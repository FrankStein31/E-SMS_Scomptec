@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <div class="row m-1">
                <div class="col-12">
                    <h4 class="main-title">Entri Surat</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="#">
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Daftar Entri Surat</a>
                        </li>
                    </ul>
                </div>
            </div>
            @include('layout.alert')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>Daftar Entri Surat</h5>
                                </div>
                                <div class="col text-end">
                                    <a href="{{ route('entrisurat.create') }}" class="btn btn-primary btn-sm b-r-22">
                                        <i class="iconoir-plus"></i> Tambah Entri Surat
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <select id="filterSifat" class="form-select form-select-sm" data-placeholder="-- Semua Sifat --">
                                        <option value="">-- Semua Sifat --</option>
                                        <option value="1">Penting</option>
                                        <option value="2">Rahasia</option>
                                        <option value="3">Biasa</option>
                                        <option value="4">Pribadi</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="filterJenis" class="form-select form-select-sm" data-placeholder="-- Semua Jenis --">
                                        <option value="">-- Semua Jenis --</option>
                                        @foreach(App\Models\MasterJenisSurat::all() as $jenis)
                                            <option value="{{ $jenis->last_id }}">{{ $jenis->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="filterUnit" class="form-select form-select-sm" data-placeholder="-- Semua Unit Pengentri --">
                                        <option value="">-- Semua Unit Pengentri --</option>
                                        @foreach(App\Models\User::orderBy('fullname')->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="filterTujuan" class="form-select form-select-sm" data-placeholder="-- Semua Tujuan --">
                                        <option value="">-- Semua Tujuan --</option>
                                        @foreach(App\Models\EntrySuratIsi::select('kepada')->distinct()->get() as $row)
                                            @if($row->kepada)
                                                <option value="{{ $row->kepada }}">{{ $row->kepada }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                {{ $dataTable->table(['id' => 'tabelEntriSurat']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script>
    $(function(){
        $('#filterSifat, #filterJenis, #filterUnit, #filterTujuan').select2({
            width: '100%',
            allowClear: true
        });
        $('#filterSifat, #filterJenis, #filterUnit, #filterTujuan').on('change', function(){
            let sifat = $('#filterSifat').val();
            let jenis = $('#filterJenis').val();
            let unit = $('#filterUnit').val();
            let tujuan = $('#filterTujuan').val();
            window.LaravelDataTables['tabelEntriSurat'].ajax.url('?sifat='+sifat+'&jenis='+jenis+'&unit_pengentri='+unit+'&tujuan='+tujuan).load();
        });
    });
    </script>
@endpush
