@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <h5 class="main-title">Laporan</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        {{-- <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Laporan
                                </span>
                            </a>
                        </li> --}}
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Laporan</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Breadcrumb end -->

            @include('layout.alert')

            <!-- Blank start -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Filter</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" class="app-form" method="get">
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label"></label>
                                    </div>
                                    <div class="col-md-4 col-lg-6">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default1"
                                                        name="surat_type" checked value="surat_masuk" type="radio">
                                                    <label class="form-check-label" for="radio_default1">
                                                        Surat Masuk
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default2"
                                                        name="surat_type" value="entry_surat" type="radio">
                                                    <label class="form-check-label" for="radio_default2">
                                                        Entry Surat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default13"
                                                        name="surat_type" value="surat_keluar" type="radio">
                                                    <label class="form-check-label" for="radio_default13">
                                                        Surat Keluar
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default14"
                                                        name="surat_type" value="surat_terkirim" type="radio">
                                                    <label class="form-check-label" for="radio_default14">
                                                        Surat Terkirim
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Sifat Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="sifat_surat" class="form-control form-control-sm">
                                            <option value=""></option>
                                            <option value="semua">Semua</option>
                                            <option value="penting">Penting</option>
                                            <option value="rahasia">Rahasia</option>
                                            <option value="biasa">Biasa</option>
                                            <option value="pribadi">Pribadi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Jenis Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="select-example form-select form-select-sm select-basic"
                                            name="jenis_surat">
                                            <option selected>Pilih Jenis Surat</option>
                                            @foreach ($jenisSurat as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tgl Surat / Tgl Terima</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col">
                                                <input class="form-control form-control-sm" type="date" name="tgl_surat">
                                            </div>
                                            <div class="col">
                                                <input class="form-control form-control-sm" type="date"
                                                    name="tgl_terima">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Kepada</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="select-example form-select form-select-sm select-basic"
                                            name="kepada">
                                            <option selected value=""></option>
                                            @foreach ($satker as $item)
                                                <option value="{{ $item->id }}">{{ $item->satker }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label"></label>
                                    </div>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
                                        <a href="{{ route('report.cetak') . '?' . http_build_query(request()->except('_token')) . '&cetak=1' }}"
                                            target="_blank" class="btn btn-warning btn-sm">
                                            Cetak
                                        </a>


                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>List Data</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                No
                                            </th>
                                            <th scope="col">
                                                No. Agenda
                                            </th>
                                            <th scope="col">
                                                Sifat
                                            </th>
                                            <th scope="col">
                                                Jenis
                                            </th>
                                            <th scope="col">
                                                No. Surat
                                            </th>
                                            <th scope="col">
                                                Dari
                                            </th>
                                            <th scope="col">
                                                Tujuan
                                            </th>
                                            <th scope="col">
                                                Hal
                                            </th>
                                            <th scope="col">
                                                Unit Pengentri
                                            </th>
                                            <th scope="col">
                                                Tanggal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->noagenda ?? '-' }}</td>
                                                <td>{{ sifatSurat($item->sifat ?? 0) }}</td>
                                                <td>{{ $item->jenis_id ?? '-' }}</td>
                                                <td>{{ $item->nomor_surat ?? '-' }}</td>
                                                <td>{{ $item->dari ?? '-' }}</td>
                                                <td>{{ $item->kepada }}</td>
                                                <td>{{ $item->hal ?? '-' }}</td>
                                                <td>{{ $item->created_by ?? '-' }}</td>
                                                <td>{{ $item->tgl_surat ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.clickable-row').click(function() {
                window.location = $(this).data('href');
            });
        });
    </script>
@endpush
