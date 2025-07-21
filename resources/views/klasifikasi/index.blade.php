@extends('layout.main')

@section('content')
<div class="container-fluid klasifikasi-container">
    <div class="card mt-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0">Data Klasifikasi</h5>
            <div class="d-flex gap-2 align-items-center">
                <select id="filterKodeUtama" class="form-select form-select-sm" style="min-width:220px;max-width:320px;">
                    <option value="">Semua Kode</option>
                    @foreach($kodeUtama as $kode => $row)
                        <option value="{{ $kode }}">{{ $kode }} - {{ $row->klasifikasi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-sm" id="btnTambah">Tambah Klasifikasi</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="modalKlasifikasi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formKlasifikasi">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Klasifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="klasifikasi_id">
                    <div class="mb-2">
                        <label>Kode Klasifikasi</label>
                        <input type="text" name="kodeklasifikasi" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Klasifikasi</label>
                        <textarea name="klasifikasi" class="form-control" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label>Retensi Aktif</label>
                            <input type="number" name="retensi_aktif" class="form-control" required>
                        </div>
                        <div class="col">
                            <label>Retensi Inaktif</label>
                            <input type="number" name="retensi_inaktif" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-2 mt-2">
                        <label>Keterangan</label>
                        <select name="keterangan" class="form-control" required>
                            <option value="1">Dinilai Kembali</option>
                            <option value="2">Musnah</option>
                            <option value="3">Permanen</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Retensi</label>
                        <input type="number" name="retensi" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Parent</label>
                        <input type="text" name="parent" class="form-control">
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
    // Inisialisasi select2 untuk dropdown filter
    $('#filterKodeUtama').select2({
        width: 'resolve',
        placeholder: 'Pilih Kode',
        allowClear: true,
        dropdownParent: $('.card-header')
    });

    var table = $('#tabelKlasifikasi').DataTable();

    // Filter kode utama (kolom ke-0 = kodeklasifikasi)
    $('#filterKodeUtama').on('change', function(){
        var val = $(this).val();
        var colIdx = 0; // kodeklasifikasi kolom ke-0
        if(val) {
            table.column(colIdx).search(val).draw();
        } else {
            table.column(colIdx).search('').draw();
        }
    });

    // Tambah
    $('#btnTambah').click(function(){
        $('#formKlasifikasi')[0].reset();
        $('#modalTitle').text('Tambah Klasifikasi');
        $('#klasifikasi_id').val('');
        $('#modalKlasifikasi').modal('show');
    });
    // Edit pakai event delegation
    $(document).on('click', '.btnEdit', function(){
        let id = $(this).data('id');
        $.get('/klasifikasi/'+id, function(res){
            if(res.success){
                let d = res.data;
                $('#modalTitle').text('Edit Klasifikasi');
                $('#klasifikasi_id').val(d.id);
                $('[name=kodeklasifikasi]').val(d.kodeklasifikasi);
                $('[name=klasifikasi]').val(d.klasifikasi);
                $('[name=retensi_aktif]').val(d.retensi_aktif);
                $('[name=retensi_inaktif]').val(d.retensi_inaktif);
                $('[name=keterangan]').val(d.keterangan);
                $('[name=retensi]').val(d.retensi);
                $('[name=parent]').val(d.parent);
                $('#modalKlasifikasi').modal('show');
            } else {
                alert('Data tidak ditemukan!');
            }
        });
    });
    // Hapus pakai event delegation
    $(document).on('click', '.btnHapus', function(){
        if(confirm('Yakin hapus data?')){
            let id = $(this).data('id');
            $.ajax({
                url: '/klasifikasi/'+id,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(res){
                    if(res.success){
                        $('#modalKlasifikasi').modal('hide');
                        table.ajax.reload(null, false);
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
    // Simpan (tambah/edit)
    $('#formKlasifikasi').submit(function(e){
        e.preventDefault();
        let id = $('#klasifikasi_id').val();
        let url = '/klasifikasi'+(id ? '/' + id : '');
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
                    $('#modalKlasifikasi').modal('hide');
                    table.ajax.reload(null, false);
                    alert('Data berhasil disimpan!');
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
.select2-container--default .select2-selection--single {
    height: 32px;
    padding: 2px 8px;
    font-size: 1rem;
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 28px;
}
.klasifikasi-container {
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