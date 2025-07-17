@extends('layout.main') {{-- Assuming your main layout is layout.main --}}

@section('content')
    <main>
        <div class="container-fluid">
            <div class="row m-1">
                <div class="col-12">
                    <h4 class="main-title">Riwayat Disposisi</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="{{ url('/') }}">
                                <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a class="f-s-14 f-w-500" href="{{ route('disposisi.index') }}">Disposisi</a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Riwayat</a>
                        </li>
                    </ul>
                </div>
            </div>
            @include('layout.alert') {{-- Include your alert messages partial --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5>Detail Disposisi: {{ $disposisi->no_surat ?? 'N/A' }}</h5>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('disposisi.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    {{-- This will make the button look like a print button, but it still navigates to the index page. --}}
                                    {{-- <a href="{{ route('disposisi.index') }}" class="btn btn-secondary btn-sm"> --}}
                                        <i class="fas fa-print me-1"></i> Cetak
                                    </a>
                                    {{-- Add other action buttons like Edit, Print for detail view if needed --}}
                                    {{-- <a href="{{ route('disposisi.edit', $disposisi->id) }}" class="btn btn-primary btn-sm ms-2">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>No Agenda:</strong> {{ $disposisi->no_agenda ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>No Surat:</strong> {{ $disposisi->no_surat ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Sifat:</strong> {{ $disposisi->sifat ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Jenis:</strong> {{ $disposisi->jenis ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Dari:</strong> {{ $disposisi->dari ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Tujuan:</strong> {{ $disposisi->tujuan ?? '-' }}
                                </div>
                                <div class="col-md-12 mb-3">
                                    <strong>Hal:</strong> {{ $disposisi->hal ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Tanggal Surat:</strong>
                                    {{ \Carbon\Carbon::parse($disposisi->tgl_surat)->format('d F Y') ?? '-' }}
                                </div>
                                {{-- Add more detail fields as per your Disposition model --}}
                                {{-- For example: --}}
                                {{-- <div class="col-md-12 mb-3">
                                    <strong>Isi Disposisi:</strong> {{ $disposisi->isi_disposisi ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Penerima:</strong> {{ $disposisi->penerima ?? '-' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Tanggal Disposisi:</strong> {{ \Carbon\Carbon::parse($disposisi->tgl_disposisi)->format('d F Y H:i') ?? '-' }}
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
