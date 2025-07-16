@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <h4 class="main-title">Entri Surat</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Entri Surat</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Breadcrumb end -->

            @include('layout.alert')

            <!-- Blank start -->
            <div class="row">
                <!-- Default Card start -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Form Entri Surat</h5>
                                </div>
                                <div class="col text-end">
                                    <a href="{{ route('entrisurat.index') }}" class="btn btn-info btn-sm">Daftar Entri
                                        Surat</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('entrisurat.store') }}" class="app-form" method="post">
                                @csrf
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">No. Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="no_surat" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Hal</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="hal" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Klasifikasi</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="select-example form-select form-select-sm select-basic"
                                            name="klasifikasi">
                                            <option value="">Pilih Jenis Klasifikasi</option>
                                            @foreach ($klasifikasi as $item)
                                                <option value="{{ $item->id }}">{{ $item->kodeklasifikasi }} -
                                                    {{ $item->klasifikasi }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Kepada</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control select-1" name="kepada[]" multiple="multiple">
                                            <option value="">Pilih Kepada</option>
                                            @foreach ($users as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['FullName'] }} -
                                                    {{ $item['Jabatan2'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Dari</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="dari" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Alamat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="alamat" name="alamat" placeholder="...." rows="1"></textarea>
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
                                        <label class="form-label">Sifat Surat</label>
                                    </div>
                                    <div class="col-md-4 col-lg-6">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default1"
                                                        name="sifat" checked value="1" type="radio">
                                                    <label class="form-check-label" for="radio_default1">
                                                        Penting
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default2"
                                                        name="sifat" value="2" type="radio">
                                                    <label class="form-check-label" for="radio_default2">
                                                        Rahasia
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default13"
                                                        name="sifat" value="3" type="radio">
                                                    <label class="form-check-label" for="radio_default13">
                                                        Biasa
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default14"
                                                        name="sifat" value="4" type="radio">
                                                    <label class="form-check-label" for="radio_default14">
                                                        Pribadi
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Lampiran</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="lampiran" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Ringkasan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="ringkasan" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tembusan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="tembusan" type="text">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label"></label>
                                    </div>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Default Card end -->
            </div>
            <!-- Blank end -->
        </div>
    </main>
@endsection
