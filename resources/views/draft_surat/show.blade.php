@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12">
                    <h5 class="main-title">Detail Draft Surat</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li><a class="f-s-14 f-w-500" href="#">Home</a></li>
                        <li><a class="f-s-14 f-w-500" href="{{ route('draft_surat.index') }}">Daftar Draft Surat</a></li>
                        <li class="active"><a class="f-s-14 f-w-500" href="#">Detail Draft</a></li>
                    </ul>
                </div>
            </div>
            <!-- Breadcrumb end -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5 class="mb-0">Informasi Surat</h5>
                                </div>
                                <div class="col text-end">
                                    <a href="{{ route('draft_surat.index') }}" class="btn btn-secondary btn-sm b-r-22 me-1">
                                        <i class="iconoir-arrow-left"></i> Kembali
                                    </a>
                                    <a href="{{ route('draft_surat.edit', $draft->id) }}"
                                        class="btn btn-warning btn-sm b-r-22 me-1">
                                        <i class="iconoir-edit-pencil"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-success btn-sm b-r-22">
                                        <i class="iconoir-send"></i> Kirim
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Jenis Surat</strong></div>
                                <div class="col-md-10">: {{ $draft->jenis->name ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>No. Surat</strong></div>
                                <div class="col-md-10">: {{ $draft->nosurat }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Klasifikasi</strong></div>
                                <div class="col-md-10">: {{ $draft->kodeklasifikasi }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Tgl Surat</strong></div>
                                <div class="col-md-10">: {{ $draft->tgl_surat }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Hal</strong></div>
                                <div class="col-md-10">: {{ $draft->hal }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Sifat</strong></div>
                                <div class="col-md-10">
                                    : @if ($draft->sifat == 1)
                                        <span class="badge bg-danger">Penting</span>
                                    @elseif($draft->sifat == 2)
                                        <span class="badge bg-warning text-dark">Rahasia</span>
                                    @else
                                        <span class="badge bg-info">Biasa</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Lampiran</strong></div>
                                <div class="col-md-10">: {{ $draft->jml_lampiran }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Kepada</strong></div>
                                <div class="col-md-10">
                                    : @php
                                        $kepada = json_decode($draft->kepada);
                                        $names = [];
                                        foreach ($kepada as $k) {
                                            $data = json_decode($k);
                                            $names[] = $data->name;
                                        }
                                        echo implode(', ', $names);
                                    @endphp
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Tembusan</strong></div>
                                <div class="col-md-10">: {{ $draft->tembusan }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Penandatangan</strong></div>
                                <div class="col-md-10">: {{ $draft->ttd_nama }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2"><strong>Status</strong></div>
                                <div class="col-md-10">
                                    : @if ($draft->status == 1)
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    @elseif($draft->status == 2)
                                        <span class="badge bg-info">Diproses</span>
                                    @elseif($draft->status == 3)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-2"><strong>Isi</strong></div>
                                <div class="col-md-10">: {!! $draft->isi !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
