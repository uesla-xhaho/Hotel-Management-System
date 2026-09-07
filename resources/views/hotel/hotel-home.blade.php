@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<style>
    @import url('https://fonts.googleapis.com/css2?family=Urbanist:wght@200;300;400;500;600;700&display=swap');

body{

    margin: 0;
    padding: 0;
    background-color: #fafafa;
    font-family: 'Urbanist', sans-serif;
}
.swiper-wrapper{

    margin-bottom: 80px;
    position: relative;
}

.swiper-wrapper .swiper-slide{

    display: flex;
    min-height:500px;
    min-width:350px;
    width:400px;
    height:500px;
    justify-content: center;
    align-items: center;
    position: relative;
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid #ece6db;
    box-shadow: 0 16px 30px rgba(28, 26, 34, 0.1);
    overflow: hidden;
    transition: transform .4s ease, box-shadow .4s ease;
}

.swiper-wrapper .swiper-slide:hover{
    transform: translateY(-6px);
    box-shadow: 0 24px 40px rgba(28, 26, 34, 0.18);
    
}


.swiper-wrapper .swiper-slide .main-img{
    height:240px;
    margin: 0;
    object-position: center;
    width: 100%;
    object-fit: cover;
    transition: all 1s ease;
}

.swiper-wrapper .swiper-slide:hover .main-img{
 transform: scale(1.05);
}

.swiper-slide .card-body{
    width: 100%;
    padding: 18px 20px 8px;
}

.swiper-slide .card-title{
    font-size: 1.2rem;
    letter-spacing: 0.02em;
    color: #1c1a22;
    margin-bottom: 10px;
}

.swiper-slide .card-text{
    color: #4f4f58;
    font-size: 0.95rem;
}

.swiper-slide .card-text strong{
    color: #1c1a22;
    font-weight: 700;
}

.swiper-slide .card-text .mb-2{
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.swiper-slide .card-footer{
    width: 100%;
    height: auto;
    padding: 12px 20px 16px;
    border-top: 1px solid #eee4d7;
    background: #fbf7f1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.swiper-slide .card-footer small{
    color: #6b6d76;
    font-weight: 600;
}

.swiper-slide .dropdown .btn{
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #e0d7cb;
    background: #fff;
    color: #1c1a22;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    transition: all .2s ease;
}

.swiper-slide .dropdown .btn:hover{
    background: #1c1d4f;
    color: #fff;
    border-color: #1c1d4f;
}

.swiper-pagination-bullet-active{
    background: #215deb;
}

#submit-btn:hover {
      transform: scale(1.08);
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
}
/* Dropdown */
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

/* Dropdown content */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px -8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 1;
  bottom:10px;
    right: 0; 
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

.dropdown:hover .dropdown-content,
.dropdown-content:hover {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}
.user-sub-title {
    color: #74788d;
    font-size: 11px;
    font-weight: 600;
}
.user-name {
    font-size: 14.4px;
    font-weight: 600;
    display: block;
    color: #495057;
    text-transform: uppercase;
}

</style>
<?php
        $tittle="My Hotels"
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
    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Hotels</button>
    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">New Hotel</button>
  </div>
</nav>
</div>

<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
    <section>

