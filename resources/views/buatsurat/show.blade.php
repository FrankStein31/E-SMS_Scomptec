@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Detail Surat</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="#">
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('buatsurat.index') }}">Daftar Surat</a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Detail Surat</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- Blank start -->
        <div class="row">
            <!-- Default Card start -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h5>Detail Surat</h5>
                            </div>
                            <div class="col text-end">
                                <a href="{{ route('buatsurat.index') }}" class="btn btn-secondary btn-sm b-r-22">
                                    <i class="iconoir-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Jenis Surat</strong>
                            </div>
                            <div class="col-md-10">
                                : {{ $surat->jenis->name }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>No. Surat</strong>
                            </div>
                            <div class="col-md-10">
                                : {{ $surat->nosurat }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Klasifikasi</strong>
                            </div>
                            <div class="col-md-10">
                                : {{ $surat->kodeklasifikasi }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Tgl Surat</strong>
                            </div>
                            <div class="col-md-10">
                                : {{ $surat->tgl_surat }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Hal</strong>
                            </div>
                            <div class="col-md-10">
                                : {{ $surat->hal }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Sifat</strong>
                            </div>
                            <div class="col-md-10">
                                : @if($surat->sifat == 1)
                                    <span class="badge bg-danger">Penting</span>
                                @elseif($surat->sifat == 2)
                                    <span class="badge bg-warning">Rahasia</span>
                                @else
                                    <span class="badge bg-info">Biasa</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Lampiran</strong>
                            </div>
                            <div class="col-md-10">
                                : {{ $surat->jml_lampiran }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Kepada</strong>
                            </div>
                            <div class="col-md-10">
                                : @php
                                    $kepada = json_decode($surat->kepada);
                                    $names = [];
                                    foreach($kepada as $k) {
                                        $data = json_decode($k);
                                        $names[] = $data->name;
                                    }
                                    echo implode(', ', $names);
                                @endphp
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Isi</strong>
                            </div>
                            <div class="col-md-10">
                                : {!! $surat->isi !!}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Tembusan</strong>
                            </div>
                            <div class="col-md-10">
                                : {{ $surat->tembusan }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Penandatangan</strong>
                            </div>
                            <div class="col-md-10">
                                : {{ $surat->ttd_nama }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <strong>Status</strong>
                            </div>
                            <div class="col-md-10">
                                : @if($surat->status == 1)
                                    <span class="badge bg-warning">Draft</span>
                                @elseif($surat->status == 2)
                                    <span class="badge bg-info">Diproses</span>
                                @elseif($surat->status == 3)
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Default Card end -->
        </div>
        <!-- Blank end -->
    </div>
</main>
@endsection 