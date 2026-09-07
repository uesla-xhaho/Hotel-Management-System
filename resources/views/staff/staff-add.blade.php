@extends('layouts.app')

@section('content')

<style>
/* Improved styling for main container */
#register-container {
  width: 100%;
  max-width: 650px;
  margin: 100px auto;
  padding: 20px;
  border-radius: 50px;
  background: linear-gradient(180deg, #6089B9 38.02%, rgba(96, 137, 185, 0.34) 100%);
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

/* Improved styling for form elements */
input[type="text"],
input[type="email"],
input[type="date"],
select {
  width: 100%;
  height: 40px;
  border-radius: 50px;
  background: #D9D9D9;
  padding: 5px 15px;
  border: none;
  margin-bottom: 10px;
}

/* Improved styling for labels */
label {
  font-size: 18px;
  font-family: 'Outfit', sans-serif;
  color: #FFF;
  margin-bottom: 5px;
}

/* Improved styling for radio buttons */
.radio-group {
  display: flex;
  gap: 10px;
  margin-bottom: 10px;
}

/* Improved styling for submit button */
.btn-primary {
  display: block;
  width: 100%;
}

/* Improved styling for error messages */
.invalid-feedback {
  color: #FF0000;
  font-size: 14px;
  margin-top: 5px;
}

/* Responsive adjustments for small screens */
@media (max-width: 768px) {
  #register-container {
    max-width: 100%;
    margin: 50px auto;
    padding: 10px;
  }
}

#welcome{
    margin-left: auto;
    margin-right: auto;
}


</style>

<div id="welcome"><h1 class="welcome-message">Staff</h1></div>

<div class="container" id="register-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <div class="header_tittle">{{ __('Add Staff Member') }}</div>

                    <form method="POST" action="{{ route('staffadd') }}" enctype="multipart/form-data" data-hm-reset="true">
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
                            <select name="hotel_id" id="select">
                            @foreach($hotels as $row)
                                <option value="{{ $row['id'] }}">{{ $row['name']}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
@endsection
