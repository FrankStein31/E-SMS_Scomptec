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
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <form action="{{ route('user.index') }}" method="GET">
                                        <div class="input-group">
                                            <label class="input-group-text" for="group">User Group</label>
                                            <select class="form-select select2" name="group" id="group"
                                                onchange="this.form.submit()" style="width: 100%;">

                                                <option value="">-- Semua --</option>
                                                @foreach ($userGroups as $ug)
                                                    <option value="{{ $ug }}"
                                                        {{ $group == $ug ? 'selected' : '' }}>{{ $ug }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    {{ $dataTable->table() }}
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
        function editModel(btn) {
            var id = $(btn).data('id');
            const masterSatker = @json($masterSatkers);
            $.get("{{ route('user.index') }}?id=" + id, function(data) {

                $('#username').val(data.username);
                $('#password').val(data.password);
                $('#fullname').val(data.fullname);
                $('#nip').val(data.nip);
                $('#pangkat').val(data.pangkat);
                $('#jabatan').val(data.jabatan);
                $('#satkertest').val(data.satkerid);
                $('#email').val(data.email);

                $('#satkerid2').empty().append('<option value="">-- Pilih Satker --</option>');

                // Tambahkan opsi dari masterSatker
                masterSatker.forEach(function(item) {
                    const isSelected = item.id === data.satkerid ? 'selected' : '';
                    $('#satkerid2').append(`<option value="${item.id}" ${isSelected}>${item.satker}</option>`);
                });

                $('#exampleModal2').find('form').attr('action', "{{ url('user') }}/" + data.id);
                $('#exampleModal2').modal('show');
            });
        }
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

