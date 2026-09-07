<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    public function index()
    {
        $hotels = Hotel::select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('booking.public-home', [
            'hotels' => $hotels,
            'rooms' => collect(),
            'search' => null,
            'selectedHotel' => null,
        ]);
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => 'required|integer|exists:hotels,id',
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
            'guests' => 'required|integer|min:1',
        ]);

        $selectedHotel = Hotel::select('id', 'name')
            ->find($data['hotel_id']);

        $data['hotel_name'] = optional($selectedHotel)->name;

        $rooms = $this->availableRooms($data['hotel_id'], $data['checkin'], $data['checkout'], $data['guests']);

        if ($request->expectsJson()) {
            return response()->json([
                'search' => $data,
                'rooms' => $rooms,
                'selected_hotel' => $selectedHotel,
            ]);
        }

        $hotels = Hotel::select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('booking.public-home', [
            'hotels' => $hotels,
            'rooms' => $rooms,
            'search' => $data,
            'selectedHotel' => $selectedHotel,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'personal_id' => 'required|regex:/^[A-Z][0-9]{8}[A-Z]$/',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:50',
            'birthdate' => 'required|date|before:today',
            'phone' => 'required|regex:/^\+?[1-9]\d(?:[\s-]?\d){6,13}$/',
            'guests' => 'required|integer|min:1',
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
            'room_id' => 'required|integer|exists:rooms,id',
            'comment' => 'nullable|string|max:255',
        ]);

        if (!$this->isRoomAvailable($data['room_id'], $data['checkin'], $data['checkout'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Selected room is no longer available for those dates.',
                ], 409);
            }
            return back()
                ->withInput()
                ->with('error', 'Selected room is no longer available for those dates.');
        }

        $customer = Customer::where('personal_id', $data['personal_id'])->first();
        if (!$customer) {
            $customer = Customer::create([
                'personal_id' => $data['personal_id'],
                'name' => $data['name'],
                'gender' => $data['gender'],
                'birthdate' => $data['birthdate'],
                'phone' => $data['phone'],
            ]);
        } else {
            $customer->name = $data['name'];
            $customer->gender = $data['gender'];
            $customer->birthdate = $data['birthdate'];
            $customer->phone = $data['phone'];
            $customer->save();
        }

        $room = Room::find($data['room_id']);

        $booking = new Booking();
        $booking->customer_id = $customer->id;
        $booking->room_id = $data['room_id'];
        $booking->hotel_id = optional($room)->hotel_id;
        $booking->guests = $data['guests'];
        $booking->checkin = $data['checkin'];
        $booking->checkout = $data['checkout'];
        $booking->comment = $data['comment'] ?? '';
        $booking->status = 'Confirmed';
        $booking->save();

        if ($room) {
            $room->status = 'Booked';
            $room->save();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Booking confirmed.',
                'booking_id' => $booking->id,
            ]);
        }

        return redirect()
            ->route('public-booking')
            ->with('message', 'Booking confirmed.');
    }

    private function availableRooms($hotelId, $checkin, $checkout, $guests)
    {
        return Room::where('hotel_id', $hotelId)
            ->where('status', 'Free')
            ->where('capacity', '>=', $guests)
            ->whereDoesntHave('booking', function ($query) use ($checkin, $checkout) {
                $query->whereIn('status', ['Pending', 'Confirmed'])
                    ->where('checkin', '<', $checkout)
                    ->where('checkout', '>', $checkin);
            })
            ->with('hotel:id,name,address,phone,email')
            ->get([
                'id',
                'hotel_id',
                'room_number',
                'category',
                'capacity',
                'price',
                'nrofbeds',
                'aircondition',
                'balcony',
                'description',
                'image',
                'status',
            ]);
    }

    private function isRoomAvailable($roomId, $checkin, $checkout)
    {
        return !Booking::where('room_id', $roomId)
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->where('checkin', '<', $checkout)
            ->where('checkout', '>', $checkin)
            ->exists();
    }
}
