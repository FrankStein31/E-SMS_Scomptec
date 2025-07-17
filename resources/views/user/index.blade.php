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
                                                                <select id="satkerSelect" name="satkerid"
                                                                    class="form-select select2">
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
                                            <select class="form-select" name="group" id="group"
                                                onchange="this.form.submit()">
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
                                                    <td>
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#exampleModal2">
                                                            Edit
                                                        </button>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="exampleModal2" tabindex="-1"
                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <!-- Form di luar modal-content agar tombol submit terdeteksi -->
                                                                <form action="{{ route('user.update', $us->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="exampleModalLabel">Edit User</h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row mb-2 align-items-center">
                                                                                <label
                                                                                    class="col-sm-3 col-form-label">Username:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="username"
                                                                                        class="form-control"
                                                                                        value="{{ $us->username }}"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-2 align-items-center">
                                                                                <label
                                                                                    class="col-sm-3 col-form-label">Password:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="password" name="password"
                                                                                        class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-2 align-items-center">
                                                                                <label class="col-sm-3 col-form-label">Nama
                                                                                    Lengkap:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="fullname"
                                                                                        class="form-control"
                                                                                        value="{{ $us->fullname }}"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-2 align-items-center">
                                                                                <label
                                                                                    class="col-sm-3 col-form-label">NIP:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="nip"
                                                                                        class="form-control"
                                                                                        value="{{ $us->nip }}"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-2 align-items-center">
                                                                                <label
                                                                                    class="col-sm-3 col-form-label">Pangkat:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="pangkat"
                                                                                        class="form-control"
                                                                                        value="{{ $us->pangkat }}"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-2 align-items-center">
                                                                                <label
                                                                                    class="col-sm-3 col-form-label">Jabatan:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" name="jabatan"
                                                                                        class="form-control"
                                                                                        value="{{ $us->jabatan }}"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-2 align-items-center">
                                                                                <label class="col-sm-3 col-form-label">Unit
                                                                                    Kerja:</label>
                                                                                <div class="col-sm-9">
                                                                                    <select id="satkerSelect"
                                                                                        name="satkerid"
                                                                                        class="form-select select2">
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

                                                                            <div class="row mb-2 align-items-center">
                                                                                <label
                                                                                    class="col-sm-3 col-form-label">Email:</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="email" name="email"
                                                                                        class="form-control"
                                                                                        value="{{ $us->email }}"
                                                                                        required>
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
                                                                    </div> <!-- /.modal-content -->
                                                                </form>
                                                            </div> <!-- /.modal-dialog -->
                                                        </div> <!-- /.modal -->
                                                        <form action="{{ route('user.destroy', $us->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash"></i> Hapus
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
