@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <div class="row m-1">
                <div class="col-12 ">
                    <h4 class="main-title">Daftar Entri Surat</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Entri Surat</a>
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
                                    <h5>List Entri Surat</h5>
                                </div>
                                <div class="col text-end">
                                    <a href="{{ route('entrisurat.create') }}" class="btn btn-primary btn-sm b-r-22">
                                        <i class="iconoir-plus"></i> Tambah Entri Surat
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">No. Agenda</th>
                                            <th scope="col">Sifat</th>
                                            <th scope="col">Jenis</th>
                                            <th scope="col">No. Surat</th>
                                            <th scope="col">Dari</th>
                                            <th scope="col">Tujuan</th>
                                            <th scope="col">Hal</th>
                                            <th scope="col">Unit Pengentri</th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                            <tr data-href='{{ route('entrisurat.show', $item->id) }}' class="clickable-row">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->noagenda ?? '-' }}</td>
                                                <td>{{ sifatSurat($item->sifat) ?? '-' }}</td>
                                                <td>{{ $item->jenis->name ?? '-' }}</td>
                                                <td>{{ $item->nomor_surat ?? '-' }}</td>
                                                <td>{{ $item->dari ?? '-' }}</td>
                                                <td>{{ $item->kepada ?? '-' }}</td>
                                                <td>{{ $item->hal ?? '-' }}</td>
                                                <td>{{ $item->createdby->fullname ?? '-' }}</td>
                                                <td>{{ $item->tgl_surat ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('entrisurat.edit', $item->id) }}"
                                                        class="btn btn-primary btn-sm">Edit</a>
                                                    <form action="{{ route('entrisurat.destroy', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus entri surat ini?')">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-4 text-muted">Data entri surat
                                                    tidak ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination area --}}
                            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <nav aria-label="Page navigation">
                                        {{ $data->links('pagination::bootstrap-5') }}
                                    </nav>
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari
                                        {{ $data->total() }} Data
                                    </div>
                                </div>
                            @else
                                {{-- Fallback for when pagination is not used but still want a footer --}}
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ count($data) }} Data
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
            $('.clickable-row').click(function() {
                window.location = $(this).data('href');
            });
        });
    </script>
@endpush
