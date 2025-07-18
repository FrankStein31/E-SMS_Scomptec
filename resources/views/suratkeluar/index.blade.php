@extends('layout.main') {{-- Ensure 'layout.main' loads Bootstrap 5 & Font Awesome --}}

@section('content')
    <main>
        <div class="container-fluid">
            <div class="row m-1">
                <div class="col-12">
                    <h4 class="main-title">Daftar Surat Keluar</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="#">
                                <span>
                                    Home
                                </span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Surat Keluar</a>
                        </li>
                    </ul>
                </div>
            </div>

            @include('layout.alert') {{-- Include your alert messages --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>List Surat Keluar</h5>
                                </div>
                                <div class="col text-end d-flex justify-content-end align-items-center">
                                    <label for="filter" class="form-label mb-0 me-2">Tampilkan Surat:</label>
                                    <select id="filter" class="form-select form-select-sm w-auto me-2">
                                        <option value="surat_saya">Surat Saya</option>
                                        <option value="semua_surat">Semua Surat</option>
                                    </select>
                                    <button class="btn btn-primary btn-sm"><i class="fas fa-print me-1"></i> Cetak</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" scope="col">No</th>
                                            <th rowspan="2" scope="col">No. Surat</th>
                                            <th rowspan="2" scope="col">Sifat</th>
                                            <th rowspan="2" scope="col">Jenis</th>
                                            <th rowspan="2" scope="col">Hal</th>
                                            <th rowspan="2" scope="col">Tgl. Surat</th>
                                            <th rowspan="2" scope="col">Klasifikasi</th>
                                            <th rowspan="2" scope="col">Kepada</th>
                                            <th colspan="3" scope="col"
                                                style="text-align: center; padding-left: 20px;">Yang Memfinalkan</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Jabatan</th>
                                            <th scope="col">Satker</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($suratKeluar as $index => $surat)
                                            <tr class="clickable-row" data-id="{{ $surat['id'] ?? '' }}">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $surat['no_surat'] ?? '-' }}</td>
                                                <td>{{ $surat['sifat'] ?? '-' }}</td> {{-- Removed badge for simplicity --}}
                                                <td>{{ $surat['jenis'] ?? '-' }}</td>
                                                <td>{{ $surat['hal'] ?? '-' }}</td>
                                                <td>{{ $surat['tgl_surat'] ?? '-' }}</td>
                                                <td>{{ $surat['klasifikasi'] ?? '-' }}</td>
                                                <td>{{ $surat['kepada'] ?? '-' }}</td>
                                                <td>{{ $surat['nama_final'] ?? '-' }}</td>
                                                <td>{{ $surat['jabatan_final'] ?? '-' }}</td>
                                                <td>{{ $surat['satker_final'] ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-4 text-muted">Data surat keluar
                                                    tidak ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination area --}}
                            @if ($suratKeluar instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <nav aria-label="Page navigation">
                                        {{ $suratKeluar->links('pagination::bootstrap-5') }}
                                    </nav>
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ $suratKeluar->firstItem() }} - {{ $suratKeluar->lastItem() }} dari
                                        {{ $suratKeluar->total() }} Data
                                    </div>
                                </div>
                            @else
                                {{-- Fallback for when pagination is not used but still want a footer --}}
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ count($suratKeluar) }} Data
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
            If you want rows to be clickable, uncomment this
            $('.clickable-row').click(function() {
                window.location = $(this).data('href');
            });
        });
    </script>
@endpush
