@extends('layouts.app')

@section('content')



<style>

  .divtable{
    display: flex;
  justify-content: center;
  align-items: center;
}


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
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
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

.payment-actions {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.payment-delete-x {
  width: 30px;
  height: 30px;
  padding: 0;
  line-height: 1;
  border-radius: 999px;
  font-weight: 700;
  font-size: 16px;
}

</style>
<?php
        $tittle="Payment"
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
    <button class="nav-link active" id="nav-default-tab" data-bs-toggle="tab" data-bs-target="#nav-default" type="button" role="tab" aria-controls="nav-home" aria-selected="true">All Payments</button>
    <!--<button class="nav-link" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Placeholder</button>-->
  </div>
</nav>
  </div>
  <div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-default" role="tabpanel" aria-labelledby="nav-home-tab">

      <main class="content">
            @component('layouts.data-table', ['id' => 'myTable', 'order' => '[[0,"desc"]]'])
            <thead>
                  <tr>
                    <th>Booking ID</th> 
                    <th>Customer Name</th>
                    <th>Room Number</th>
                    <th>Number of days</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="no-sort">Complete</th>
                    <th class="text-center no-sort">Action</th>
                    <th class="d-none"></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($payment as $row)
                  <tr>
                      <td>{{ $row['booking_id'] }}</td>
                      <td>{{ $row->customer->name }}</td>
                      <td>{{ optional(optional($row->booking)->room)->room_number ?? 'Unassigned' }}</td>
                      <td>{{ $row['duration'] }}</td>
                      <td>{{ $row['price'] }}</td>
                      <td>{{ $row['status'] }}</td>
                      <td class="text-center">
                      <a type="submit" name="input" class="deletebtn"> <i class="fa fa-edit" style="color: #2196f3;"></i></a>            
                      </td>
                      <td>
                          <div class="payment-actions">
                              <a type="button" class="btn btn-primary" href="{{ route('invoice', $id = $row['id']) }}">
                                  Generate Invoice
                              </a>
                              <button
                                  type="button"
                                  class="btn btn-danger payment-delete-x js-payment-delete-btn"
                                  data-payment-id="{{ $row['id'] }}"
                                  title="Delete payment"
                                  aria-label="Delete payment"
                              >&times;</button>
                          </div>
                      </td>
                      <td class="d-none" id="row-id">{{$row['id']}}</td>
                  </tr>
                  @endforeach
                  </tbody>
                @endcomponent
              </main>
            </div>
          </div>
       </div>





 <!-- Delete Modal -->
 <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Payment</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" style="border:none; background-color:white;" onclick=" customCloseFunctionDelete()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="{{ route('payment-complete') }}" enctype="multipart/form-data" data-hm-refresh="#nav-default" data-hm-close-modal="#deletemodal">
                        @csrf    
                        <input type="hidden" class="form-control" id="v_id" name="id" value="">
<div class="my-3">
  <div class="form-check">
    <input id="credit" name="paymentMethod" type="radio" value="credit" class="form-check-input" checked required>
    <label class="form-check-label" for="credit">Credit card</label>
  </div>
  <div class="form-check">
    <input id="debit" name="paymentMethod" type="radio" value="debit" class="form-check-input" required>
    <label class="form-check-label" for="debit">Debit card</label>
  </div>
  <div class="form-check">
    <input id="paypal" name="paymentMethod" type="radio" value="paypal" class="form-check-input" required>
    <label class="form-check-label" for="paypal">PayPal</label>
  </div>
  <div class="form-check">
    <input id="cash" name="paymentMethod" type="radio" value="cash" class="form-check-input" required>
    <label class="form-check-label" for="cash">Cash</label>
  </div>
</div>

<div class="row gy-3 js-card-fields">
  <div class="col-md-6">
    <label for="cc-name" class="form-label">Name on card</label>
    <input type="text" class="form-control js-card-input" id="cc-name" placeholder="" required>
    <small class="text-muted">Full name as displayed on card</small>
    <div class="invalid-feedback">
      Name on card is required
    </div>
  </div>

  <div class="col-md-6">
    <label for="cc-number" class="form-label">Credit card number</label>
    <input type="text" class="form-control js-card-input" id="cc-number" placeholder="" required>
    <div class="invalid-feedback">
      Credit card number is required
    </div>
  </div>

  <div class="col-md-3">
    <label for="cc-expiration" class="form-label">Expiration</label>
    <input type="text" class="form-control js-card-input" id="cc-expiration" placeholder="" required>
    <div class="invalid-feedback">
      Expiration date required
    </div>
  </div>

  <div class="col-md-3">
    <label for="cc-cvv" class="form-label">CVV</label>
    <input type="text" class="form-control js-card-input" id="cc-cvv" placeholder="" required>
    <div class="invalid-feedback">
      Security code required
    </div>
  </div>
</div>
<div class="js-card-logos">
  <img src="./storage/images/-11597193683crlwwyqxmq.png" alt="img" style="max-width:300px; margin-top:10px;">
  <hr class="my-4">
</div>

<button class="w-100 btn btn-primary btn-lg" type="submit">Complete</button>
</form>
</div>
</div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Delete Payment Modal -->
<div class="modal fade" id="payment-delete-modal" tabindex="-1" role="dialog" aria-labelledby="paymentDeleteModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentDeleteModalTitle">Delete Payment</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" style="border:none; background-color:white;" onclick="customCloseFunctionPaymentDelete()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          Are you sure you want to delete this payment?
        </div>
        <div class="modal-footer border-0 pt-0 mt-2 justify-content-center">
          <form method="POST" action="{{ route('payment-delete') }}" enctype="multipart/form-data" data-hm-refresh="#nav-default" data-hm-close-modal="#payment-delete-modal" class="d-flex align-items-center gap-2">
            @csrf
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="customCloseFunctionPaymentDelete()">Close</button>
            <input type="hidden" class="form-control" id="payment_delete_id" name="id" value="">
            <button type="submit" class="btn btn-danger">
              {{ __('Delete') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
function customCloseFunction() {
  $('#editmodal').modal('hide');
    }
  function customCloseFunctionDelete() {
    $('#deletemodal').modal('hide');
    }
  function customCloseFunctionPaymentDelete() {
    $('#payment-delete-modal').modal('hide');
  }
    
  </script>


<script>
$(document).on('click','.deletebtn',function(){
  $('#deletemodal').modal('show');
  var _this=$(this).parents('tr');
  var idString = _this.find('#row-id').text();
  var id = parseInt(idString);

  $('#v_id').val(id);

})

$(document).on('click', '.js-payment-delete-btn', function () {
  $('#payment_delete_id').val($(this).data('payment-id'));
  $('#payment-delete-modal').modal('show');
});
  </script>

<script>
  (function () {
    function initPaymentMethodBehavior(modalId) {
      var modal = document.querySelector(modalId);
      if (!modal) {
        return;
      }

      var radios = modal.querySelectorAll('input[name="paymentMethod"]');
      var cardSections = modal.querySelectorAll('.js-card-fields, .js-card-logos');
      var cardInputs = modal.querySelectorAll('.js-card-input');

      function updateCardFields() {
        var selectedMethod = modal.querySelector('input[name="paymentMethod"]:checked');
        var isCash = selectedMethod && selectedMethod.value === 'cash';

        cardSections.forEach(function (section) {
          section.classList.toggle('d-none', isCash);
        });

        cardInputs.forEach(function (input) {
          input.required = !isCash;
          if (isCash) {
            input.value = '';
          }
        });
      }

      radios.forEach(function (radio) {
        radio.addEventListener('change', updateCardFields);
      });

      $(modalId).on('shown.bs.modal', updateCardFields);
      updateCardFields();
    }

    document.addEventListener('DOMContentLoaded', function () {
      initPaymentMethodBehavior('#deletemodal');
    });
  })();
</script>


@endsection

