@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- <div class="row m-1">
                <div class="col-12 ">
                    <h5 class="main-title">Laporan Surat</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Laporan</a>
                        </li>
                    </ul>
                </div>
            </div> -->
            @include('layout.alert')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Laporan Surat</h5>
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
                                    <label class="form-label">Kepada</label>
                                    <select name="kepada" class="form-select form-select-sm select2">
                                        <option value="">Semua</option>
                                        @foreach ($satker as $item)
                                            <option value="{{ $item->satker }}">{{ $item->satker }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit"
                                        class="btn btn-primary btn-sm b-r-22 btn-add-primary d-none">Tampilkan</button>
                                    <a href="#" id="btnCetak" target="_blank"
                                        class="btn btn-warning btn-sm b-r-22 btn-action-edit">Cetak</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <!-- <div class="card-header">
                            <h5>List Data</h5>
                        </div> -->
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

@push('js')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
    <style>
        /* Enhanced Button Styling for Better Text Visibility */
        .btn-action-detail {
            background-color: #e3f2fd !important;
            border-color: #bbdefb !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .btn-action-detail:hover {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
        }

        .btn-action-edit {
            background-color: #fff3cd !important;
            border-color: #ffeaa7 !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .btn-action-edit:hover {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
        }

        .btn-action-delete {
            background-color: #f8d7da !important;
            border-color: #f5c6cb !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .btn-action-delete:hover {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
        }

        .btn-add-primary {
            background-color: #cfe2ff !important;
            border-color: #b6d4fe !important;
            color: #212529 !important;
            font-weight: 500;
        }

        .btn-add-primary:hover {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
        }

        /* Focus states for accessibility */
        .btn-action-detail:focus,
        .btn-action-detail:active,
        .btn-action-edit:focus,
        .btn-action-edit:active,
        .btn-action-delete:focus,
        .btn-action-delete:active,
        .btn-add-primary:focus,
        .btn-add-primary:active {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #495057 !important;
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25) !important;
        }
    </style>
    <script>
        $(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Pilih...',
                allowClear: true,
                width: '100%'
            });

            var table = window.LaravelDataTables['reportsurat-table'];
            // Tambahkan filter ke parameter AJAX DataTables
            table.on('preXhr.dt', function(e, settings, data) {
                var formData = $('#filterForm').serializeArray();
                formData.forEach(function(item) {
                    data[item.name] = item.value;
                });
            });
            // Trigger reload DataTables via ajax.reload() setiap filter berubah
            $('#filterForm').on('change', 'input,select', function() {
                table.ajax.reload();
                var url = '{{ route('report.cetak') }}?' + $('#filterForm').serialize();
                $('#btnCetak').attr('href', url);
            });
            // Set default cetak link
            $('#btnCetak').attr('href', '{{ route('report.cetak') }}');
        });
    </script>
@endpush
