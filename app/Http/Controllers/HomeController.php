<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', $this->buildDashboardData());
    }



    public function userIndex()
    {
        return view('user.home', $this->buildDashboardData($this->receptionistHotelId()));
    }

    private function buildDashboardData(?int $hotelId = null)
    {
        $bookings = Booking::select('id', 'customer_id', 'room_id', 'hotel_id', 'guests', 'checkin', 'checkout', 'comment', 'status')
            ->with(['room:id,room_number,hotel_id', 'customer:id,name'])
            ->where(function ($query) {
                $query->where('status', 'Confirmed')
                    ->orWhere('status', 'Completed');
            })
            ->when($hotelId, function ($query, $value) {
                $query->where(function ($hotelScoped) use ($value) {
                    $hotelScoped->where('hotel_id', $value)
                        ->orWhereHas('room', function ($roomQuery) use ($value) {
                            $roomQuery->where('hotel_id', $value);
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $bookingCount = Booking::when($hotelId, function ($query, $value) {
                $query->where(function ($hotelScoped) use ($value) {
                    $hotelScoped->where('hotel_id', $value)
                        ->orWhereHas('room', function ($roomQuery) use ($value) {
                            $roomQuery->where('hotel_id', $value);
                        });
                });
            })
            ->count();
        $roomCount = DB::table('rooms')
            ->when($hotelId, function ($query, $value) {
                $query->where('hotel_id', $value);
            })
            ->count();
        $customerCount = DB::table('customers')->count();
        $visitors = Booking::when($hotelId, function ($query, $value) {
                $query->where(function ($hotelScoped) use ($value) {
                    $hotelScoped->where('hotel_id', $value)
                        ->orWhereHas('room', function ($roomQuery) use ($value) {
                            $roomQuery->where('hotel_id', $value);
                        });
                });
            })
            ->sum('guests');

        $roomTypeCounts = Booking::join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->when($hotelId, function ($query, $value) {
                $query->where('rooms.hotel_id', $value);
            })
            ->select('rooms.category', DB::raw('count(*) as total'))
            ->groupBy('rooms.category')
            ->pluck('total', 'category');

        $singleCount = $roomTypeCounts->get('Single', 0);
        $doubleCount = $roomTypeCounts->get('Double', 0);
        $twinCount = $roomTypeCounts->get('Twin', 0);
        $quadCount = $roomTypeCounts->get('Quad', 0);
        $suiteCount = $roomTypeCounts->get('Suite', 0);
        $villaCount = $roomTypeCounts->get('Villa', 0);

        return compact(
            'bookings',
            'bookingCount',
            'roomCount',
            'customerCount',
            'visitors',
            'singleCount',
            'doubleCount',
            'twinCount',
            'quadCount',
            'suiteCount',
            'villaCount'
        );
    }

    private function receptionistHotelId(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        $isAdmin = Auth::user()->isadmin ?? Auth::user()->isAdmin ?? 0;
        if ((int) $isAdmin === 1) {
            return null;
        }

        return Staff::where('username', Auth::user()->username)->value('hotel_id');
    }
}
