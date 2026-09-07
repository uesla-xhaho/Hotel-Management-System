@extends('layouts.app')

@section('content')

<style>

/* table style */

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
  text-align: center;
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
#submit-btn:hover {
      transform: scale(1.08);
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
 }

.staff-table-container {
  max-width: none;
  width: 100%;
}

@media (min-width: 1200px) {
  .staff-table-container {
    width: calc(100% + 56px);
    margin-right: -56px;
  }
}

.staff-table .phone-col,
.staff-table td.phone {
  white-space: nowrap;
  min-width: 150px;
}

.staff-table .action-col,
.staff-table td.action-cell {
  min-width: 230px;
  white-space: nowrap;
}

.staff-table .action-buttons {
  min-width: 210px;
  gap: 8px;
  flex-wrap: nowrap;
}

.staff-table .action-buttons .btn {
  white-space: nowrap;
}
</style>
<?php
        $tittle="Staff"
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
    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">All Staff</button>
    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">New Staff</button>
  </div>
</nav>
</div>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
<main class="content">
  <div class="container-fluid staff-table-container px-0">
  @component('layouts.data-table', ['id' => 'myTable', 'class' => 'staff-table', 'responsive' => false, 'scrollX' => true])
        <thead>
        <tr>
            <th class="text-center">Name</th> 
            <th class="text-center">Gender</th>
            <th class="text-center">Birthday</th>
            <th class="text-center phone-col">Phone Number</th>
            <th class="text-center">Email</th>
            <th class="text-center">Address</th>
            <th class="text-center">Role</th>
            <th class="text-center">Username</th>
            <th class="text-center no-sort action-col">Action</th>
            <th class="d-none"></th>
            <th class="d-none"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $row)
          <tr>
            <td class="name">{{ $row['name'] }}</td>
            <td class="gender">{{ $row['gender'] }}</td>
            <td class="birthdate">{{ $row['birthdate'] }}</td>
            <td class="phone">{{ $row['phone'] }}</td>
            <td class="email">{{ $row['email'] }}</td>
            <td class="address">{{ $row['address'] }}</td>
            <td class="role">{{ $row['role'] }}</td>
            <td class="text-center">{{ $row['username'] ?? 'NULL' }}</td>      
            <td class="action-cell"> 
                <div class="d-flex justify-content-center align-items-center action-buttons">   
                    <button type="button" class="btn btn-primary editbtn mr-2" data-toggle="modal" data-target="#editmodal">
                    <i class="fa-regular fa-pen-to-square" style="margin-right:3px;"></i>Edit</button>
                    <button type="submit" name="input" class="btn btn-danger deletebtn"  data-toggle="modal" data-target="#deletemodal"><i class="fa-solid fa-trash-can" style="margin-right:3px;"></i>Delete</button>  
                </div>
            </td>
            <td class="d-none" id="row-id">{{$row['id']}}</td>
            <td class="d-none" id="hotel_id">{{$row['hotel_id']}}</td>
          </tr>
        @endforeach    
        </tbody>
    @endcomponent
  </div>
