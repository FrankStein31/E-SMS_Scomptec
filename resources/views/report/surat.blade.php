@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <div class="row m-1">
            <div class="col-12 ">
                <h5 class="main-title">Laporan Surat</h5>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Laporan</a>
                    </li>
                </ul>
            </div>
        </div>
        @include('layout.alert')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Filter</h5>
                    </div>
                    <div class="card-body">
                        <form id="filterForm" class="row g-2 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label">Sifat Surat</label>
                                <select name="sifat_surat" class="form-select form-select-sm select2">
                                    <option value="">Semua</option>
                                    <option value="penting">Penting</option>
                                    <option value="rahasia">Rahasia</option>
                                    <option value="biasa">Biasa</option>
                                    <option value="pribadi">Pribadi</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jenis Surat</label>
                                <select name="jenis_surat" class="form-select form-select-sm select2">
                                    <option value="">Semua</option>
                                    @foreach ($jenisSurat as $item)
                                        <option value="{{ $item->last_id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tgl Surat</label>
                                <input class="form-control form-control-sm" type="date" name="tgl_surat">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tgl Terima</label>
                                <input class="form-control form-control-sm" type="date" name="tgl_terima">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Kepada</label>
                                <select name="kepada" class="form-select form-select-sm select2">
                                    <option value="">Semua</option>
                                    @foreach ($satker as $item)
                                        <option value="{{ $item->satker }}">{{ $item->satker }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm d-none">Tampilkan</button>
                                <a href="#" id="btnCetak" target="_blank" class="btn btn-warning btn-sm">Cetak</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5>List Data</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            {!! $dataTable->table(['id' => 'reportsurat-table']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script>
$(function(){
    // Inisialisasi select2
    $('.select2').select2({
        width: '100%',
        placeholder: 'Pilih',
        allowClear: true
    });
    var table = window.LaravelDataTables['reportsurat-table'];
    // Trigger reload DataTables via ajax.reload() setiap filter berubah
    $('#filterForm').on('change', 'input,select', function(){
        table.ajax.reload();
        var url = '{{ route('report.cetak') }}?' + $('#filterForm').serialize();
        $('#btnCetak').attr('href', url);
    });
    // Set default cetak link
    $('#btnCetak').attr('href', '{{ route('report.cetak') }}');
});
</script>
@endpush
