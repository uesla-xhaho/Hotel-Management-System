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
  max-width: 650px;
  padding: 30px;
  border-radius: 20px;
  margin-left: auto;
  margin-right: auto;
  background: linear-gradient(180deg, #6089B9 38.02%, rgba(96, 137, 185, 0.34) 100%);
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

#select,
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


</style>

<div id="welcome"><h1 class="welcome-message">Customers</h1></div>
<div class="container" id="register-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <div class="header_tittle">{{ __('Add New Customer') }}</div>

                
                    <form method="POST" action="{{ route('customer-store') }}" enctype="multipart/form-data" data-hm-reset="true">
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
                            <label for="personal_id" class="col-md-4 col-form-label text-md-end">{{ __('Personal ID') }}</label>

                            <div class="col-md-6">
                                <input id="personal_id" type="text" class="form-control @error('personal_id') is-invalid @enderror" name="personal_id" value="{{ old('personal_id') }}"unique required autocomplete="personal_id">

                                @error('personal_id')
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