<div class="container">

    <!-- Slider main container -->
    <div class="swiper mySwiper">

        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
        @foreach($hotels as $hotel)
            <!-- Slides -->
            <div class="swiper-slide card">
            <div class="d-none" id="row-id">{{$hotel['id']}}</div>
                <img class="card-img-top main-img" id="card-img" src="./storage/images/{{ $hotel['image'] }}" alt="Card image cap">
                <div class="card-body">
                        <h4 class="card-title" style="font-weight:bold;"><span class="name">{{ $hotel['name'] }}</span></h4>    
                        <div class="card-text">
                            <div class="mb-2">
                                <strong>Address:</strong> <span class="address">{{ $hotel['address'] }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Phone:</strong><span class="phone"> {{ $hotel['phone'] }}</span>
                            </div>
                            <div  class="mb-2">
                                <strong>Email: </strong><span class="email">{{ $hotel['email'] }}</span> 
                            </div>
                            <div  class="mb-2">
                                <strong>Year of Opening: </strong><span class="year">{{ $hotel['year'] }}</span> 
                            </div>
                            </div>
                </div>            
                    <div class="card-footer">
                                <?php
                                $timestamp = strtotime($hotel['updated_at']); // Convert the string timestamp to an integer

                                if ($timestamp === false) {
                                    // Handle the case where the conversion fails (invalid timestamp)
                                    $formattedDate = 'Invalid Date';
                                } else {
                                    // Format the timestamp to display only the date, e.g., "YYYY-MM-DD"
                                    $formattedDate = date('Y-m-d', $timestamp);
                                }
                                ?>
                                <small class="text-muted">Last updated <?php echo $formattedDate; ?></small>
                                        <div class="dropdown" style="float:right">
                                            <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <div class="dropdown-content" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item edit-btn" href="#">Edit</a>
                                                <a class="dropdown-item delete-btn" href="#">Delete</a>
                                            </div>
                                        </div>
                            </div>    
            </div>
            @endforeach  
        
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
</section>

    </div>

        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="container" id="register-container">
                    <div class="row justify-content-center">

                            <form method="POST" action="{{ route('addhotel') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home" data-hm-reset="true">
                                @csrf

                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

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
                                    <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address">

                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>

                                    <div class="col-md-6">
                                        <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">

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
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="year" class="col-md-4 col-form-label text-md-end">{{ __('Year Of Opening:') }}</label>

                                    <div class="col-md-6">
                                        <input id="year" type="number" class="form-control @error('year') is-invalid @enderror" name="year" required autocomplete="year">

                                        @error('year')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="image" class="col-md-4 col-form-label text-md-end">{{ __('Add a photo:') }}</label>

                                    <div class="col-md-6">
                                        <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" autofocus>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                            


                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" id="submit-btn" name="upload" class="btn btn-primary">
                                            {{ __('Add Hotel') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
        </div>
</div>

   <!-- Edit Modal -->
   <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Hotel</h5>
        <button type="button" data-dismiss="modal" aria-label="Close" style="border:none; background-color:white;" onclick="customCloseFunction()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                        <form method="POST" action="{{ route('hotel-edit') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home" data-hm-close-modal="#editmodal">
                        @csrf
                        <input type="hidden" class="form-control" id="v_id" name="id" value="">
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-8">
                                <input id="v_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>

                            <div class="col-md-8">
                                <input id="v_address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="" required autocomplete="address">

                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>

                            <div class="col-md-8">
                                <input id="v_phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="" required autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                            <div class="col-md-8">
                                <input id="v_email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="year" class="col-md-4 col-form-label text-md-end">{{ __('Year Of Opening:') }}</label>

                            <div class="col-md-8">
                                <input id="v_year" type="number" class="form-control @error('year') is-invalid @enderror" name="year" value="" required autocomplete="year">

                                @error('year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="image" class="col-md-4 col-form-label text-md-end">{{ __('Add new photo:') }}</label>

                            <div class="col-md-8">
                                <input id="v_image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" value="" autofocus>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small>Choosing new image will replace the existing one.</small>
                            </div>
                        </div>

                        <div class="row mb-0">
                        <div class="modal-footer">
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
          <h5 class="modal-title" id="exampleModalLongTitle">Delete Hotel</h5>
          <button type="button" data-dismiss="modal" aria-label="Close" style="border:none; background-color:white;" onclick=" customCloseFunctionDelete()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">    
          <div class="container">
            <strong>Are you sure you want to delete this Hotel ?</strong> <br>
            <small>(Deleting this Hotel will delete all data including Rooms,Staff,Bookings and Customers related to this Hotel.)</small>
          </div>
          <div class="modal-footer border-0 pt-0 mt-2 justify-content-center">
            <form method="POST" action="{{ route('hotel-delete') }}" enctype="multipart/form-data" data-hm-refresh="#nav-home" data-hm-close-modal="#deletemodal" class="d-flex align-items-center gap-2">
              @csrf
              <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="customCloseFunctionDelete()">Close</button>
              <input type="hidden" class="form-control" id="d_id" name="id" value="">
              <button type="submit" class="btn btn-danger dltbtn">
                {{ __('Delete') }}
              </button>
            </form>
          </div>
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
  



<script>
function customCloseFunction() {
  $('#editmodal').modal('hide');
    }
function customCloseFunctionDelete() {
    $('#deletemodal').modal('hide');
}
  </script>

<script>
 $(document).on('click','.edit-btn',function(){
  $("#editmodal").modal("show");
  var _this = $(this).closest('.swiper-slide');// Change to select the parent div instead of tr
  var idString = _this.find('#row-id').text();
  var id = parseInt(idString);
    $('#v_id').val(id);
    $('#v_name').val(_this.find('.name').text());
    $('#v_phone').val(_this.find('.phone').text());
    $('#v_email').val(_this.find('.email').text());
    $('#v_address').val(_this.find('.address').text());
    $('#v_year').val(_this.find('.year').text());
})
</script>

<script>
$(document).on('click','.delete-btn',function(){
$("#deletemodal").modal("show");
var _this = $(this).closest('.swiper-slide');// Change to select the parent div instead of tr
var idString = _this.find('#row-id').text();
var id = parseInt(idString);
$('#d_id').val(id);
})
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
 <!-- Initialize Swiper -->
 <script>
window.hmInitSwiper = function () {
    if (window.hmSwiper) {
        window.hmSwiper.destroy(true, true);
    }

    window.hmSwiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        grabCursor: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            576: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 3,
            }
        }
    });
};

window.hmInitSwiper();
</script>
@endsection
