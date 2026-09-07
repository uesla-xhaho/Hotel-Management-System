@extends('layouts.guest')

@section('topbar-action')
<a href="{{ route('public-booking') }}" class="hm-pill hm-pill-ghost text-decoration-none">Guest booking</a>
@endsection

@section('content')
<div class="hm-hero-grid hm-fade-in" data-delay="1">
    <div class="hm-hero">
        <div class="hm-badge mb-3">Admin & Staff</div>
        <div class="hm-hero-title mb-2">Welcome back</div>
        <p class="hm-hero-subtitle">Sign in to manage rooms, bookings, and guest requests.</p>
        <div class="d-flex flex-wrap gap-2 mt-3">
            <span class="hm-feature">Secure access</span>
            <span class="hm-feature">Fast workflow</span>
            <span class="hm-feature">All bookings in one place</span>
        </div>
    </div>
    <div class="hm-card p-4 p-md-5">
        <div class="hm-section-title mb-3">Login</div>
        <form method="POST" action="{{ route('login') }}" class="row g-3">
            @csrf
            <div class="col-12">
                <label for="username" class="form-label hm-form-label">Username</label>
                <input id="username" type="text" class="form-control hm-input @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                @error('username')
                    <div class="hm-form-error mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label for="password" class="form-label hm-form-label">Password</label>
                <input id="password" type="password" class="form-control hm-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <div class="hm-form-error mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 d-flex flex-column flex-md-row align-items-md-center gap-2">
                <label class="hm-check">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
            </div>
            <div class="col-12">
                <button type="submit" class="hm-pill hm-pill-primary w-100">Login</button>
            </div>
        </form>
    </div>
</div>
@endsection
