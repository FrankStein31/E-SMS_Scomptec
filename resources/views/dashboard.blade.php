@extends('layouts.app', ['bodyClass' => 'bg-gray-100'])

@section('content')
<style>
    .sidebar-md {
        min-height: 100vh;
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.05);
        padding: 2rem 1rem;
    }
    .sidebar-md .nav-link.active {
        background: #e3e6f0;
        color: #1976d2;
        font-weight: bold;
        border-radius: .5rem;
    }
    .sidebar-md .nav-link {
        color: #333;
        margin-bottom: .5rem;
        transition: background .2s;
    }
    .sidebar-md .nav-link:hover {
        background: #f5f5f5;
        color: #1976d2;
    }
    .dashboard-header {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.05);
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
    }
</style>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="sidebar-md">
                <div class="mb-4 text-center">
                    <img src="{{ asset('assets/img/logo-ct.png') }}" alt="Logo" style="width: 60px;">
                    <h5 class="mt-2 mb-0">E-SMS Scomptec</h5>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#"><i class="material-icons me-2">dashboard</i> Dashboard</a>
                    <a class="nav-link" href="#"><i class="material-icons me-2">mail</i> Surat Masuk</a>
                    <a class="nav-link" href="#"><i class="material-icons me-2">send</i> Surat Keluar</a>
                    <a class="nav-link" href="#"><i class="material-icons me-2">settings</i> Pengaturan</a>
                </nav>
                <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100" style="border-radius: 0.75rem;">
                        <i class="material-icons me-1">logout</i> Logout
                    </button>
                </form>
            </div>
        </div>
        <div class="col-md-9">
            <div class="dashboard-header mb-4">
                <h3 class="mb-0">Dashboard</h3>
                <div class="text-muted">Selamat datang, <b>{{ auth()->user()->fullname ?? auth()->user()->username }}</b></div>
            </div>
            <div class="card p-4 shadow-sm">
                <h5>Konten Utama</h5>
                <p>Ini adalah halaman dashboard utama. Silakan custom sesuai kebutuhan aplikasi surat anda.</p>
            </div>
        </div>
    </div>
</div>
@endsection 