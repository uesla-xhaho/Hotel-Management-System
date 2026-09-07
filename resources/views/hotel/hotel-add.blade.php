
<style>
*{
    overflow: auto;
}

#register-container{
    margin-top:100px;
    width: 750px;
    height: 950px;
    display: flex;
    align-items:center;
   
    flex-shrink: 0;
    border-radius: 50px;
    margin-left:250px;
    background: linear-gradient(180deg, #6089B9 38.02%, rgba(96, 137, 185, 0.34) 100%);
}
input{
    width: 400px;
    height: 40px;
    flex-shrink: 0;
    border-radius: 50px;
    background: #D9D9D9;
    padding: 5px;
    border: none;
    margin-left:10px;
}

label{
    font-size: 18px;
    font-family: 'Outfit', sans-serif;
    color:#FFF;
    margin-bottom:15px;
    margin-left:15px;
}

#submit-btn{
    width: 130px;
    height: 50px;
    flex-shrink: 0;
    border-radius: 50px;
    background: #63ACCB;
    flex-direction: column;
    color: #FFF;
    font-size: 18px;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
    margin-top:10px;
    margin-bottom:10px;
    margin-left:50px;
}
.header_tittle{
    height: 50px;
    color: #FFF;
    text-align: center;
    font-size: 40px;    
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
}
form{
    margin-top:120px;
}
#radiobtn{
}

</style>

<div id="welcome"><h1 class="welcome-message">Rooms</h1></div>
<div class="container" id="register-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="header_tittle">{{ __('Add Hotel') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('addhotel') }}" enctype="multipart/form-data" data-hm-reset="true">
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
                                <button type="submit" id="submit-btn" class="btn btn-primary">
                                    {{ __('Add +') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
