@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Aktivitas User</h4>
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
                                <label>User</label>
                                <select name="user" class="form-select form-select-sm select2-user">
                                    <option value="">Semua User</option>
                                    @foreach(App\Models\User::all() as $u)
                                        <option value="{{ $u->id }}" {{ request('user') == $u->id ? 'selected' : '' }}>{{ $u->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
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

@push('scripts')
{!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-user').select2({
        placeholder: 'Cari User',
        allowClear: true,
        width: '100%'
    });
    // Auto submit on change
    $('form').on('change', 'select, input[type=date]', function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush 