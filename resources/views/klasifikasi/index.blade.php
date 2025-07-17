@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Klasifikasi</h5>
            <div class="d-flex gap-2">
                <form class="d-flex" method="get" action="" id="formSearch">
                    <input type="text" name="q" class="form-control form-control-sm me-2" placeholder="Cari kode/klasifikasi..." value="{{ request('q') }}" id="inputSearch">
                </form>
                <button class="btn btn-primary btn-sm" id="btnTambah">Tambah Klasifikasi</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Klasifikasi</th>
                            <th>Retensi Aktif</th>
                            <th>Retensi Inaktif</th>
                            <th>Keterangan</th>
                            <th>Retensi</th>
                            <th>Parent</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td>{{ $row->kodeklasifikasi }}</td>
                            <td>{{ $row->klasifikasi }}</td>
                            <td>{{ $row->retensi_aktif }}</td>
                            <td>{{ $row->retensi_inaktif }}</td>
                            <td>
                                @if($row->keterangan==1) Dinilai Kembali
                                @elseif($row->keterangan==2) Musnah
                                @else Permanen
                                @endif
                            </td>
                            <td>{{ $row->retensi }}</td>
                            <td>{{ $row->parent }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btnEdit" data-id="{{ $row->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm btnHapus" data-id="{{ $row->id }}">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center flex-wrap p-2">
        <div class="small text-muted">
            Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari {{ $data->total() }} data
        </div>
        <div>
            @php $links = $data->appends(['q'=>request('q')])->onEachSide(1)->links('pagination::bootstrap-5'); @endphp
            @if(trim($links) != '')
                {!! $links !!}
            @elseif($data->lastPage() > 1)
                <nav>
                    <ul class="pagination">
                        <li class="page-item {{ $data->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $data->previousPageUrl() }}">&laquo;</a>
                        </li>
                        @for($i = 1; $i <= $data->lastPage(); $i++)
                            <li class="page-item {{ $i == $data->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ !$data->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $data->nextPageUrl() }}">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            @endif
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
<script>
$(function(){
    // Auto search
    $('#inputSearch').on('input', function(){
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(function(){
            $('#formSearch').submit();
        }, 400);
    });
    // Tambah
    $('#btnTambah').click(function(){
        $('#formKlasifikasi')[0].reset();
        $('#modalTitle').text('Tambah Klasifikasi');
        $('#klasifikasi_id').val('');
        $('#modalKlasifikasi').modal('show');
    });
    // Edit
    $('.btnEdit').click(function(){
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
            }
        });
    });
    // Simpan
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
                    // reload data table tanpa reload full page
                    location.reload();
                }
            },
            error: function(xhr){
                alert('Gagal simpan data!');
            }
        });
    });
    // Hapus
    $('.btnHapus').click(function(){
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
                        window.location = window.location.href;
                    }
                }
            });
        }
    });
});
</script>
@endpush

@push('css')
<style>
.pagination { display: flex !important; }
.page-link, .pagination { color: #0d6efd !important; background: #fff !important; border: 1px solid #dee2e6 !important; }
.page-item.active .page-link { background: #0d6efd !important; color: #fff !important; border-color: #0d6efd !important; }
</style>
@endpush 