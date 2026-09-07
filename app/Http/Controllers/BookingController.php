<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Staff;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    //
    public $bid;

    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $receptionistHotelId = $this->receptionistHotelId();

        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $hotels = Hotel::select('id', 'name', 'code')
            ->orderBy('name')
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->where('id', $hotelId);
            })
            ->get();

        $baseQuery = $this->bookingsForCurrentUser();

        $results = (clone $baseQuery)
            ->where('status', "Confirmed")
            ->get();
        $pending = (clone $baseQuery)
            ->where('status', "Pending")
            ->get();
        $cancelled = (clone $baseQuery)
            ->where('status', "Cancelled")
            ->get();
        
        return view('booking.booking-home', compact('results', 'pending', 'cancelled', 'customers', 'hotels', 'receptionistHotelId'));
    }


    public function userIndex()
    {
        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $baseQuery = $this->bookingsForCurrentUser();

        $results = (clone $baseQuery)
            ->where('status', "Confirmed")
            ->get();
        $pending = (clone $baseQuery)
            ->where('status', "Pending")
            ->get();
        $cancelled = (clone $baseQuery)
            ->where('status', "Cancelled")
            ->get();
        
        return view('user.booking',compact('results','pending','cancelled','customers'));
    }

    public function roomIndex($booking_id){
        $book = $this->findBookingForCurrentUser((int) $booking_id);
        if (!$book) {
            abort(404);
        }

        $customer = Customer::find($book['customer_id']);
        $roomHotelId = optional($book->room)->hotel_id;
        $hotelId = $this->receptionistHotelId() ?? $book->hotel_id ?? $roomHotelId;

        $results = Booking::select('id', 'customer_id', 'room_id', 'hotel_id', 'guests', 'checkin', 'checkout', 'comment', 'status')
            ->with(['room:id,room_number,hotel_id', 'customer:id,name'])
            ->where('checkin', $book->checkin)
            ->where('status', 'Confirmed')
            ->when($hotelId, function ($query, $value) {
                $query->where(function ($hotelScoped) use ($value) {
                    $hotelScoped->where('hotel_id', $value)
                        ->orWhereHas('room', function ($roomQuery) use ($value) {
                            $roomQuery->where('hotel_id', $value);
                        });
                });
            })
            ->get();

        $rooms = Room::where('status', 'Free')
            ->when($hotelId, function ($query, $value) {
                $query->where('hotel_id', $value);
            })
            ->where('capacity', (int) $book->guests)
            ->get(['id', 'room_number', 'category', 'capacity', 'price', 'nrofbeds', 'aircondition', 'balcony', 'description']);

        return view('user.changeroom', compact('rooms', 'book','results','customer'));
    }

    public function chooseroom($booking_id){
        $book = $this->findBookingForCurrentUser((int) $booking_id);
        if (!$book) {
            abort(404);
        }

        $customer = Customer::find($book['customer_id']);
        $roomHotelId = optional($book->room)->hotel_id;
        $hotelId = $this->receptionistHotelId() ?? $book->hotel_id ?? $roomHotelId;

        $results = Booking::select('id', 'customer_id', 'room_id', 'hotel_id', 'guests', 'checkin', 'checkout', 'comment', 'status')
            ->with(['room:id,room_number,hotel_id', 'customer:id,name'])
            ->where('checkin', $book->checkin)
            ->where('status', 'Confirmed')
            ->when($hotelId, function ($query, $value) {
                $query->where(function ($hotelScoped) use ($value) {
                    $hotelScoped->where('hotel_id', $value)
                        ->orWhereHas('room', function ($roomQuery) use ($value) {
                            $roomQuery->where('hotel_id', $value);
                        });
                });
            })
            ->get();

        $rooms = Room::where('status', 'Free')
            ->when($hotelId, function ($query, $value) {
                $query->where('hotel_id', $value);
            })
            ->where('capacity', (int) $book->guests)
            ->get(['id', 'room_number', 'category', 'capacity', 'price', 'nrofbeds', 'aircondition', 'balcony', 'description']);


        return view('booking.booking-rooms', compact('rooms', 'book','results','customer'));
    }

    public function selectroom($room_id,$bid){
        $receptionistHotelId = $this->receptionistHotelId();

        $rooms = Room::where('id', $room_id)
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->first();
        if (!$rooms) {
            abort(404);
        }

        $booking = $this->findBookingForCurrentUser((int) $bid);
        if (!$booking) {
            abort(404);
        }

        if (!empty($booking->hotel_id) && (int) $booking->hotel_id !== (int) $rooms->hotel_id) {
            return $this->respondError(request(), 'Selected room belongs to a different hotel', 422, $this->bookingRedirectPath());
        }

        if ((int) $rooms->capacity !== (int) $booking->guests) {
            return $this->respondError(request(), 'Selected room capacity must match the booking guest count', 422, $this->bookingRedirectPath());
        }

        $rooms->status = "Booked";
        $rooms->save();

        if ($booking->status == "Confirmed" && !empty($booking->room_id)) {
            $roomFree = Room::find($booking->room_id);
            if ($roomFree) {
                $roomFree->status = "Free";
                $roomFree->save();
            }
        }

        $booking->room_id = $room_id;
        $booking->hotel_id = $rooms->hotel_id;
        $booking->status = "Confirmed";
        $booking->save();

        $displayRoomNumber = $rooms->room_number ?? ('#' . $rooms->id);
        return redirect($this->bookingRedirectPath())->with('message', 'Room ' . $displayRoomNumber . ' Has Been Selected');
    }
    
    
    public function complete($id){
        $booking = $this->findBookingForCurrentUser((int) $id);
        if (!$booking) {
            abort(404);
        }

        $booking->status = "Completed";
        $booking->save();

        $roomid = $booking->room_id;
        $room = Room::find($roomid);
        if ($room) {
            $room->status = "Free";
            $room->save();
        }

        $start = $booking->checkin;
        $end = $booking->checkout;
        $datetime1 = new DateTime($start);
        $datetime2 = new DateTime($end);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $price = $room ? ($days * $room->price) : 0;
                
        $pay = new Payment();
        $pay->customer_id = $booking->customer_id;
        $pay->booking_id = $id;
        $pay->duration = $days;
        $pay->price = $price;
        $pay->save();
        
        return redirect($this->bookingRedirectPath())->with('message', 'Booking Has Been Completed');
    }

    public function cancel($id){
        $booking = $this->findBookingForCurrentUser((int) $id);
        if (!$booking) {
            abort(404);
        }

        $booking->status = "Cancelled";
        $booking->save();

        $roomid = $booking->room_id;
        $room = Room::find($roomid);
        if ($room) {
            $room->status = "Free";
            $room->save();
        }

        return redirect($this->bookingRedirectPath())->with('message','Booking Has Been Cancelled');
    }
    
    public function store(Request $request)
    {
        $scopeHotelId = $this->receptionistHotelId();

        $rules = [
            'customer_id' => 'required|integer|exists:customers,id',
            'guests'=>'required|integer|min:0',
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after_or_equal:checkin',
            'comment' => 'nullable|string|max:255',
        ];

        if (is_null($scopeHotelId)) {
            $rules['hotel_id'] = 'required|integer|exists:hotels,id';
        }

        $data = $request->validate($rules);

        $booking = new Booking();
        $booking->customer_id = $data['customer_id'];
        $booking->hotel_id = $scopeHotelId ?? $data['hotel_id'];
        $booking->guests = $data['guests'];
        $booking->checkin = $data['checkin'];
        $booking->checkout = $data['checkout'];
        $booking->status = 'Pending';
        $booking->comment = $data['comment'] ?? '';
        $booking->save();

        return $this->respondSuccess($request, 'Booking has been added', $this->bookingRedirectPath());
    }

    private function bookingsForCurrentUser()
    {
        $receptionistHotelId = $this->receptionistHotelId();

        return Booking::select('id', 'customer_id', 'room_id', 'hotel_id', 'guests', 'checkin', 'checkout', 'comment', 'status')
            ->with(['room:id,room_number,hotel_id', 'customer:id,name'])
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->where(function ($hotelScoped) use ($hotelId) {
                    $hotelScoped->where('hotel_id', $hotelId)
                        ->orWhereHas('room', function ($roomQuery) use ($hotelId) {
                            $roomQuery->where('hotel_id', $hotelId);
                        });
                });
            });
    }

    private function findBookingForCurrentUser(int $bookingId): ?Booking
    {
        return $this->bookingsForCurrentUser()
            ->where('id', $bookingId)
            ->first();
    }

    private function receptionistHotelId(): ?int
    {
        if (!Auth::check() || $this->isAdminUser()) {
            return null;
        }

        return Staff::where('username', Auth::user()->username)->value('hotel_id');
    }

    private function isAdminUser(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        $isAdmin = $user->isadmin ?? $user->isAdmin ?? 0;
        return (int) $isAdmin === 1;
    }

    private function bookingRedirectPath(): string
    {
        return $this->isAdminUser() ? '/booking' : '/user/booking';
    }
}
