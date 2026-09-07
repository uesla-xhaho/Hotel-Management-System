@extends('layouts.app')

@section('content')

<style>



.styled-table {
  width: 70%;
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
  justify-content:center;
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

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

.tittle {
  display: flex;
  justify-content: center;
  align-items: center;
  color: #080155;
  font-size: 20px;
  font-family: 'Outfit', sans-serif;
  text-transform: uppercase;
}

.dropdown {
  position: relative;
  right: 28px;
  display: inline-block;
}

.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 10px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 2000;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align:center;
}

.dropdown-content a:hover {
  background-color: #d6d0d0;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}


        #submit-btn:hover {
      transform: scale(1.08);
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    }

.customer-search-wrap {
  position: relative;
}

.customer-search-input {
  width: 100%;
}

.customer-results {
  position: absolute;
  top: calc(100% + 6px);
  left: 0;
  right: 0;
  max-height: 220px;
  overflow-y: auto;
  background: #fff;
  border-radius: 12px;
  border: 1px solid #c9c9c9;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  z-index: 10;
}

.customer-option {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  border: none;
  background: transparent;
  text-align: left;
  padding: 10px 12px;
  cursor: pointer;
  color: #1f2937;
}

.customer-option:hover,
.customer-option:focus {
  background: #e8f0fb;
  outline: none;
}

.customer-option.selected {
  background: #dcfce7;
  color: #166534;
  font-weight: 600;
}

.customer-option-check {
  display: none;
  font-size: 12px;
  color: #166534;
}

.customer-option.selected .customer-option-check {
  display: inline;
}

.customer-helper {
  display: block;
  margin-top: 6px;
  font-size: 13px;
  text-align: left;
  color: #666;
}

/* Prevent action dropdown from being clipped by table card */
.hm-table-card,
.hm-table-rail,
.hm-table-card .dataTables_wrapper {
  overflow: visible !important;
}

#nav-home .dropdown,
#nav-profile .dropdown {
  z-index: 30;
}
</style>


<?php
        $tittle="Bookings"
?>
@if ($errors->has('message'))
    <div class="alert alert-danger">
        {{ $errors->first('message') }}
    </div>
@endif
@if (session('message'))
    <div class="alert alert-success alert-dismissible fade show" id="myAlert">
        {{ session('message') }}
    <span class="close-alert" aria-hidden="true" style="float:right;" onclick="closeAlert()">&times;</span>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" id="myAlert">
        {{ session('error') }}
    <span class="close-alert" aria-hidden="true" style="float:right;" onclick="closeAlert()">&times;</span>
    </div>
@endif
<div id="tab-position">
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-default-tab" data-bs-toggle="tab" data-bs-target="#nav-default" type="button" role="tab" aria-controls="nav-home" aria-selected="true">New Booking</button>
    <button class="nav-link" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Pending Bookings</button>
    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Confirmed Bookings</button>
    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Cancelled Bookings</button>
  </div>
</nav>
      </div>
