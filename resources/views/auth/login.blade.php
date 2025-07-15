@extends('layouts.app', ['bodyClass' => 'bg-gradient-primary'])

@section('content')
<style>
    .card-glass {
        backdrop-filter: saturate(200%) blur(30px);
        background-color: rgba(255, 255, 255, 0.8) !important;
    }
    .btn-hover-lift {
        transition: transform .2s ease-out;
    }
    .btn-hover-lift:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card card-glass shadow-xl p-4 p-md-5" style="max-width: 420px; width: 100%; border-radius: 1.5rem; border: none;">
        <div class="text-center mb-4">
            <span class="material-icons text-primary" style="font-size: 52px;">lock_open</span>
        </div>
        <h2 class="mb-4 text-center text-primary font-weight-bold">Welcome Back!</h2>
        <form method="POST" action="{{ route('login') }}" role="form" class="text-start">
            @csrf
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required autofocus>
            </div>
            <div class="input-group input-group-outline mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-check form-switch d-flex align-items-center mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100 my-4 mb-2 btn-hover-lift" style="border-radius: 0.75rem;">Login</button>
            </div>
            <p class="mt-4 text-sm text-center">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Register here</a>
            </p>
        </form>
    </div>
</div>
@endsection