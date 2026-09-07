@extends('layouts.app')

@section('content')
  <div class="mb-2">
     <strong>Room Number:</strong> {{ $room->room_number ?? 'Unassigned' }}
   </div>
  <div class="mb-2">
     <strong>Category:</strong><span class="phone"> {{ $room->category }}</span>
  </div>
   <div class="mb-2">
    <strong>Capacity:</strong><span class="email"> {{ $room->capacity }}</span> 
    </div>
   <div class="mb-2">
     <strong>Price:</strong><span class="year"> {{ $room->price }}</span> 
 </div>
 <div class="mb-2">
     <strong>Number of Beds:</strong> <span class="address">{{ $room->nrofbeds }}</span>
   </div>
  <div class="mb-2">
     <strong>Air Condition:</strong><span class="category"> {{ ((int) $room->aircondition) === 1 ? 'Yes' : 'No' }}</span>
  </div>
   <div class="mb-2">
    <strong>Balcony:</strong> {{ $room->balcony }}
    </div>
 <div class="mb-2">
     <strong>Status:</strong><span class="year"> {{ $room->status }}</span> 
 </div>
@endsection