</main>
</div>


  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
  <div class="container" id="register-container">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <form method="POST" action="{{ route('staffadd') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home" data-hm-reset="true">
                        @csrf
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Full Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="birthdate" class="col-md-4 col-form-label text-md-end">{{ __('Birthday') }}</label>

                            <div class="col-md-6">
                                <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate') }}" required autocomplete="birthdate">

                                @error('birthdate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                             <label for="gender" class="col-md-4 col-form-label text-md-end">{{ __('Gender:') }}</label>

                            <div class="col-md-6" >
                                <input type="radio" id="radiobtn" name="gender" value="Male" checked><label for="">Male</label>
                                <input type="radio" id="radiobtn" name="gender" value="Female"><label for="">Female</label>

                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>        

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number:') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" required autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"unique required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                         
                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}"unique required autocomplete="address">

                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Role:') }}</label>

                            <div class="col-md-6">
                            <input id="role" type="text" class="form-control @error('role') is-invalid @enderror" name="role" value="{{ old('role') }}"unique required autocomplete="role">

                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="hotel_id" class="col-md-4 col-form-label text-md-end">{{ __('Hotel:') }}</label>

                            <div class="col-md-6">
                            <select name="hotel_id" class="form-select" id="select">
                            @foreach($hotels as $row)
                                <option value="{{ $row['id'] }}">{{ $row['name']}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" id='submit-btn' class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>


                    </div>
                </div>
      </div>
    </div>
  </div>

   <!-- Edit Modal -->
  <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Room</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" style="border:none; background-color:white;" onclick="customCloseFunction()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="{{ route('staff-edit') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home" data-hm-close-modal="#editmodal">
                        @csrf

                        <input type="hidden" class="form-control" id="v_id" name="id" value="">
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Full Name') }}</label>

                            <div class="col-md-6">
                                <input id="v_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Role:') }}</label>

                            <div class="col-md-6">
                            <input id="v_role" type="text" class="form-control @error('role') is-invalid @enderror" name="role" value=""unique required disabled autocomplete="role">
                            <small id="passwordHelpBlock" class="form-text text-muted">
                               You cannot edit the role of this staff
                            </small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="v_phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="" required autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="v_email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="" unique required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <input id="v_address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="" unique required autocomplete="address">

                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="birthdate" class="col-md-4 col-form-label text-md-end">{{ __('Birthday') }}</label>

                            <div class="col-md-6">
                                <input id="v_birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="" required autocomplete="birthdate">

                                @error('birthdate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                             <label for="gender" class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>

                            <div class="col-md-6" >
                                <input type="radio" id="m-radio" name="gender" value="Male"><label for="">Male</label>
                                <input type="radio" id="f-radio" name="gender" value="Female"><label for="">Female</label>

                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>       

                        <div class="row mb-3">
                            <label for="hotel_id" class="col-md-4 col-form-label text-md-end">{{ __('Hotel') }}</label>

                            <div class="col-md-6">
                            <select name="hotel_id" class="form-select" id="v_hotel_id" value="">
                            @foreach($hotels as $row)
                                <option value="{{ $row['id'] }}">{{ $row['name']}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
  
                        <div class="row mb-0">
                        <div class="modal-footer border-0 pt-0">
                            <div class="col-md-6 offset-md-4">
                              <button type="button"  class="btn btn-secondary" data-dismiss="modal" onclick="customCloseFunction()">Close</button>
                              <button type="submit" class="btn btn-primary">
                                    {{ __('Save Changes') }}
                              </button>
                            </div>
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
</div>
</div>


 <!-- Delete Modal -->
 <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Delete Customer</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" style="border:none; background-color:white;" onclick=" customCloseFunctionDelete()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">    
        <div class="container">
          Are you sure you want to delete ?
        </div>           
        <div class="modal-footer border-0 pt-0 mt-2 justify-content-center">
          <form method="POST" action="{{ route('staff-delete') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home" data-hm-close-modal="#deletemodal" class="d-flex align-items-center gap-2">
            @csrf
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="customCloseFunctionDelete()">Close</button>
            <input type="hidden" class="form-control" id="d_id" name="id" value="">
            <input type="hidden" class="form-control" id="d_role" name="role" value="">
            <button type="submit" class="btn btn-danger dltbtn">
              {{ __('Delete') }}
            </button>
          </form>
        </div>
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
  </script>

<script>
     $(document).on('click','.editbtn',function(){
    $('#editmodal').modal('show');
    var _this=$(this).parents('tr');
    var idString = _this.find('#row-id').text();
    var id = parseInt(idString); 
    var gender= _this.find('.gender').text();
    
    $('#v_id').val(id);
    $('#v_hotel_id').val(_this.find('#hotel_id').text());
    $('#v_name').val(_this.find('.name').text());
    $('#v_phone').val(_this.find('.phone').text());
    $('#v_email').val(_this.find('.email').text());
    $('#v_address').val(_this.find('.address').text());
    $('#v_birthdate').val(_this.find('.birthdate').text());
    $('#v_role').val(_this.find('.role').text());
    if (gender === 'Male') {
        $('#m-radio').prop('checked', true);
    }
    else if (gender === 'Female') {
        $('#f-radio').prop('checked', true);
  }
})
</script>

<script>
$(document).on('click','.deletebtn',function(){
  $('#deletemodal').modal('show');
  var _this=$(this).parents('tr');
  var idString = _this.find('#row-id').text();
  var id = parseInt(idString);
  $('#d_id').val(id);
  $('#d_role').val(_this.find('.role').text());
})
</script>



  @endsection
