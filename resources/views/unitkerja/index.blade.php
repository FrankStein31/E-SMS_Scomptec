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
            <!-- Pesan Error dan Success -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12">
                    <h5 class="main-title">Unit Kerja</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="/">
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Unit Kerja</a>
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
                                            <form action="{{ route('unitkerja.store') }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Unit</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Unit Kerja:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="satker" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2 align-items-center">
                                                            <label class="col-sm-3 col-form-label">Kode Unit:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="kodesatker"
                                                                    class="form-control">
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
                                </div>
                            </div>
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    {{ $dataTable->table() }}
                                    {{-- <table id="example1" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Unit Kerja</th>
                                                <th>Kode Unit</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($unitkerja as $index => $un)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $un->satker }}</td>
                                                    <td>{{ $un->kodesatker }}</td>
                                                    <td class="d-flex gap-1">
                                                        @php
                                                            $childCount = $unitkerja->where('kodesatker', '!=', $un->kodesatker)
                                                                ->filter(fn($item) => str_starts_with($item->kodesatker, $un->kodesatker) && strlen($item->kodesatker) > strlen($un->kodesatker))
                                                                ->count();
                                                        @endphp
                                                        @if ($index < 5)
                                                            <!-- Tombol Edit Disabled -->
                                                            <button type="button" class="btn btn-warning btn-sm" disabled>
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                            <!-- Tombol Hapus Disabled -->
                                                            <button type="button" class="btn btn-danger btn-sm" disabled>
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </button>
                                                        @elseif ($childCount > 0)
                                                            <!-- Tombol Edit -->
                                                            <button type="button" class="btn btn-warning btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editModal-{{ $un->id }}">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                            <!-- Tombol Hapus Disabled dengan tooltip -->
                                                            <button type="button" class="btn btn-danger btn-sm" disabled
                                                                title="Hapus child-nya dulu">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </button>
                                                        @else
                                                            <!-- Tombol Edit -->
                                                            <button type="button" class="btn btn-warning btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editModal-{{ $un->id }}">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                            <!-- Modal Edit -->
                                                            <div class="modal fade" id="editModal-{{ $un->id }}"
                                                                tabindex="-1"
                                                                aria-labelledby="editModalLabel-{{ $un->id }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <form action="{{ route('unitkerja.update', $un->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="editModalLabel-{{ $un->id }}">
                                                                                    Edit Unit Kerja</h5>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Tutup"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="mb-3 row">
                                                                                    <label class="col-sm-3 col-form-label">Unit
                                                                                        Kerja:</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="text" name="satker"
                                                                                            class="form-control"
                                                                                            value="{{ $un->satker }}"
                                                                                            required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="mb-3 row">
                                                                                    <label class="col-sm-3 col-form-label">Kode
                                                                                        Satker:</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="text"
                                                                                            name="kodesatker"
                                                                                            class="form-control"
                                                                                            value="{{ $un->kodesatker }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">Batal</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-primary">Simpan
                                                                                    Perubahan</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <!-- Tombol Hapus -->
                                                            <form action="{{ route('unitkerja.destroy', $un->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-trash"></i> Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-3">
                                                        <i class="fas fa-inbox"></i> Tidak ada data unit kerja.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table> --}}
                                </div>
                                <!-- /.card-body -->
                            </div>
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
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
