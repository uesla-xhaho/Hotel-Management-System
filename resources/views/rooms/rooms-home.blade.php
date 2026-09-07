@extends('layouts.app')

@section('content')
<style>
    
.divtable {
  display: flex;
  justify-content: center;
  align-items: center;
}

table {
  margin: 90px 0 0 150px;
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
        $tittle="Rooms";
        $showNewTab = $errors->any();
?>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the errors below and try again.</strong>
        <ul style="margin-bottom:0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
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
    <button class="nav-link {{ $showNewTab ? '' : 'active' }}" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="{{ $showNewTab ? 'false' : 'true' }}">All Rooms</button>
    <button class="nav-link {{ $showNewTab ? 'active' : '' }}" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="{{ $showNewTab ? 'true' : 'false' }}">New Room</button>
  </div>
</nav>
</div>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show {{ $showNewTab ? '' : 'active' }}" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

            <main class="content">
            @component('layouts.data-table', ['id' => 'myTable'])
            <thead>      
            <tr> 
                    <th  class="text-center">Room Number</th> 
                    <th class="text-center">Category</th>
                    <th  class="text-center">Capacity</th>
                    <th  class="text-center ">Price</th>
                    <th class="text-center">Number of beds</th>
                    <th class="text-center ">Air Condition</th>
                    <th class="text-center ">Balcony</th>
                    <th class="text-center">Status</th>
                    <th class="text-center ">Description</th>
                    <th class="text-center no-sort">Action</th>    
                    <th class="d-none"></th>
                    <th class="d-none"></th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($results as $row)
                    <tr data-room-number="{{ $row['room_number'] ?? '' }}" data-room-floor="{{ $row['room_floor'] }}" data-room-position="{{ $row['room_position'] }}">
                      <td class="room-number">{{ $row['room_number'] ?? 'Unassigned' }}</td>
                      <td class="category">{{ $row['category'] }}</td>
                      <td class="capacity">{{ $row['capacity'] }}</td>
                      <td class="price">{{ $row['price'] }}</td>
                      <td class="nrofbeds">{{ $row['nrofbeds'] }}</td>
                      <td class="aircondition">{{ ((int) $row['aircondition']) === 1 ? 'Yes' : 'No' }}</td>
                      <td class="balcony">{{ $row['balcony'] }}</td>
                      <td class="">{{ $row['status'] }}</td>
                      <td class="description">{{ $row['description'] }}</td>    
                        
                      <td> 
                      <div class="d-flex justify-content-center align-items-center" style="min-width:170px;">   
                      <button type="button" class="btn btn-primary editbtn mr-2"  data-toggle="modal" style=" margin-right: 5%;" data-target="#editmodal">
                      <i class="fa-regular fa-pen-to-square" style="margin-right:3px;"></i>Edit</button>
                     <button type="submit" name="input" class="btn btn-danger deletebtn"><i class="fa-solid fa-trash-can" style="margin-right:3px;"></i>Delete</button>  
                         </div>
                      </td>
                      <td class="d-none" id="row-id">{{$row['id']}}</td>
                      <td class="d-none" id="hotel_id">{{$row['hotel_id']}}</td>
                    </tr>
                  @endforeach
                  </tbody>
                @endcomponent
                </main>
            </div>
  <div class="tab-pane fade {{ $showNewTab ? 'show active' : '' }}" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
          <div class="container" id="register-container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                        <form method="POST" action="{{ route('add') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home" data-hm-reset="true">
                        @csrf

                     <div class="row mb-3">
                        <label for="hotel_id" class="col-md-4 col-form-label text-md-end">{{ __('Hotel:') }}</label>
                        <div class="col-md-8">
                        <select name="hotel_id" id="select" class="form-select">
                                    @foreach($hotels as $row)
                                        <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                                    @endforeach
                    </select>
            </div>
        </div>


                        <div class="row mb-3">
                            <label for="room_floor" class="col-md-4 col-form-label text-md-end">{{ __('Floor (1-3)') }}</label>

                            <div class="col-md-8">
                                <input id="room_floor" type="number" class="form-control @error('room_floor') is-invalid @enderror" name="room_floor" value="{{ old('room_floor') }}" min="1" max="3" required autocomplete="off" autofocus>

                                @error('room_floor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="room_position" class="col-md-4 col-form-label text-md-end">{{ __('Room Position (1-4)') }}</label>

                            <div class="col-md-8">
                                <input id="room_position" type="number" class="form-control @error('room_position') is-invalid @enderror" name="room_position" value="{{ old('room_position') }}" min="1" max="4" required autocomplete="off">

                                @error('room_position')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="category" class="col-md-4 col-form-label text-md-end">{{ __('Category') }}</label>

                            <div class="col-md-8">
                                <select name="category" id="select"  class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="Single">Single</option>
                                    <option value="Double">Double</option>
                                    <option value="Twin">Twin</option>
                                    <option value="Quad">Quad</option>
                                    <option value="Suite">Suite</option>
                                    <option value="Villa">Villa</option>
                                </select>
                                    @error('category')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label for="capacity" class="col-md-4 col-form-label text-md-end">{{ __('Capacity') }}</label>

                            <div class="col-md-8">
                                <input id="capacity" type="number" class="form-control @error('capacity') is-invalid @enderror" name="capacity" value="{{ old('capacity') }}" required autocomplete="capacity">

                                @error('capacity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Price') }}</label>

                            <div class="col-md-8">
                                <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" required autocomplete="price">

                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nrofbeds" class="col-md-4 col-form-label text-md-end">{{ __('Number Of Beds:') }}</label>

                            <div class="col-md-8">
                                <input id="nrofbeds" type="number" class="form-control @error('nrofbeds') is-invalid @enderror" name="nrofbeds" required autocomplete="nrofbeds">

                                @error('nrofbeds')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                             <label for="aircondition" class="col-md-4 col-form-label text-md-end">{{ __('Air Condition:') }}</label>

                            <div class="col-md-8" >
                                <input type="radio" id="radiobtn" name="aircondition" value="Yes" checked><label for="">Yes</label>
                                <input type="radio" id="radiobtn" name="aircondition" value="No"><label for="">No</label>

                                @error('aircondition')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label for="balcony" class="col-md-4 col-form-label text-md-end">{{ __('Number of Balconies:') }}</label>

                            <div class="col-md-8">
                                <input id="balcony" type="number" class="form-control @error('balcony') is-invalid @enderror" name="balcony" required autocomplete="balcony">

                                @error('balcony')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                            <div class="col-md-8">
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" required autocomplete="description">

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="image" class="col-md-4 col-form-label text-md-end">{{ __('Add a photo:') }}</label>

                            <div class="col-md-8">
                                <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" autofocus autocomplete="image">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" id='submit-btn'>
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
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Room</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" style="border:none; background-color:white;" onclick="customCloseFunction()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="POST" action="{{ route('room-edit') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home" data-hm-close-modal="#editmodal">
                        @csrf
                
                        <input type="hidden" class="form-control" id="v_id" name="id" value="">
                        <div class="row mb-3">
                        <label for="hotel_id" class="col-md-4 col-form-label text-md-end">{{ __('Hotel:') }}</label>
                        <div class="col-md-8">
                            <select name="hotel_id" id="v_hotel_id" class="form-select" value="">
                                @foreach($hotels as $row)
                                    <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                        <div class="row mb-3">
                            <label for="v_room_floor" class="col-md-4 col-form-label text-md-end">{{ __('Floor (1-3)') }}</label>

                            <div class="col-md-8">
                                <input id="v_room_floor" type="number" class="form-control @error('room_floor') is-invalid @enderror" name="room_floor" value="" min="1" max="3" required autocomplete="off" autofocus>

                                @error('room_floor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="v_room_position" class="col-md-4 col-form-label text-md-end">{{ __('Room Position (1-4)') }}</label>

                            <div class="col-md-8">
                                <input id="v_room_position" type="number" class="form-control @error('room_position') is-invalid @enderror" name="room_position" value="" min="1" max="4" required autocomplete="off">

                                @error('room_position')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="category" class="col-md-4 col-form-label text-md-end">{{ __('Category') }}</label>

                            <div class="col-md-8">
                                <select name="category" id="v_category"  class="form-select @error('category') is-invalid @enderror"  value="" required>
                                    <option value="Single">Single</option>
                                    <option value="Double">Double</option>
                                    <option value="Twin">Twin</option>
                                    <option value="Quad">Quad</option>
                                    <option value="Suite">Suite</option>
                                    <option value="Villa">Villa</option>
                                </select>
                                    @error('category')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                        </div>

                        <div class="row mb-3">
                            <label for="capacity" class="col-md-4 col-form-label text-md-end">{{ __('Capacity') }}</label>

                            <div class="col-md-8">
                                <input id="v_capacity" type="number" class="form-control @error('capacity') is-invalid @enderror" name="capacity" value="" required autocomplete="capacity">

                                @error('capacity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Price') }}</label>

                            <div class="col-md-8">
                                <input id="v_price" type="number" class="form-control @error('price') is-invalid @enderror" name="price"  value="" required autocomplete="price">

                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nrofbeds" class="col-md-4 col-form-label text-md-end">{{ __('Number Of Beds:') }}</label>

                            <div class="col-md-8">
                                <input id="v_nrofbeds" type="number" class="form-control @error('nrofbeds') is-invalid @enderror" name="nrofbeds" value="" required autocomplete="nrofbeds">

                                @error('nrofbeds')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                             <label for="aircondition" class="col-md-4 col-form-label text-md-end">{{ __('Air Condition:') }}</label>

                            <div class="col-md-8" >
                                <input type="radio" id="radio-yes" name="aircondition" value="Yes"><label for="">Yes</label>
                                <input type="radio" id="radio-no" name="aircondition" value="No"><label for="">No</label>

                                @error('aircondition')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label for="balcony" class="col-md-4 col-form-label text-md-end">{{ __('Number of Balconies:') }}</label>

                            <div class="col-md-8">
                                <input id="v_balcony" type="number" class="form-control @error('balcony') is-invalid @enderror" name="balcony" value="" required autocomplete="balcony">

                                @error('balcony')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                            <div class="col-md-8">
                                <input id="v_description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="" required autocomplete="description">

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="v_image" class="col-md-4 col-form-label text-md-end">{{ __('Add new photo:') }}</label>

                            <div class="col-md-8">
                                <input id="v_image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" autofocus>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small>Choosing new image will replace the existing one.</small>
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
        <h5 class="modal-title" id="exampleModalLongTitle">Delete Room</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" style="border:none; background-color:white;" onclick=" customCloseFunctionDelete()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">    
        <div class="container">
          Are you sure you want to delete ?
         
        </div>           
        <div class="modal-footer border-0 pt-0 mt-2 justify-content-center">
          <form method="POST" action="{{ route('deleterooms') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home" data-hm-close-modal="#deletemodal" class="d-flex align-items-center gap-2">
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
    var ac= _this.find('.aircondition').text();
    $('#v_id').val(id);
    $('#v_hotel_id').val(_this.find('#hotel_id').text());
    $('#v_room_floor').val(_this.data('room-floor'));
    $('#v_room_position').val(_this.data('room-position'));
    $('#v_category').val(_this.find('.category').text());
    $('#v_capacity').val(_this.find('.capacity').text());
    $('#v_price').val(_this.find('.price').text());
    $('#v_nrofbeds').val(_this.find('.nrofbeds').text());
    $('#v_balcony').val(_this.find('.balcony').text());
    $('#v_description').val(_this.find('.description').text());
    if (ac === 'Yes') {
        $('#radio-yes').prop('checked', true);
    }
    else if (ac === 'No') {
        $('#radio-no').prop('checked', true);
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
