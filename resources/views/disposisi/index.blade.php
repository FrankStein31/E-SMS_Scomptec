@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="row m-1">
                <div class="col-12">
                    <h4 class="main-title">Daftar Disposisi</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="{{ url('/') }}">
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="{{ route('disposisi.index') }}">Disposisi</a>
                        </li>
                    </ul>
                </div>
            </div>

            @include('layout.alert')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5>List Disposisi</h5>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalCreateDisposisi">
                                        <i class="fas fa-plus"></i> Tambah Disposisi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" class="mb-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <label for="jenis_id" class="col-form-label">Filter Jenis Surat:</label>
                                    </div>
                                    <div class="col-auto">
                                        <select name="jenis_id" id="jenis_id" class="form-select form-select-sm"
                                            onchange="this.form.submit()">
                                            <option value="">Semua Jenis</option>
                                            @foreach ($jenisSuratMap as $id => $name)
                                                <option value="{{ $id }}" {{ $jenisId == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Agenda</th>
                                            <th>No Surat</th>
                                            <th>Sifat</th>
                                            <th>Jenis</th>
                                            <th>Dari</th>
                                            <th>Tujuan</th>
                                            <th>Hal</th>
                                            <th>Tgl. Surat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($disposisiList as $disposisi)
                                            @php
                                                $surat = $disposisi->suratSumber();
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $surat->noagenda ?? '-' }}</td>
                                                <td>{{ $surat->nomor_surat ?? ($surat->nosurat ?? '-') }}</td>
                                                <td>
                                                    @if ($surat->sifat == 1)
                                                        <span class="badge bg-danger">Penting</span>
                                                    @elseif($surat->sifat == 2)
                                                        <span class="badge bg-warning">Rahasia</span>
                                                    @else
                                                        <span class="badge bg-info">Biasa</span>
                                                    @endif
                                                </td>
                                                <td>{{ $surat->jenis->name ?? '-' }}</td>
                                                <td>
                                                    @if (isset($surat->dari))
                                                        {{ $surat->dari }}
                                                    @elseif(isset($surat->pembuat))
                                                        {{ $surat->pembuat->FullName ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($surat->kepada))
                                                        @if (is_string($surat->kepada))
                                                            {{ $surat->kepada }}
                                                        @elseif(is_array($surat->kepada))
                                                            {{ implode(', ', $surat->kepada) }}
                                                        @else
                                                            @php
                                                                $kepada_ids = explode(',', $disposisi->kepada);
                                                                $nama_kepada = \App\Models\User::whereIn(
                                                                    'id',
                                                                    $kepada_ids,
                                                                )
                                                                    ->pluck('FullName')
                                                                    ->toArray();
                                                            @endphp
                                                            {{ implode(', ', $nama_kepada) }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $surat->hal ?? '-' }}</td>
                                                <td>{{ $surat->tgl_surat ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('disposisi.show', $disposisi->id) }}"
                                                        class="btn btn-primary btn-sm">Detail</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4 text-muted">
                                                    <i class="fas fa-folder-open me-2"></i> Belum ada data disposisi.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                @if ($disposisiList instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    <nav aria-label="Page navigation">
                                        {{ $disposisiList->links('pagination::bootstrap-5') }}
                                    </nav>
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ $disposisiList->firstItem() }} - {{ $disposisiList->lastItem() }}
                                        dari {{ $disposisiList->total() }} Data
                                    </div>
                                @else
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ count($disposisiList) }} Data
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Tambah Disposisi -->
    <div class="modal fade" id="modalCreateDisposisi" tabindex="-1" aria-labelledby="modalCreateDisposisiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('disposisi.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateDisposisiLabel">Tambah Disposisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Pilih Surat -->
                        <div class="mb-3">
                            <label for="entrysurat_id" class="form-label">Pilih Surat</label>
                            <select name="entrysurat_id" id="entrysurat_id" class="form-select" required>
                                <option value="">-- Pilih Surat --</option>
                                @foreach (\App\Models\EntrySuratIsi::orderBy('created_at', 'desc')->get() as $surat)
                                    <option value="entry_{{ $surat->id }}">[Masuk] {{ $surat->nomor_surat ?? '-' }} |
                                        {{ $surat->hal ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kepada" class="form-label">Kepada</label>
                            <select class="form-control select-1" name="kepada[]" id="kepada" multiple required
                                style="min-height: 150px;">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->FullName }} - {{ $user->Jabatan2 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Hal -->
                        <div class="mb-3">
                            <label for="hal" class="form-label">Hal</label>
                            <input type="text" name="hal" id="hal" class="form-control">
                        </div>

                        <!-- Isi -->
                        <div class="mb-3">
                            <label for="isi" class="form-label">Isi Disposisi</label>
                            <textarea name="isi" id="isi" class="form-control"></textarea>
                        </div>

                        <!-- Tindakan -->
                        <div class="mb-3">
                            <label for="tindakan" class="form-label">Tindakan</label>
                            <input type="text" name="tindakan" id="tindakan" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#kepada').select2({
                dropdownParent: $('#modalCreateDisposisi'),
                width: '765'
            });
        });
    </script>
@endpush
@push('css')
    <style>
        #kepada {
            min-height: 150px !important;
        }
    </style>
@endpush