<div class="tab-content" id="nav-tabContent">
<div class="tab-pane fade show active" id="nav-default" role="tabpanel" aria-labelledby="nav-home-tab">
<div class="container" id="register-container">
    <div class="row justify-content-center">
        <div class="col-md-8">

                    <form method="POST" action="{{ route('bookingadd') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home, #nav-profile, #nav-contact" data-hm-reset="true">
                        @csrf

                        @if (empty($receptionistHotelId))
                        <div class="row mb-3">
                            <label for="hotel_id" class="col-md-4 col-form-label text-md-end">{{ __('Hotel:') }}</label>
                            <div class="col-md-6">
                                <select name="hotel_id" class="form-select @error('hotel_id') is-invalid @enderror" id="hotel_id" required>
                                    @foreach($hotels as $hotel)
                                        <option value="{{ $hotel['id'] }}" {{ (string) old('hotel_id') === (string) $hotel['id'] ? 'selected' : '' }}>
                                            {{ $hotel['name'] }}{{ !empty($hotel['code']) ? ' - ' . $hotel['code'] : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hotel_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @else
                            <input type="hidden" name="hotel_id" value="{{ $receptionistHotelId }}">
                        @endif

                        <div class="row mb-3">
                            <label for="customer_search" class="col-md-4 col-form-label text-md-end">{{ __('Customer Name:') }}</label>
                            <div class="col-md-6">
                                <div class="customer-search-wrap">
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">
                                    <input
                                        type="text"
                                        id="customer_search"
                                        class="form-control customer-search-input @error('customer_id') is-invalid @enderror"
                                        value="{{ old('customer_search') }}"
                                        autocomplete="off"
                                        required
                                    >
                                    <div id="customer_results" class="customer-results d-none" role="listbox" aria-label="Customer search results"></div>
                                </div>
                                <span id="customer_search_error" class="invalid-feedback d-none" role="alert">
                                    <strong>Please select a customer from the list.</strong>
                                </span>
                                @error('customer_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="guests" class="col-md-4 col-form-label text-md-end">{{ __('Number of guests:') }}</label>

                            <div class="col-md-6">
                                <input id="guests" type="number" class="form-control @error('guests') is-invalid @enderror" name="guests" value="{{ old('guests') }}" required autocomplete="guests" autofocus>

                                @error('guests')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="checkin" class="col-md-4 col-form-label text-md-end">{{ __('Check In Date:') }}</label>

                            <div class="col-md-6">
                                <input id="checkin" type="date" class="form-control @error('checkin') is-invalid @enderror" name="checkin" value="{{ old('checkin') }}" required autocomplete="checkin">

                                @error('checkin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="checkout" class="col-md-4 col-form-label text-md-end">{{ __('Check Out Date:') }}</label>

                            <div class="col-md-6">
                                <input id="checkout" type="date" class="form-control @error('checkout') is-invalid @enderror" name="checkout" value="{{ old('checkout') }}" required autocomplete="checkout">

                                @error('checkout')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="comment" class="col-md-4 col-form-label text-md-end">{{ __('Additional Comments:') }}</label>

                            <div class="col-md-6">
                                <input id="comment" type="text" class="form-control @error('comment') is-invalid @enderror" name="comment" value="">

                            </div>
                        </div>


                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" id="submit-btn" class="btn btn-primary">
                                    {{ __('Add Booking') }}
                                </button>
                            </div>
                        </div>
                    </form>


                    @if ($errors->any())
                    <div class="w-4/8 m-auto text-center">
                       
                        @endif
                    </div>
        </div>
    </div>
</div>
  <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
  <main class="content">
<div class="divtable">
@component('layouts.data-table', ['cookieKey' => 'admin-booking-pending'])
  <thead>
      <tr>
        <th class="text-center">Booking ID</th> 
        <th class="text-center">Customer Name</th>
        <th class="text-center">Number of guests</th>
        <th class="text-center">Room Number</th>
        <th class="text-center">Check In</th>
        <th class="text-center">Check Out</th>
        <th class="text-center">Comment</th>
        <th class="text-center">Status</th>
        <th class="text-center no-sort">Action</th>
      </tr>
      </thead>
      <tbody>
      @foreach($pending as $row)
        <tr>
          <td>{{ $row['id'] }}</td>
          <td>{{ $row->customer->name }}</td>
          <td>{{ $row['guests'] }}</td>
          <td>{{ optional($row->room)->room_number ?? 'Unassigned' }}</td>
          <td>{{ $row['checkin'] }}</td>
          <td>{{ $row['checkout'] }}</td>
          <td>{{ $row['comment'] }}</td>
          <td ><span style="border-radius:15px;background-color:#f2bb02; color: #fff; padding: 7px;">{{ $row['status'] }}</span></td>
          <td>
        <div class="dropdown justify-content-center align-items-center">
          <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          </button>
          <div class="dropdown-content" aria-labelledby="dropdownMenuButton" >
              <a class="dropdown-item" href="{{ route('booking3',$booking_id= $row['id']) }}"><i class="fa-solid fa-bed" style="margin-right:3px;"></i>Choose Room</a>
              <a class="dropdown-item" href="{{ route('booking4',$id= $row['id']) }}"><i class="fa-solid fa-xmark" style="margin-right:3px;"></i>Cancel</a> 
          </div>
       </div>
     </td>
  </tr>
      @endforeach
      </tbody>
@endcomponent
    </div>
      </main>
  </div>
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
  @component('layouts.data-table', ['cookieKey' => 'admin-booking-active'])
    <thead>
        <tr>
          <th class="text-center">Booking ID</th> 
          <th class="text-center">Customer Name</th>
          <th class="text-center">Number of guests</th>
          <th class="text-center">Room Number</th>
          <th class="text-center">Check In</th>
          <th class="text-center">Check Out</th>
          <th class="text-center">Comment</th>
          <th class="text-center">Status</th>
          <th class="text-center no-sort">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $row) 
          <tr>
            <td>{{ $row['id'] }}</td>
            <td>{{ $row->customer->name }}</td>
            <td>{{ $row['guests'] }}</td>
            <td>{{ optional($row->room)->room_number ?? 'Unassigned' }}</td>
            <td>{{ $row['checkin'] }}</td>
            <td>{{ $row['checkout'] }}</td>
            <td>{{ $row['comment'] }}</td>
            <td ><span style="border-radius:15px;background-color:#021af2; color: #fff; padding: 7px;">{{ $row['status'] }}</span></td>
            <td>
      <div class="dropdown justify-content-center align-items-center">
        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        </button>
        <div class="dropdown-content" aria-labelledby="dropdownMenuButton" >
        <a class="dropdown-item" href="{{ route('completebooking', $id = $row['id']) }}"><i class="fa-solid fa-check"  style="margin-right:3px;"></i>Booking Completed</a>
          <a class="dropdown-item" href="{{ route('booking3', $booking_id = $row['id']) }}"><i class="fa-solid fa-bed"  style="margin-right:3px;"></i> Change Room</a>
          <a class="dropdown-item" href="{{ route('booking4', $id = $row['id']) }}"><i class="fa-solid fa-xmark" style="margin-right:3px;"></i>Cancel</a>
        </div>
      </div>
    </td>
          </tr>
        @endforeach
        </tbody>
      @endcomponent
  </div>
  <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            @component('layouts.data-table', ['cookieKey' => 'admin-booking-cancelled'])
                  <thead>
                    <tr>
                    <th class="text-center">Booking ID</th> 
                    <th class="text-center">Customer Name</th>
                    <th class="text-center">Number of guests</th>
                    <th class="text-center">Room Number</th>
                    <th class="text-center">Check In</th>
                    <th class="text-center">Check Out</th>
                    <th class="text-center">Comment</th>
                    <th class="text-center">Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($cancelled as $row)
                    <tr>
                      <td>{{ $row['id'] }}</td>
                      <td>{{ $row->customer->name ?? '' }}</td>
                      <td>{{ $row['guests'] }}</td>
                      <td>{{ optional($row->room)->room_number ?? 'Unassigned' }}</td>
                      <td>{{ $row['checkin'] }}</td>
                      <td>{{ $row['checkout'] }}</td>
                      <td>{{ $row['comment'] }}</td>
                      <td><span style="border-radius:15px;background-color:#f20206; color: #fff; padding: 7px;">{{ $row['status'] }}</span></td>
                    </tr>
                  @endforeach
                  </tbody>
                @endcomponent
                </div>
  </div>    
      </main>
  </div>
<script>
  (function () {
    var customers = @json(collect($customers)->map(function ($row) {
      return [
        'id' => $row['id'],
        'name' => $row['name'],
      ];
    })->values());

    var customerSearchInput = document.getElementById('customer_search');
    var customerIdInput = document.getElementById('customer_id');
    var customerResults = document.getElementById('customer_results');
    var customerSearchError = document.getElementById('customer_search_error');
    var selectedCustomerId = customerIdInput.value ? String(customerIdInput.value) : '';

    if (!customerSearchInput || !customerIdInput || !customerResults) {
      return;
    }

    function escapeHtml(value) {
      var div = document.createElement('div');
      div.textContent = value;
      return div.innerHTML;
    }

    function getCustomerLabel(customer) {
      return String(customer.name || '');
    }

    function setSelectedState(label, id) {
      selectedCustomerId = String(id || '');
    }

    function clearSelectedState() {
      selectedCustomerId = '';
    }

    function hideResults() {
      customerResults.classList.add('d-none');
      customerResults.innerHTML = '';
    }

    function renderResults(items) {
      if (!items.length) {
        customerResults.innerHTML = '<div class="customer-option" style="cursor:default;">No customers found.</div>';
        customerResults.classList.remove('d-none');
        return;
      }

      customerResults.innerHTML = items.map(function (customer) {
        var label = getCustomerLabel(customer);
        var selectedClass = String(customer.id) === selectedCustomerId ? ' selected' : '';
        return '<button type="button" class="customer-option' + selectedClass + '" data-id="' + customer.id + '"><span class="customer-option-name">' + escapeHtml(label) + '</span><span class="customer-option-check">Selected</span></button>';
      }).join('');
      customerResults.classList.remove('d-none');
    }

    function filterCustomers() {
      var query = customerSearchInput.value.trim().toLowerCase();
      customerIdInput.value = '';
      clearSelectedState();

      if (!query) {
        hideResults();
        return;
      }

      var matches = customers.filter(function (customer) {
        var name = String(customer.name || '').toLowerCase();
        return name.indexOf(query) !== -1 || String(customer.id).indexOf(query) !== -1;
      }).slice(0, 50);

      renderResults(matches);
    }

    function selectCustomer(id, label) {
      customerIdInput.value = id;
      customerSearchInput.value = label;
      setSelectedState(label, id);
      hideResults();
      if (customerSearchError) {
        customerSearchError.classList.add('d-none');
      }
    }

    function getOptionLabel(button) {
      var labelNode = button.querySelector('.customer-option-name');
      return labelNode ? labelNode.textContent.trim() : button.textContent.trim();
    }

    function handleOptionSelection(event) {
      var button = event.target.closest('.customer-option');
      if (!button || !button.getAttribute('data-id')) {
        return;
      }

      if (event.type === 'mousedown') {
        event.preventDefault();
      }

      selectCustomer(button.getAttribute('data-id'), getOptionLabel(button));
    }

    customerSearchInput.addEventListener('input', filterCustomers);

    customerResults.addEventListener('mousedown', handleOptionSelection);
    customerResults.addEventListener('click', handleOptionSelection);

    customerSearchInput.addEventListener('keydown', function (event) {
      if (event.key !== 'Enter' || customerIdInput.value) {
        return;
      }

      var firstOption = customerResults.querySelector('.customer-option[data-id]');
      if (!firstOption) {
        return;
      }

      event.preventDefault();
      var labelNode = firstOption.querySelector('.customer-option-name');
      selectCustomer(firstOption.getAttribute('data-id'), labelNode ? labelNode.textContent.trim() : firstOption.textContent.trim());
    });

    customerSearchInput.addEventListener('blur', function () {
      setTimeout(hideResults, 200);
    });

    customerSearchInput.addEventListener('focus', function () {
      if (customerSearchInput.value.trim() !== '' && !customerIdInput.value) {
        filterCustomers();
      }
    });

    if (customerSearchInput.form) {
      customerSearchInput.form.addEventListener('submit', function (event) {
        if (!customerIdInput.value) {
          event.preventDefault();
          if (customerSearchError) {
            customerSearchError.classList.remove('d-none');
          }
          customerSearchInput.focus();
        }
      });
    }

    if (customerIdInput.value) {
      customers.forEach(function (customer) {
        if (String(customer.id) === String(customerIdInput.value)) {
          var label = getCustomerLabel(customer);
          customerSearchInput.value = label;
          setSelectedState(label, customer.id);
        }
      });
    }
  })();
</script>
@endsection

