@extends('layouts.app')

@section('content')


<style>

  .divtable{
    display: flex;
  justify-content: center;
  align-items: center;
}

table{
  margin-top:90px;
  margin-left:150px;
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
  padding: 12px;
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

.row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}
#submit-btn:hover {
      transform: scale(1.08);
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    }

</style>
<?php
        $tittle="Customers"
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
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" id="myAlert">
        {{ $errors->first() }}
        <span class="close-alert" aria-hidden="true" style="float:right;" onclick="closeAlert()">&times;</span>
    </div>
@endif
<div id="tab-position">
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-default-tab" data-bs-toggle="tab" data-bs-target="#nav-default" type="button" role="tab" aria-controls="nav-home" aria-selected="true">All Customers</button>
    <button class="nav-link" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">New Customer</button>
  </div>
</nav>
  </div>
  <div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-default" role="tabpanel" aria-labelledby="nav-home-tab">

      <main class="content">
        @component('layouts.data-table', ['id' => 'myTable'])
        <thead>
          <tr>
          <th  class="text-center">Name</th> 
          <th  class="text-center">Personal ID</th>
          <th  class="text-center">Birthdate</th>
          <th  class="text-center">Phone Number</th>
          <th  class="text-center">Gender</th>
          <th class="text-center no-sort">Actions</th>
          <th class="d-none"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $row)
          <tr>
            <td  class="name">{{ $row['name'] }}</td>
            <td class="personal_id">{{ $row['personal_id'] }}</td>
            <td class="birthdate">{{ $row['birthdate'] }}</td>
            <td class="phone">{{ $row['phone'] }}</td>
            <td class="gender">{{ $row['gender'] }}</td>
            <td>     
            <div class="d-flex justify-content-center align-items-center">  
            <button type="button" class="btn btn-primary editbtn"  data-toggle="modal" data-target="#editmodal" data-username="{{ $row['id'] }}"
               style=" margin-right: 5%;"><i class="fa-regular fa-pen-to-square" style="margin-right:3px;"></i>Edit</button>
            <button type="submit" name="input" class="btn btn-danger deletebtn"><i class="fa-solid fa-trash-can" style="margin-right:3px;"></i>Delete</button>            
                </div>
          </td>
          <td class="d-none" id="row-id">{{$row['id']}}</td>
          </tr>
        @endforeach
        </tbody>
      @endcomponent
      </main>
      </div>
    <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
    <div class="container" id="register-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <form method="POST" action="{{ route('customer-store') }}" enctype="multipart/form-data" data-hm-refresh="#nav-default" data-hm-reset="true">
                        @csrf
                        
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Full Name:') }}</label>

                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="personal_id" class="col-md-4 col-form-label text-md-end">{{ __('Personal ID:') }}</label>

                            <div class="col-md-8">
                                <input id="personal_id" type="text" class="form-control @error('personal_id') is-invalid @enderror" name="personal_id" value="{{ old('personal_id') }}"unique required autocomplete="personal_id">

                                @error('personal_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="birthdate" class="col-md-4 col-form-label text-md-end">{{ __('Birthday:') }}</label>

                            <div class="col-md-8">
                                <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate') }}" required autocomplete="birthdate">

                                @error('birthdate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number:') }}</label>

                            <div class="col-md-8">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" required autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                             <label for="gender" class="col-md-4 col-form-label text-md-end">{{ __('Gender:') }}</label>

                            <div class="col-md-8" >
                                <input type="radio" id="radiobtn" name="gender" value="Male" checked><label for="">Male</label>
                                <input type="radio" id="radiobtn" name="gender" value="Female"><label for="">Female</label>

                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Customer</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" style="border:none; background-color:white;" onclick="customCloseFunction()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="{{ route('customer-edit') }}" enctype="multipart/form-data" data-hm-refresh="#nav-default" data-hm-close-modal="#editmodal">
                        @csrf
                        
                        <input type="hidden" class="form-control" id="v_id" name="id" value="">
                        
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Full Name:') }}</label>

                            <div class="col-md-6">
                                <input id="v_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" required autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="personal_id" class="col-md-4 col-form-label text-md-end">{{ __('Personal ID:') }}</label>

                            <div class="col-md-6">
                                <input id="v_personal_id" type="text" class="form-control @error('personal_id') is-invalid @enderror" name="personal_id" value=""unique required autocomplete="personal_id">

                                @error('personal_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="birthdate" class="col-md-4 col-form-label text-md-end">{{ __('Birthday:') }}</label>

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
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number:') }}</label>

                            <div class="col-md-6">
                                <input id="v_phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" required autocomplete="phone" value="">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                             <label for="gender" class="col-md-4 col-form-label text-md-end">{{ __('Gender:') }}</label>

                            <div class="col-md-6" >
                                <input type="radio" id="m_radiobtn" name="gender" value="Male"><label for="">Male</label>
                                <input type="radio" id="f_radiobtn" name="gender" value="Female"><label for="">Female</label>

                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
          <form method="POST" action="{{ route('customer-delete') }}" enctype="multipart/form-data" data-hm-refresh="#nav-default" data-hm-close-modal="#deletemodal" class="d-flex align-items-center gap-2">
            @csrf
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="customCloseFunctionDelete()">Close</button>
            <input type="hidden" class="form-control" id="d_id" name="id" value="">
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

  $('#v_id').val(id);
  $('#v_name').val(_this.find('.name').text());
  $('#v_personal_id').val(_this.find('.personal_id').text());
  $('#v_birthdate').val(_this.find('.birthdate').text());
  $('#v_phone').val(_this.find('.phone').text());
  var gender = _this.find('.gender').text();
  if (gender === 'Male') {
    $('#m_radiobtn').prop('checked', true);
  }
   else if (gender === 'Female') {
    $('#f_radiobtn').prop('checked', true);
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
})
</script>
@endsection

