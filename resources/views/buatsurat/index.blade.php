@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h5 class="main-title">Daftar Surat</h5>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="#">
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Daftar Surat</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        @include('layout.alert')

        <!-- Blank start -->
        <div class="row">
            <!-- Default Card start -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h5>Daftar Surat</h5>
                            </div>
                            <div class="col text-end">
                                <a href="{{ route('buatsurat.create') }}" class="btn btn-primary btn-sm b-r-22">
                                    <i class="iconoir-plus"></i> Buat Surat
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
                                        <th scope="col">Jenis Surat</th>
                                        <th scope="col">No. Surat</th>
                                        <th scope="col">Tgl Surat</th>
                                        <th scope="col">Hal</th>
                                        <th scope="col">Sifat</th>
                                        <th scope="col">Kepada</th>
                                        <th scope="col">Penandatangan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($suratKeluar as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->jenis->name }}</td>
                                        <td>{{ $item->nosurat }}</td>
                                        <td>{{ $item->tgl_surat }}</td>
                                        <td>{{ $item->hal }}</td>
                                        <td>
                                            @if($item->sifat == 1)
                                                <span class="badge bg-danger">Penting</span>
                                            @elseif($item->sifat == 2)
                                                <span class="badge bg-warning">Rahasia</span>
                                            @else
                                                <span class="badge bg-info">Biasa</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $kepada = json_decode($item->kepada);
                                                $names = [];
                                                foreach($kepada as $k) {
                                                    $data = json_decode($k);
                                                    $names[] = $data->name;
                                                }
                                                echo implode(', ', $names);
                                            @endphp
                                        </td>
                                        <td>{{ $item->ttd_nama }}</td>
                                        <td>
                                            @if($item->status == 1)
                                                <span class="badge bg-warning">Draft</span>
                                            @elseif($item->status == 2)
                                                <span class="badge bg-info">Diproses</span>
                                            @elseif($item->status == 3)
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('buatsurat.show', $item->id) }}" 
                                                    class="btn btn-info btn-sm b-r-22">
                                                    <i class="iconoir-eye"></i>
                                                </a>
                                                <a href="{{ route('buatsurat.edit', $item->id) }}" 
                                                    class="btn btn-warning btn-sm b-r-22">
                                                    <i class="iconoir-edit"></i>
                                                </a>
                                                <form action="{{ route('buatsurat.destroy', $item->id) }}" 
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm b-r-22" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">
                                                        <i class="iconoir-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data</td>
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
