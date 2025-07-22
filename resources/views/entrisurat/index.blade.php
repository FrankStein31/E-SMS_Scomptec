@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <div class="row m-1">
                <div class="col-12">
                    <h4 class="main-title">Entri Surat</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="#">
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Daftar Entri Surat</a>
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
                                    <h5>Daftar Entri Surat</h5>
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
                                        @forelse ($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->noagenda }}</td>
                                                <td>
                                                    @if ($item->sifat == 1)
                                                        <span class="badge bg-danger">Penting</span>
                                                    @elseif($item->sifat == 2)
                                                        <span class="badge bg-warning">Rahasia</span>
                                                    @else
                                                        <span class="badge bg-info">Biasa</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->jenis->name }}</td>
                                                <td>{{ $item->nomor_surat }}</td>
                                                <td>{{ $item->dari }}</td>
                                                <td>{{ $item->kepada }}</td>
                                                <td>{{ $item->hal }}</td>
                                                <td>{{ $item->createdby->fullname }}</td>
                                                <td>{{ $item->tgl_surat }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('entrisurat.show', $item->id) }}"
                                                            class="btn btn-info btn-sm b-r-22">
                                                            <i class="iconoir-eye"></i>
                                                        </a>
                                                        {{-- <a href="{{ route('entrisurat.edit', $item->id) }}"
                                                            class="btn btn-warning btn-sm b-r-22">
                                                            <i class="iconoir-edit"></i>
                                                        </a> --}}
                                                        {{-- <form action="{{ route('entrisurat.destroy', $item->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm b-r-22"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus entri surat ini?')">
                                                                <i class="iconoir-trash"></i>
                                                            </button>
                                                        </form> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
