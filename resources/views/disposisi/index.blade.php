@extends('layout.main') {{-- Assuming your main layout is layout.main --}}

@section('content')
    <main>
        <div class="container-fluid">
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
            @include('layout.alert') {{-- Include your alert messages partial --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5>List Disposisi</h5>
                                </div>
                                <div class="col-auto">
                                    {{-- You might want to add a filter or a 'Tambah Disposisi' button here --}}
                                    {{-- <select class="form-select form-select-sm d-inline-block w-auto me-2">
                                        <option value="all">Semua Disposisi</option>
                                        <option value="saya">Disposisi Saya</option>
                                    </select>
                                    <button class="btn btn-secondary btn-sm">Cetak</button> --}}
                                    {{-- <a href="{{ route('disposisi.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Tambah Disposisi
                                    </a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">No Agenda</th>
                                            <th scope="col">No Surat</th>
                                            <th scope="col">Sifat</th>
                                            <th scope="col">Jenis</th>
                                            <th scope="col">Dari</th>
                                            <th scope="col">Tujuan</th>
                                            <th scope="col">Hal</th>
                                            <th scope="col">Tgl. Surat</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($disposisiList as $disposisi)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $disposisi->no_agenda ?? '-' }}</td>
                                                <td>{{ $disposisi->no_surat ?? '-' }}</td>
                                                <td>{{ $disposisi->sifat ?? '-' }}</td>
                                                <td>{{ $disposisi->jenis ?? '-' }}</td>
                                                <td>{{ $disposisi->dari ?? '-' }}</td>
                                                <td>{{ $disposisi->tujuan ?? '-' }}</td>
                                                <td>{{ $disposisi->hal ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($disposisi->tgl_surat)->format('d/m/Y') ?? '-' }}
                                                </td>
                                                <td>
                                                    {{-- Action button --}}
                                                    <a href="{{ route('disposisi.show', $disposisi->id) }}"
                                                        class="btn btn-info btn-sm" title="Detail Disposisi">
                                                        Detail
                                                    </a>
                                                    {{-- Add other action buttons like Edit, Delete if needed --}}
                                                    {{-- <a href="{{ route('disposisi.edit', $disposisi->id) }}" class="btn btn-warning btn-sm me-1">Edit</a>
                                                    <form action="{{ route('disposisi.destroy', $disposisi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah yakin ingin menghapus disposisi ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form> --}}
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

                            {{-- Pagination area --}}
                            @if ($disposisiList instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <nav aria-label="Page navigation">
                                        {{ $disposisiList->links('pagination::bootstrap-5') }}
                                    </nav>
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ $disposisiList->firstItem() }} - {{ $disposisiList->lastItem() }}
                                        dari {{ $disposisiList->total() }} Data
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ count($disposisiList) }} Data
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('js')
    {{-- Optional: Add any specific JavaScript for this page --}}
@endpush
