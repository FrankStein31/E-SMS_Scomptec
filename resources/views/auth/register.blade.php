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
    <div class="card card-glass shadow-xl p-4 p-md-5" style="max-width: 480px; width: 100%; border-radius: 1.5rem; border: none;">
        <div class="text-center mb-4">
            <span class="material-icons text-primary" style="font-size: 52px;">person_add</span>
        </div>
        <h2 class="mb-4 text-center text-primary font-weight-bold">Create Account</h2>
        <form method="POST" action="{{ route('register') }}" role="form" class="text-start">
            @csrf
            <div class="input-group input-group-outline my-2">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="input-group input-group-outline my-2">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="fullname" value="{{ old('fullname') }}" required>
            </div>
            <div class="input-group input-group-outline my-2">
                <label class="form-label">Jabatan</label>
                <input type="text" class="form-control" name="jabatan" value="{{ old('jabatan') }}">
            </div>
            <div class="input-group input-group-outline my-2">
                <label class="form-label">Satker ID</label>
                <input type="text" class="form-control" name="satkerid" value="{{ old('satkerid') }}">
            </div>
            <div class="input-group input-group-outline my-2">
                <label class="form-label">NIP</label>
                <input type="text" class="form-control" name="nip" value="{{ old('nip', '-') }}">
            </div>
            <div class="input-group input-group-outline my-2">
                <label class="form-label">User Group ID</label>
                <input type="number" class="form-control" name="usergroupid" value="{{ old('usergroupid') }}">
            </div>
            <div class="input-group input-group-outline my-2">
                <label class="form-label">Pangkat</label>
                <input type="text" class="form-control" name="pangkat" value="{{ old('pangkat') }}">
            </div>
            <div class="input-group input-group-outline my-2">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="input-group input-group-outline my-2">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="input-group input-group-outline mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100 my-4 mb-2 btn-hover-lift" style="border-radius: 0.75rem;">Register</button>
            </div>
            <p class="mt-4 text-sm text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Login</a>
            </p>
        </form>
    </div>
</div>
@endsection