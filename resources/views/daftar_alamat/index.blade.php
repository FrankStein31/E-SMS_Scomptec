@extends('layout.main')

@section('content')
<div class="container-fluid daftar-alamat-container">
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Alamat Instansi</h5>
            <button class="btn btn-primary btn-sm" id="btnTambah">Tambah Alamat</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                {{ $dataTable->table(['id' => 'tabelAlamat']) }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="modalAlamat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAlamat">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Alamat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="alamat_id">
                    <div class="mb-2">
                        <label>Instansi</label>
                        <input type="text" name="instansi" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Kepala</label>
                        <input type="text" name="kepala" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Kota</label>
                        <input type="text" name="kota" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Telp</label>
                        <input type="text" name="telp" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
<script>
$(function(){
    // Tambah
    $('#btnTambah').click(function(){
        $('#formAlamat')[0].reset();
        $('#modalTitle').text('Tambah Alamat');
        $('#alamat_id').val('');
        $('#modalAlamat').modal('show');
    });
    // Edit pakai event delegation
    $(document).on('click', '.btnEdit', function(){
        let id = $(this).data('id');
        $.get('/daftar-alamat/'+id, function(res){
            if(res.success){
                let d = res.data;
                $('#modalTitle').text('Edit Alamat');
                $('#alamat_id').val(d.id);
                $('[name=instansi]').val(d.instansi);
                $('[name=kepala]').val(d.kepala);
                $('[name=alamat]').val(d.alamat);
                $('[name=kota]').val(d.kota);
                $('[name=telp]').val(d.telp);
                $('#modalAlamat').modal('show');
            }
        });
    });
    // Hapus pakai event delegation
    $(document).on('click', '.btnHapus', function(){
        if(confirm('Yakin hapus data?')){
            let id = $(this).data('id');
            $.ajax({
                url: '/daftar-alamat/'+id,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.success){
                        $('#modalAlamat').modal('hide');
                        window.LaravelDataTables['tabelAlamat'].ajax.reload(null, false);
                        alert('Data berhasil dihapus!');
                    }
                    else if(res.message){
                        alert(res.message);
                    }
                },
                error: function(xhr){
                    let msg = 'Gagal hapus data!';
                    if(xhr.responseJSON && xhr.responseJSON.message){
                        msg = xhr.responseJSON.message;
                    } else if(xhr.responseJSON && xhr.responseJSON.errors){
                        let errors = xhr.responseJSON.errors;
                        msg = Object.values(errors).flat()[0];
                    }
                    alert(msg);
                }
            });
        }
    });
    // Simpan
    $('#formAlamat').submit(function(e){
        e.preventDefault();
        let id = $('#alamat_id').val();
        let url = '/daftar-alamat'+(id ? '/' + id : '');
        let method = id ? 'PUT' : 'POST';
        let formData = $(this).serializeArray();
        formData.push({name: '_token', value: '{{ csrf_token() }}'});
        if(id) formData.push({name: '_method', value: 'PUT'});
        $.ajax({
            url: url,
            type: 'POST',
            data: $.param(formData),
            success: function(res){
                if(res.success){
                    $('#modalAlamat').modal('hide');
                    window.LaravelDataTables['tabelAlamat'].ajax.reload(null, false);
                    alert('Data berhasil disimpan!');
                }
                else if(res.message){
                    alert(res.message);
                }
            },
            error: function(xhr){
                let msg = 'Gagal simpan data!';
                if(xhr.responseJSON && xhr.responseJSON.message){
                    msg = xhr.responseJSON.message;
                } else if(xhr.responseJSON && xhr.responseJSON.errors){
                    let errors = xhr.responseJSON.errors;
                    msg = Object.values(errors).flat()[0];
                }
                alert(msg);
            }
        });
    });
});
</script>
@endpush

@push('css')
<style>
.daftar-alamat-container {
    padding-left: 24px;
    padding-right: 24px;
}
.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
    margin-bottom: 10px;
    margin-top: 10px;
}
.dataTables_wrapper .dataTables_length label, .dataTables_wrapper .dataTables_filter label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 1rem;
    font-weight: 400;
}
.dataTables_wrapper .dataTables_length select {
    margin: 0 4px;
    height: 32px;
    font-size: 1rem;
}
.dataTables_wrapper .dataTables_filter input[type="search"] {
    margin-left: 4px;
    height: 32px;
    font-size: 1rem;
    width: 160px;
}
.dataTables_wrapper .dataTables_length {
    float: left;
}
.dataTables_wrapper .dataTables_filter {
    float: right;
}
@media (max-width: 768px) {
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
        float: none;
        text-align: left;
        margin-bottom: 8px;
    }
    .dataTables_wrapper .dataTables_filter input[type="search"] {
        width: 100%;
    }
}
.dataTables_wrapper .dataTables_paginate {
    margin-top: 10px;
    margin-bottom: 10px;
}
.dataTables_wrapper .dataTables_info {
    margin-top: 10px;
    color: #6c757d;
    margin-left: 0;
}
</style>
@endpush
