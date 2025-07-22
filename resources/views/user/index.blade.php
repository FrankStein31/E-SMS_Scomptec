@extends('layout.main')

@section('content')
    <style>
        .select2-container .select2-results__option {
            text-align: left !important;
            padding-left: 8px;
        }
    </style>
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12">
                    <h5 class="main-title">Daftar User</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="/">
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">User</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Breadcrumb end -->
            <!-- Blank start -->
            <div class="row">
                <!-- Default Card start -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                </div>
                                <div class="col text-end">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        + Tambah
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <!-- Form di luar modal-content agar tombol submit terdeteksi -->
                                            <form action="{{ route('user.store') }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah User</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Username:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="username" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Password:</label>
                                                            <div class="col-sm-9">
                                                                <input type="password" name="password" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Nama Lengkap:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="fullname" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">NIP:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="nip" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Pangkat:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="pangkat" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Jabatan:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="jabatan" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Unit Kerja:</label>
                                                            <div class="col-sm-9">
                                                                <select name="satkerid" class="form-select select2"
                                                                    style="width: 100%;">
                                                                    <option value="">Pilih Unit Kerja</option>
                                                                    @foreach ($masterSatkers as $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ $item->satker }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Email:</label>
                                                            <div class="col-sm-9">
                                                                <input type="email" name="email" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </div> <!-- /.modal-content -->
                                            </form>
                                        </div> <!-- /.modal-dialog -->
                                    </div> <!-- /.modal -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                        <label class="input-group-text" for="group">User Group</label>
                                            <select id="filterJabatan" class="form-select">
                                                <option value="">-- Semua Jabatan --</option>
                                                @foreach($userGroups as $ug)
                                                    <option value="{{ $ug }}">{{ $ug }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{ $dataTable->table(['id' => 'tabelUser']) }}
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- Default Card end -->
                </div>

                <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <!-- Form di luar modal-content agar tombol submit terdeteksi -->
                        <form action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Username:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="username" class="form-control" value=""
                                                id="username" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Password:</label>
                                        <div class="col-sm-9">
                                            <input type="password" id="password" name="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Nama
                                            Lengkap:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="fullname" class="form-control" id="fullname"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">NIP:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nip" id="nip" class="form-control"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Pangkat:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="pangkat" id="pangkat" class="form-control"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Jabatan:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="jabatan" id="jabatan" class="form-control"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Unit
                                            Kerja:</label>
                                        <div class="col-sm-9">
                                            <select id="satkerSelect" name="satkerid" id="satkerid2"
                                                class="form-select select2">
                                                <option value="">Pilih Unit
                                                    Kerja</option>
                                                @foreach ($masterSatkers as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->satker }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{-- <input type="text" class="form-control" name="" id="satkertest"> --}}
                                        </div>
                                    </div>

                                    <div class="row mb-2 align-items-center">
                                        <label class="col-sm-3 col-form-label">Email:</label>
                                        <div class="col-sm-9">
                                            <input type="email" name="email" id="email" class="form-control"
                                                value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div> <!-- /.modal-content -->
                        </form>
                    </div> <!-- /.modal-dialog -->
                </div>
                <!-- Blank end -->
            </div>
    </main>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
    $(function(){
        // Filter jabatan
        $('#filterJabatan').on('change', function(){
            let val = $(this).val();
            window.LaravelDataTables['tabelUser'].column('jabatan:name').search(val).draw();
        });
        // Tambah User AJAX
        $('#exampleModal form').submit(function(e){
            e.preventDefault();
            let form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(res){
                    if(res.success){
                        $('#exampleModal').modal('hide');
                        $('#exampleModal form')[0].reset();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        window.LaravelDataTables['tabelUser'].ajax.reload(null, false);
                        alert('User berhasil ditambahkan!');
                    } else if(res.message){
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
        // Edit User AJAX
        $(document).on('click', '.btnEdit', function(){
            let id = $(this).data('id');
            $.get('/user?id='+id, function(data){
                $('#exampleModal2 #username').val(data.username);
                $('#exampleModal2 #fullname').val(data.fullname);
                $('#exampleModal2 #nip').val(data.nip);
                $('#exampleModal2 #pangkat').val(data.pangkat);
                $('#exampleModal2 #jabatan').val(data.jabatan);
                $('#exampleModal2 #satkerid2').val(data.satkerid).trigger('change');
                $('#exampleModal2 #email').val(data.email);
                $('#exampleModal2 form').attr('action', '/user/'+data.id);
                $('#exampleModal2').modal('show');
            });
        });
        // Update User AJAX
        $('#exampleModal2 form').submit(function(e){
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let data = form.serialize();
            data += '&_method=PUT';
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(res){
                    if(res.success){
                        $('#exampleModal2').modal('hide');
                        window.LaravelDataTables['tabelUser'].ajax.reload(null, false);
                        alert('User berhasil diupdate!');
                    } else if(res.message){
                        alert(res.message);
                    }
                },
                error: function(xhr){
                    let msg = 'Gagal update data!';
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
        // Hapus User AJAX
        $(document).on('click', '.btnHapus', function(){
            if(confirm('Yakin hapus user ini?')){
                let id = $(this).data('id');
                $.ajax({
                    url: '/user/'+id,
                    type: 'POST',
                    data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
                    success: function(res){
                        if(res.success){
                            window.LaravelDataTables['tabelUser'].ajax.reload(null, false);
                            alert('User berhasil dihapus!');
                        } else if(res.message){
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
    });
    </script>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi semua select2 di luar modal (misal: di halaman Buat Surat)
            $('.select2, .select-example, .select-basic, .select-1').select2({
                width: '100%',
                dropdownAutoWidth: true,
                placeholder: 'Pilih opsi',
                allowClear: true,
                dropdownParent: $('#exampleModal2')
            });

            // Inisialisasi Select2 khusus saat modal Tambah User muncul
            $('#exampleModal').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({
                    dropdownParent: $('#exampleModal'),
                    width: '100%',
                    placeholder: 'Pilih Unit Kerja',
                    allowClear: true
                });
            });

            // Inisialisasi Select2 khusus untuk semua modal edit user
            $('div[id^="editUserModal"]').on('shown.bs.modal', function() {
                $(this).find('.select2').select2({
                    dropdownParent: $(this),
                    width: '100%',
                    placeholder: 'Pilih Unit Kerja',
                    allowClear: true
                });
            });
        });
    </script>
@endpush
