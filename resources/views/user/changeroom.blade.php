@extends('user.app')

@section('content')

<style>
.header_tittle {
  height: 50px;
  color: black;
  text-align: center;
  font-size: 30px;
  font-family: 'Outfit', sans-serif;
  font-weight: 700;
}

form {
  margin-top: 120px;
}

.divtable{
  width:80%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.styled-table {
  width: 80%;
  border-collapse: collapse;
  font-size: 1em;
  font-family: sans-serif;
  color: #444;
  text-align: center;
}

.styled-table thead th {
  background-color: #f2f2f2;
  border-bottom: 1px solid #ddd;
  padding: 10px;
  text-align: left;
}

.styled-table tbody td {
  border-bottom: 1px solid #ddd;
  padding: 10px;
}

.styled-table tbody tr:nth-of-type(even) {
  background-color: #f9f9f9;
}

.styled-table tbody tr:last-of-type {
  border-bottom: 2px solid #009879;
}

.styled-table tbody tr:hover {
  background-color: #e0f0e8;
}

.styled-table tbody td:last-child {
  text-align: right;
}

.booking-details-card {
  max-width: 30rem;
  margin-top: 7%;
  margin-left: 10%;
  width: 100%;
  min-height: 0;
  border: 1px solid #c9c6cb;
  border-radius: 14px;
  overflow: hidden;
  background: #f2f0f4;
}

.booking-details-card .card-header {
  background: #ebe8e8;
  border-bottom: 1px solid #c9c6cb;
  font-size: 1.1rem;
  font-weight: 700;
  color: #1f2937;
}

.booking-details-card .card-body {
  padding: 22px 18px;
  background: #f2f0f4;
}

.booking-id {
  margin-top: 4px;
  margin-bottom: 16px;
  font-size: 1.85rem;
  font-weight: 800;
  color: #1f2937;
}

.booking-detail-grid {
  display: grid;
  gap: 10px;
}

.booking-detail-line {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  padding: 10px 12px;
  border-radius: 10px;
  background: #ebe8e8;
  color: #1f2937;
  font-size: 1.02rem;
}

.booking-detail-line strong {
  color: #4b5563;
}

.booking-detail-value {
  font-weight: 700;
  text-align: right;
}

.booking-comments-block {
  margin-top: 12px;
  padding: 12px;
  border: 1px dashed #c6c3c8;
  border-radius: 10px;
  background: #ebe8e8;
}

.booking-comments-title {
  margin-bottom: 6px;
  color: #4b5563;
  font-weight: 700;
}

.booking-comments-value {
  color: #1f2937;
  font-weight: 600;
  white-space: pre-wrap;
  word-break: break-word;
}

@media (max-width: 991px) {
  .booking-details-card {
    margin: 20px auto 0;
    max-width: 100%;
  }
}
</style>

<script>
        $(document).ready( function () {
         $('.hm-table').on('mouseenter', 'tr', function(event) {
            $('[data-toggle="tooltip"]').tooltip();
        } );
      });
    </script>

<?php
        $tittle="Bookings"
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm">
      <div class="card booking-details-card">
        <div class="card-header">Booking details:</div>
        <div class="card-body">
          <h4 class="booking-id">Booking ID {{ $book['id'] }}</h4>
          <div class="booking-detail-grid">
            <div class="booking-detail-line">
              <strong>Customer name</strong>
              <span class="booking-detail-value">{{ $customer->name }}</span>
            </div>
            <div class="booking-detail-line">
              <strong>Number of Guests</strong>
              <span class="booking-detail-value">{{ $book['guests'] }}</span>
            </div>
            <div class="booking-detail-line">
              <strong>Check In Date</strong>
              <span class="booking-detail-value">{{ $book['checkin'] }}</span>
            </div>
            <div class="booking-detail-line">
              <strong>Check Out Date</strong>
              <span class="booking-detail-value">{{ $book['checkout'] }}</span>
            </div>
          </div>
          <div class="booking-comments-block">
            <div class="booking-comments-title">Additional Comments</div>
            <div class="booking-comments-value">{{ $book['comment'] ?: '-' }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm">
   
@component('layouts.data-table')
    <thead>
        <tr>
          <th class="text-center">Room Number</th> 
          <th class="text-center">Category</th>
          <th class="text-center">Capacity</th>
          <th class="text-center">Price</th>
          <th class="text-center no-sort">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rooms as $row)
        <tr data-toggle="tooltip" data-bs-html="true" data-bs-placement="bottom" title="
          <strong>Room Number:</strong> {{ $row['room_number'] ?? 'Unassigned' }}<br>
          <strong>Category:</strong> {{ $row['category'] }}<br>
          <strong>Capacity:</strong> {{ $row['capacity'] }}<br>
          <strong>Price:</strong> {{ $row['price'] }}<br>
          <strong>Number of Beds:</strong> {{ $row['nrofbeds'] }}<br>
          <strong>Air Condition:</strong> {{ ((int) $row['aircondition']) === 1 ? 'Yes' : 'No' }}<br>
          <strong>Balcony:</strong> {{ $row['balcony'] }}<br>
          <strong>Description:</strong> {{ $row['description'] }}<br>
">
</div>
            <td>{{ $row['room_number'] ?? 'Unassigned' }}</td>
            <td>{{ $row['category'] }}</td>
            <td>{{ $row['capacity'] }}</td>
            <td>{{ $row['price'] }}</td>
            <td class=" justify-content-center align-items-center"> 
            <a href="{{ route('bookings', ['room_id' => $row['id'], 'bid' => $book]) }}"><button type="submit" class="btn btn-primary" style="min-width:100px;"><i class="fa-solid fa-arrow-pointer"  style="margin-right:4px;"></i>Select</button></a>   
            </td>
          </tr>
          @endforeach
        </tbody>
      @endcomponent
    </div>
</div>
</div> 
@endsection

