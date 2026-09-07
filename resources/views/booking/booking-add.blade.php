@extends('layouts.app')

@section('content')

<style>
/* External CSS file: styles.css */

/* Global reset and base styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Outfit', sans-serif;
  background: #f0f0f0;
}

.container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

#register-container {
  width: 90%;
  max-width: 750px;
  padding: 30px;
  border-radius: 20px;
  margin-left: auto;
  margin-right: auto;
  background: linear-gradient(180deg, #6089B9 38.02%, rgba(96, 137, 185, 0.34) 100%);
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

#customer_search,
.input-field {
  width: 100%;
  height: 40px;
  border-radius: 20px;
  background: #D9D9D9;
  padding: 10px;
  border: none;
  margin-top: 10px;
}

.input-field:focus,
.select-field:focus {
  outline: none;
}

.label {
  font-size: 18px;
  color: #FFF;
  margin-bottom: 10px;
  display: block;
  text-align: left;
}

.header_title {
  font-size: 28px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 20px;
}

.radio-label {
  font-size: 16px;
  color: #fff;
  margin-right: 20px;
}

.btn-primary {
  background-color: #2196F3;
  border: none;
  border-radius: 20px;
  padding: 10px 25px;
  font-size: 18px;
  font-weight: 600;
  color: #FFF;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: #1976D2;
}

.invalid-feedback {
  color: #FF0000;
  font-size: 14px;
  margin-top: 5px;
}

.customer-search-wrap {
  position: relative;
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
  color: #fff;
  font-size: 13px;
  text-align: left;
}

</style>

<div id="welcome"><h1 class="welcome-message">Bookings</h1></div>
<div class="container" id="register-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <div class="header_tittle">{{ __('Add Booking ') }}</div>

                    <form method="POST" action="{{ route('bookingadd') }}" enctype="multipart/form-data" data-hm-reset="true">
                        @csrf

                        <div class="row mb-3">
                            <label for="customer_search" class="col-md-4 col-form-label text-md-end">{{ __('Customer Name:') }}</label>
                            <div class="col-md-6">
                                <div class="customer-search-wrap">
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">
                                    <input
                                        type="text"
                                        id="customer_search"
                                        class="input-field @error('customer_id') is-invalid @enderror"
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
                                <input id="comment" type="text" class="form-control @error('comment') is-invalid @enderror" name="comment" value="{{ old('comment') }}"autocomplete="comment">

                            </div>
                        </div>


                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Booking') }}
                                </button>
                            </div>
                        </div>
                    </form>


                    @if ($errors->any())
                    <div class="w-4/8 m-auto text-center">
                        @foreach ($errors->all() as $error)
                        <li class="text-red-500 list-none">
                            {{ $error}}
                        </li>
                        @endforeach
                        @endif
                    </div>
        </div>
    </div>
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

        function showNoResults() {
            customerResults.innerHTML = '<div class="customer-option" style="cursor:default;">No customers found.</div>';
            customerResults.classList.remove('d-none');
        }

        function renderResults(items) {
            if (!items.length) {
                showNoResults();
                return;
            }

            var html = items.map(function (customer) {
                var label = getCustomerLabel(customer);
                var selectedClass = String(customer.id) === selectedCustomerId ? ' selected' : '';
                return '<button type="button" class="customer-option' + selectedClass + '" data-id="' + customer.id + '"><span class="customer-option-name">' + escapeHtml(label) + '</span><span class="customer-option-check">Selected</span></button>';
            }).join('');

            customerResults.innerHTML = html;
            customerResults.classList.remove('d-none');
        }

        function filterCustomers() {
            var query = customerSearchInput.value.trim().toLowerCase();

            customerIdInput.value = '';
            clearSelectedState();

            if (query === '') {
                hideResults();
                return;
            }

            var matches = customers.filter(function (customer) {
                var name = String(customer.name || '').toLowerCase();
                return name.indexOf(query) !== -1 ||
                    String(customer.id).indexOf(query) !== -1;
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
