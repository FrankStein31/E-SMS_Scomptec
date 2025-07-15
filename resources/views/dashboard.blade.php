@extends('layouts.app', ['bodyClass' => 'bg-gray-100', 'sidebar' => true])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-header bg-gradient-primary shadow-primary mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 text-white">Dashboard</h4>
                    <span class="text-white-50">Selamat datang, <b>{{ auth()->user()->fullname ?? auth()->user()->username }}</b></span>
                </div>
                <div class="mt-3 mt-md-0">
                    <i class="material-icons text-white" style="font-size: 48px;">dashboard</i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md me-3">
                    <i class="material-icons text-white">mail</i>
                </div>
                <div>
                    <h6 class="mb-0">Surat Masuk</h6>
                    <span class="text-sm text-muted">12 Surat</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md me-3">
                    <i class="material-icons text-white">send</i>
                </div>
                <div>
                    <h6 class="mb-0">Surat Keluar</h6>
                    <span class="text-sm text-muted">7 Surat</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md me-3">
                    <i class="material-icons text-white">people</i>
                </div>
                <div>
                    <h6 class="mb-0">User Terdaftar</h6>
                    <span class="text-sm text-muted">5 User</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header pb-0">
                <h6>Aktivitas Terbaru</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Aktivitas</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="text-xs font-weight-bold">12/06/2024</span>
                                </td>
                                <td>
                                    <span class="text-xs">Login ke sistem</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge bg-gradient-success">Sukses</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-xs font-weight-bold">11/06/2024</span>
                                </td>
                                <td>
                                    <span class="text-xs">Register user baru</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge bg-gradient-info">Info</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 