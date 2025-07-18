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
                                    <table id="example1" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Nama</th>
                                                <th>NIP</th>
                                                <th>Satuan Kerja</th>
                                                <th>Email</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($user as $index => $us)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $us->username }}</td>
                                                    <td>{{ $us->fullname }}</td>
                                                    <td>{{ $us->nip }}</td>
                                                    <td>{{ $us->jabatan }}</td>
                                                    <td>{{ $us->email }}</td>
                                                    <td class="d-flex gap-1 flex-wrap">
                                                        <!-- Tombol Edit -->
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editUserModal-{{ $us->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>

                                                        <!-- Modal Edit -->
                                                        <div class="modal fade" id="editUserModal-{{ $us->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="editUserModalLabel-{{ $us->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <form action="{{ route('user.update', $us->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="editUserModalLabel-{{ $us->id }}">
                                                                                Edit User</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Tutup"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            @php
                                                                                $inputFields = [
                                                                                    [
                                                                                        'label' => 'Username',
                                                                                        'name' => 'username',
                                                                                        'type' => 'text',
                                                                                    ],
                                                                                    [
                                                                                        'label' => 'Password',
                                                                                        'name' => 'password',
                                                                                        'type' => 'password',
                                                                                    ],
                                                                                    [
                                                                                        'label' => 'Nama Lengkap',
                                                                                        'name' => 'fullname',
                                                                                        'type' => 'text',
                                                                                    ],
                                                                                    [
                                                                                        'label' => 'NIP',
                                                                                        'name' => 'nip',
                                                                                        'type' => 'text',
                                                                                    ],
                                                                                    [
                                                                                        'label' => 'Pangkat',
                                                                                        'name' => 'pangkat',
                                                                                        'type' => 'text',
                                                                                    ],
                                                                                    [
                                                                                        'label' => 'Jabatan',
                                                                                        'name' => 'jabatan',
                                                                                        'type' => 'text',
                                                                                    ],
                                                                                    [
                                                                                        'label' => 'Email',
                                                                                        'name' => 'email',
                                                                                        'type' => 'email',
                                                                                    ],
                                                                                ];
                                                                            @endphp

                                                                            @foreach ($inputFields as $field)
                                                                                <div class="mb-3 row align-items-center">
                                                                                    <label
                                                                                        class="col-sm-3 col-form-label">{{ $field['label'] }}:</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="{{ $field['type'] }}"
                                                                                            name="{{ $field['name'] }}"
                                                                                            class="form-control"
                                                                                            value="{{ $field['name'] !== 'password' ? $us->{$field['name']} : '' }}"
                                                                                            {{ $field['name'] !== 'password' ? 'required' : '' }}>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach

                                                                            <div class="mb-3 row align-items-center">
                                                                                <label class="col-sm-3 col-form-label">Unit
                                                                                    Kerja:</label>
                                                                                <div class="col-sm-9">
                                                                                    <select name="satkerid"
                                                                                        class="form-select select2"
                                                                                        style="width: 100%;">
                                                                                        <option value="">Pilih Unit
                                                                                            Kerja</option>
                                                                                        @foreach ($masterSatkers as $item)
                                                                                            <option
                                                                                                value="{{ $item->id }}"
                                                                                                {{ $us->satkerid == $item->id ? 'selected' : '' }}>
                                                                                                {{ $item->satker }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Batal</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Simpan</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <!-- Tombol Hapus -->
                                                        <form action="{{ route('user.destroy', $us->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash-alt"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-3">
                                                        <i class="fas fa-inbox"></i> Tidak ada data user.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- Default Card end -->
                </div>
                <!-- Blank end -->
            </div>
    </main>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi semua select2 di luar modal (misal: di halaman Buat Surat)
            $('.select2, .select-example, .select-basic, .select-1').select2({
                width: '100%',
                dropdownAutoWidth: true,
                placeholder: 'Pilih opsi',
                allowClear: true
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

