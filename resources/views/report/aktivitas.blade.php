@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <div class="row m-1">
            <div class="col-12">
                <h5 class="main-title">Aktivitas User</h5>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Aktivitas</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form method="get" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Jenis Aktivitas</label>
                                <select name="jenis" class="form-select form-select-sm">
                                    <option value="">Semua</option>
                                    <option value="masuk" {{ request('jenis')=='masuk'?'selected':'' }}>Surat Masuk</option>
                                    <option value="keluar" {{ request('jenis')=='keluar'?'selected':'' }}>Surat Keluar</option>
                                </select>
                            </div>
                            <!-- Tidak ada tombol submit, filter auto-submit -->
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            {!! $dataTable->table(['id' => 'aktivitas-table']) !!}
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
<script>
$(document).ready(function() {
    // Auto submit on change
    $('form').on('change', 'select, input[type=date]', function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush 