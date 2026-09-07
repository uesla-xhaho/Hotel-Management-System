<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use PDF;

class PaymentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $receptionistHotelId = $this->receptionistHotelId();

        $payment = Payment::select('id', 'booking_id', 'customer_id', 'duration', 'price', 'status')
            ->with(['booking:id,room_id,hotel_id', 'booking.room:id,room_number,hotel_id', 'customer:id,name'])
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->whereHas('booking', function ($bookingQuery) use ($hotelId) {
                    $bookingQuery->where(function ($hotelScoped) use ($hotelId) {
                        $hotelScoped->where('hotel_id', $hotelId)
                            ->orWhereHas('room', function ($roomQuery) use ($hotelId) {
                                $roomQuery->where('hotel_id', $hotelId);
                            });
                    });
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('payment.payment-home', compact('payment'));
    }

    public function userIndex()
    {
        $receptionistHotelId = $this->receptionistHotelId();

        $payment = Payment::select('id', 'booking_id', 'customer_id', 'duration', 'price', 'status')
            ->with(['booking:id,room_id,hotel_id', 'booking.room:id,room_number,hotel_id', 'customer:id,name'])
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->whereHas('booking', function ($bookingQuery) use ($hotelId) {
                    $bookingQuery->where(function ($hotelScoped) use ($hotelId) {
                        $hotelScoped->where('hotel_id', $hotelId)
                            ->orWhereHas('room', function ($roomQuery) use ($hotelId) {
                                $roomQuery->where('hotel_id', $hotelId);
                            });
                    });
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('user.payment', compact('payment'));
    }


    public function invoice($id)
    {
        $results = Payment::with('booking.room.hotel', 'customer')
        ->where('id', $id)
        ->get();

        //$payment = Payment::with('booking.rooms')->find($id);

        return view('payment.invoice',compact('results'));
    }

    public function complete(Request $request){
        $payment=Payment::find($request->id);
        $payment->status = "Completed";
        $payment->save();
        
        return $this->respondSuccess($request, 'Payment has been completed', '/payment');
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:payments,id',
        ]);

        $receptionistHotelId = $this->receptionistHotelId();

        $payment = Payment::where('id', $data['id'])
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->whereHas('booking', function ($bookingQuery) use ($hotelId) {
                    $bookingQuery->where(function ($hotelScoped) use ($hotelId) {
                        $hotelScoped->where('hotel_id', $hotelId)
                            ->orWhereHas('room', function ($roomQuery) use ($hotelId) {
                                $roomQuery->where('hotel_id', $hotelId);
                            });
                    });
                });
            })
            ->first();

        if (!$payment) {
            return $this->respondError($request, 'Payment not found', 404, $this->paymentRedirectPath());
        }

        $payment->delete();

        return $this->respondSuccess($request, 'Payment deleted successfully', $this->paymentRedirectPath());
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

    private function paymentRedirectPath(): string
    {
        return $this->isAdminUser() ? '/payment' : '/user/payment';
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
}
