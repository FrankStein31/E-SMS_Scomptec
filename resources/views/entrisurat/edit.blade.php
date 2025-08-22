@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <!-- <div class="row m-1">
                <div class="col-12 ">
                    <h5 class="main-title">Edit Entri Surat</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="">
                            <a class="f-s-14 f-w-500" href="{{ route('entrisurat.index') }}">Entri Surat</a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Edit</a>
                        </li>
                    </ul>
                </div>
            </div> -->
            <!-- Breadcrumb end -->

            @include('layout.alert')

            <!-- Edit Form start -->
            <div class="row">
                <!-- Default Card start -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Form Edit Entri Surat</h5>
                                </div>
                                <div class="col text-end">
                                    <a href="{{ route('entrisurat.index') }}" class="btn btn-info btn-sm">Daftar Entri
                                        Surat</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                        <!-- Debug data
                            <div class="alert alert-info">
                                <small>
                                    Debug: klasifikasi = {{ $data->kode_klasifikasi }}<br>
                                    Debug: selectedKepada = {{ json_encode($selectedKepada) }}<br>
                                    Debug: sifat = {{ $data->sifat }}<br>
                                    Debug: jenis_id = {{ $data->jenis_id }}
                                </small>
                            </div> -->
                            
                            <form action="{{ route('entrisurat.update', $data->id) }}" class="app-form" method="post">
                                @csrf
                                @method('PUT')
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">No. Surat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="no_surat" type="text" 
                                               value="{{ old('no_surat', $data->nomor_surat ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Hal</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="hal" type="text" 
                                               value="{{ old('hal', $data->hal ?? '') }}">
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
                                                <option value="{{ $item->id }}" 
                                                    {{ old('klasifikasi', $data->kode_klasifikasi) == $item->id ? 'selected' : '' }}>
                                                    {{ $item->kodeklasifikasi }} - {{ $item->klasifikasi }}
                                                </option>
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
                                                @if (strtolower($item['Jabatan2']) != 'administrator')
                                                    <option value="{{ $item['id'] }}" 
                                                        {{ in_array($item['id'], $selectedKepada) ? 'selected' : '' }}>
                                                        {{ $item['FullName'] }} - {{ $item['Jabatan2'] }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Dari</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="dari" type="text" 
                                               value="{{ old('dari', $data->dari ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Alamat</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="alamat" name="alamat" placeholder="...." rows="1">{{ old('alamat', $data->alamat ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tgl Surat / Tgl Terima</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col">
                                                <input class="form-control form-control-sm" type="date" name="tgl_surat" 
                                                       value="{{ old('tgl_surat', $data->tgl_surat ?? '') }}">
                                            </div>
                                            <div class="col">
                                                <input class="form-control form-control-sm" type="date" name="tgl_terima" 
                                                       value="{{ old('tgl_terima', $data->tgl_diterima ?? '') }}">
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
                                            <option disabled>Pilih Jenis Surat</option>
                                            @foreach ($jenisSurat as $item)
                                                <option value="{{ $item->last_id }}"
                                                    {{ old('jenis_surat', $data->jenis_id ?? '') == $item->last_id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
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
                                                        name="sifat" value="1" type="radio" 
                                                        {{ old('sifat', $data->sifat ?? '') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="radio_default1">
                                                        Penting
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default2"
                                                        name="sifat" value="2" type="radio" 
                                                        {{ old('sifat', $data->sifat ?? '') == '2' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="radio_default2">
                                                        Rahasia
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default13"
                                                        name="sifat" value="3" type="radio" 
                                                        {{ old('sifat', $data->sifat ?? '') == '3' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="radio_default13">
                                                        Biasa
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input f-s-18 mb-1 m-1" id="radio_default14"
                                                        name="sifat" value="4" type="radio" 
                                                        {{ old('sifat', $data->sifat ?? '') == '4' ? 'checked' : '' }}>
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
                                        <input class="form-control form-control-sm" name="lampiran" type="text" 
                                               value="{{ old('lampiran', $data->jumlah_lampiran ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Ringkasan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="ringkasan" type="text" 
                                               value="{{ old('ringkasan', $data->isi ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label">Tembusan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input class="form-control form-control-sm" name="tembusan" type="text" 
                                               value="{{ old('tembusan', $data->tembusan ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <label class="form-label"></label>
                                    </div>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-primary btn-update-form">Update</button>
                                        <a href="{{ route('entrisurat.index') }}" class="btn btn-secondary btn-cancel-form">Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Default Card end -->
            </div>
            <!-- Edit Form end -->
        </div>
    </main>
@endsection

@push('styles')
<style>
    .btn-update-form {
        background-color: #fff3cd !important;
        border-color: #ffeaa7 !important;
        color: #212529 !important;
        font-weight: 500;
    }
    
    .btn-update-form:hover {
        background-color: #e9ecef !important;
        border-color: #ced4da !important;
        color: #495057 !important;
    }
    
    .btn-update-form:focus,
    .btn-update-form:active {
        background-color: #e9ecef !important;
        border-color: #ced4da !important;
        color: #495057 !important;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25) !important;
    }
    
    .btn-cancel-form {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        color: #212529 !important;
        font-weight: 500;
    }
    
    .btn-cancel-form:hover {
        background-color: #e9ecef !important;
        border-color: #ced4da !important;
        color: #495057 !important;
    }
    
    .btn-cancel-form:focus,
    .btn-cancel-form:active {
        background-color: #e9ecef !important;
        border-color: #ced4da !important;
        color: #495057 !important;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25) !important;
    }
</style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            try {
                // Initialize Select2 for multiple select with error handling
                if ($('.select-1').length) {
                    $('.select-1').select2({
                        placeholder: "Pilih Kepada",
                        allowClear: true,
                        width: '100%'
                    });
                }
                
                // Initialize Select2 for single select with error handling
                if ($('.select-basic').length) {
                    $('.select-basic').select2({
                        width: '100%'
                    });
                }
                
                console.log('Select2 initialized successfully for edit form');
            } catch (error) {
                console.error('Select2 initialization error:', error);
            }
        });
    </script>
@endpush
