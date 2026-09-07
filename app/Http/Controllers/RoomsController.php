<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use DB;

class RoomsController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }
   
     public function index()    
    {
        $receptionistHotelId = $this->receptionistHotelId();

        $results = Room::select('id', 'room_number', 'room_floor', 'room_position', 'category', 'capacity', 'price', 'nrofbeds', 'aircondition', 'balcony', 'status', 'description', 'hotel_id')
            ->orderBy('id', 'desc')
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->get();

        $hotels = Hotel::select('id', 'name', 'code')
            ->orderBy('name')
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->where('id', $hotelId);
            })
            ->get();

        return view('rooms.rooms-home', compact('results','hotels'));
    }

    
    public function userIndex()    
    {
        $receptionistHotelId = $this->receptionistHotelId();

        $results = Room::select('id', 'room_number', 'room_floor', 'room_position', 'category', 'capacity', 'price', 'nrofbeds', 'aircondition', 'balcony', 'status', 'description', 'hotel_id')
            ->orderBy('id', 'desc')
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->get();

        $hotels = Hotel::select('id', 'name', 'code')
            ->orderBy('name')
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->where('id', $hotelId);
            })
            ->get();

        return view('user.rooms', compact('results','hotels'));
    }

    public function addrooms($room_id)
    {
        $receptionistHotelId = $this->receptionistHotelId();

        $room = DB::table('rooms')
            ->where('id', $room_id)
            ->when($receptionistHotelId, function ($query, $hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->first();

        if (!$room) {
            abort(404);
        }

        return view('rooms.rooms-specific', compact('room'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hotel_id' => 'required|integer|exists:hotels,id',
            'room_floor' => 'required|integer|between:1,3',
            'room_position' => 'required|integer|between:1,4',
            'category' => 'required',
            'capacity'=>'required|integer|min:0',
            'price'=>'required|numeric|min:0',
            'nrofbeds'=>'required|integer|min:0',
            'aircondition'=>'required|in:Yes,No',
            'balcony'=>'required|integer|min:0',
            'description'=>'nullable',
            'image'=>'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $scopeHotelId = $this->receptionistHotelId();
        if (!is_null($scopeHotelId) && (int) $data['hotel_id'] !== $scopeHotelId) {
            return $this->respondError($request, 'You can only manage rooms in your hotel', 403, '/user/rooms');
        }

        $currentCount = Room::where('hotel_id', $data['hotel_id'])->count();
        if ($currentCount >= 12) {
            return $this->respondError($request, 'This hotel already has 12 rooms (3 floors x 4 rooms)', 422, '/rooms');
        }

        $exists = Room::where('hotel_id', $data['hotel_id'])
            ->where('room_floor', $data['room_floor'])
            ->where('room_position', $data['room_position'])
            ->exists();
        if ($exists) {
            return $this->respondError($request, 'This floor/room position already exists in the selected hotel', 422, '/rooms');
        }

        $hotel = Hotel::findOrFail($data['hotel_id']);
        $roomNumber = $this->buildRoomNumber($hotel, (int) $data['room_floor'], (int) $data['room_position']);
        $imagePath = $request->file('image')->store('rooms', 'public');

        $room = new Room();
        $room->room_number = $roomNumber;
        $room->room_floor = $data['room_floor'];
        $room->room_position = $data['room_position'];
        $room->category = $data['category'];
        $room->capacity = $data['capacity'];
        $room->price = $data['price'];
        $room->nrofbeds = $data['nrofbeds'];
        $room->aircondition = $data['aircondition'] === 'Yes' ? 1 : 0;
        $room->balcony = $data['balcony'];
        $room->description = $data['description'] ?? null;
        $room->image = $imagePath;
        $room->hotel_id = $data['hotel_id'];
        $room->status = 'Free';
        $room->save();

        return $this->respondSuccess($request, 'Room has been added successfully', '/rooms');
    }

public function edit(Request $request){
    $data = $request->validate([
        'id' => 'required|integer|exists:rooms,id',
        'hotel_id' => 'required|integer|exists:hotels,id',
        'room_floor' => 'required|integer|between:1,3',
        'room_position' => 'required|integer|between:1,4',
        'category' => 'required',
        'capacity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'nrofbeds' => 'required|integer|min:0',
        'aircondition' => 'required|in:Yes,No',
        'balcony' => 'required|integer|min:0',
        'description' => 'nullable',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
    ]);

    $scopeHotelId = $this->receptionistHotelId();
    if (!is_null($scopeHotelId) && (int) $data['hotel_id'] !== $scopeHotelId) {
        return $this->respondError($request, 'You can only manage rooms in your hotel', 403, '/user/rooms');
    }

    $room = Room::findOrFail($data['id']);

    if (!is_null($scopeHotelId) && (int) $room->hotel_id !== $scopeHotelId) {
        return $this->respondError($request, 'You can only manage rooms in your hotel', 403, '/user/rooms');
    }

    $newHotelId = (int) $data['hotel_id'];
    $newFloor = (int) $data['room_floor'];
    $newPosition = (int) $data['room_position'];

    $isChangingHotel = (int) $room->hotel_id !== $newHotelId;
    $isChangingSlot = $isChangingHotel
        || (int) $room->room_floor !== $newFloor
        || (int) $room->room_position !== $newPosition;

    if ($isChangingHotel) {
        $targetHotelRoomCount = Room::where('hotel_id', $newHotelId)->count();
        if ($targetHotelRoomCount >= 12) {
            return $this->respondError($request, 'This hotel already has 12 rooms (3 floors x 4 rooms)', 422, '/rooms');
        }
    }

    if ($isChangingSlot) {
        $duplicateSlot = Room::where('hotel_id', $newHotelId)
            ->where('room_floor', $newFloor)
            ->where('room_position', $newPosition)
            ->where('id', '!=', $room->id)
            ->exists();
        if ($duplicateSlot) {
            return $this->respondError($request, 'This floor/room position already exists in the selected hotel', 422, '/rooms');
        }
    }

    $hotel = Hotel::findOrFail($newHotelId);
    $roomNumber = $this->buildRoomNumber($hotel, $newFloor, $newPosition);

    $update = [
        'id' => $data['id'],
        'hotel_id' => $data['hotel_id'],
        'room_number' => $roomNumber,
        'room_floor' => $data['room_floor'],
        'room_position' => $data['room_position'],
        'category' => $data['category'],
        'capacity' => $data['capacity'],
        'price' => $data['price'],
        'nrofbeds' => $data['nrofbeds'],
        'balcony' => $data['balcony'],
        'description' => $data['description'],
        'aircondition' => $data['aircondition'] === 'Yes' ? 1 : 0,
    ];

    if ($request->hasFile('image')) {
        $update['image'] = $request->file('image')->store('rooms', 'public');
    }

    DB::table('rooms')->where('id', $data['id'])->update($update);
    return $this->respondSuccess($request, 'Room has been edited successfully', '/rooms');
}


public function destroy(Request $request){
    $room_id = (int) $request->id;

    $scopeHotelId = $this->receptionistHotelId();
    if (!is_null($scopeHotelId)) {
        $belongsToReceptionistHotel = Room::where('id', $room_id)
            ->where('hotel_id', $scopeHotelId)
            ->exists();
        if (!$belongsToReceptionistHotel) {
            return $this->respondError($request, 'You can only manage rooms in your hotel', 403, '/user/rooms');
        }
    }

    $bookingCount = DB::table('bookings')->where('room_id', $room_id)->count();
    
        if ($bookingCount > 0) {
            // If the customer has bookings, do not delete and return a message
            return $this->respondError($request, 'Room has bookings and cannot be deleted', 409, '/rooms');
        } else {
            // If the customer has no bookings, delete the customer record
            DB::table('rooms')->where('id', $room_id)->delete();
            return $this->respondSuccess($request, 'Room has been deleted successfully', '/rooms');
        }
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

    private function buildRoomNumber(Hotel $hotel, int $floor, int $position): string
    {
        $prefix = strtoupper((string) $hotel->code);
        if ($prefix === '') {
            $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $hotel->name), 0, 1) ?: 'H');
        }

        return $prefix . $floor . str_pad((string) $position, 2, '0', STR_PAD_LEFT);
    }

}
