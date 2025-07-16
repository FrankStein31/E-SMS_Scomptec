@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12">
                    <h5 class="main-title">Daftar Draft Surat</h5>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="#">
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Draft Surat</a>
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
                                    <a href="{{ route('draft_surat.create') }}" class="btn btn-primary btn-sm b-r-22">
                                    <i class="iconoir-plus"></i> Tambah Draft Surat
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
                                            <th scope="col">Sifat</th>
                                            <th scope="col">Jenis</th>
                                            <th scope="col">Hal</th>
                                            <th scope="col">Tgl. Surat</th>
                                            <th scope="col">Klasifikasi</th>
                                            <th scope="col">Kepada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($drafts as $index => $draft)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $draft['Sifat'] }}</td>
                                                <td>{{ $draft['Jenis'] }}</td>
                                                <td>{{ $draft['Hal'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="fas fa-inbox me-2 text-center"></i> Belum ada draft surat yang
                                                    tersedia.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Default Card end -->
            </div>
            <!-- Blank end -->
        </div>
    </main>
@endsection
