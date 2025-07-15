@extends('layouts.app', ['bodyClass' => 'bg-gradient-primary'])

@section('content')
<style>
    .card-glass {
        backdrop-filter: saturate(200%) blur(30px);
        background-color: rgba(255, 255, 255, 0.8) !important;
    }
    .btn-hover-lift {
        transition: transform .2s ease-out, box-shadow .2s ease-out;
    }
    .btn-hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,.1);
    }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card card-glass shadow-xl p-4 p-md-5" style="max-width: 480px; width: 100%; border-radius: 1.5rem; border: none;">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/logo-ct.png') }}" alt="Logo" class="img-fluid" style="width: 90px;">
        </div>
        <h1 class="mb-2 text-primary text-center font-weight-bold">E-SMS Scomptec</h1>
        <p class="mb-4 text-secondary text-center h6">Sistem Manajemen Surat berbasis Laravel & Material Dashboard</p>
        <div class="d-grid gap-3 col-10 mx-auto mt-3">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-hover-lift" style="border-radius: 0.75rem;">
                <i class="material-icons opacity-10 me-1">login</i> Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg btn-hover-lift" style="border-radius: 0.75rem;">
                <i class="material-icons opacity-10 me-1">person_add</i> Register
            </a>
        </div>
    </div>
</div>
@endsection