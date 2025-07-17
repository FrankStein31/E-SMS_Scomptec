@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <div class="row m-1">
                <div class="col-12">
                    <h4 class="main-title">Daftar Surat Terkirim</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="{{ url('/') }}">
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Surat Terkirim</a>
                        </li>
                    </ul>
                </div>
            </div>
            @include('layout.alert')

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>List Surat Terkirim</h5>
                                </div>
                                <div class="col text-end">
                                    {{-- Uncomment and adjust route if needed --}}
                                    {{-- <a href="{{ route('suratterkirim.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Tambah Surat Terkirim
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
                                            <th scope="col">Sifat</th>
                                            <th scope="col">Jenis</th>
                                            <th scope="col">Hal</th>
                                            <th scope="col">Tgl. Surat</th>
                                            <th scope="col">Klasifikasi</th>
                                            <th scope="col">Kepada</th>
                                            <th scope="col">U.P</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($suratTerkirim as $surat)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $surat->sifat ?? '-' }}</td>
                                                <td>{{ $surat->jenis ?? '-' }}</td>
                                                <td>{{ $surat->hal ?? '-' }}</td>
                                                <td>{{ $surat->tgl_surat ?? '-' }}</td>
                                                <td>{{ $surat->klasifikasi ?? '-' }}</td>
                                                <td>{{ $surat->kepada ?? '-' }}</td>
                                                <td>{{ $surat->up ?? '-' }}</td>
                                                <td>
                                                    @if (isset($surat->id))
                                                        <a href="{{ route('suratterkirim.show', $surat->id) }}"
                                                            class="btn btn-info btn-sm me-1" title="Detail Surat">
                                                            Detail
                                                        </a>
                                                        <a href="{{ route('suratterkirim.cetak', $surat->id) }}"
                                                            target="_blank" class="btn btn-warning text-dark btn-sm me-1"
                                                            title="Cetak Surat">
                                                            Cetak
                                                        </a>
                                                        <form action="{{ route('suratterkirim.destroy', $surat->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Apakah yakin ingin menghapus surat ini?')"
                                                            title="Hapus Surat">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">Aksi tidak tersedia</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">
                                                    <i class="fas fa-folder-open me-2"></i> Belum ada surat terkirim.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination area --}}
                            @if ($suratTerkirim instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <nav aria-label="Page navigation">
                                        {{ $suratTerkirim->links('pagination::bootstrap-5') }}
                                    </nav>
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ $suratTerkirim->firstItem() }} - {{ $suratTerkirim->lastItem() }}
                                        dari {{ $suratTerkirim->total() }} Data
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ count($suratTerkirim) }} Data
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
    <script>
        $(document).ready(function() {
            $('.clickable-row').click(function(event) {
                if ($(event.target).is('a') || $(event.target).is('button') || $(event.target).closest(
                        'form').length) {
                    return;
                }

                var id = $(this).data('id');
                if (id) {
                    window.location.href = `/surat-terkirim/${id}/show`;
                }
            });
        });
    </script>
@endpush
