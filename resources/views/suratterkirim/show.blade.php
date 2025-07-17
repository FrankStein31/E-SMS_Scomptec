@extends('layouts.app')

@section('content')

<main>
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1"></div>
            <div class="col-12 ">
                <h4 class="main-title">Detail Surat Terkirim</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="{{ route('suratterkirim.index') }}">
                            <span>Surat Terkirim</span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Detail Surat</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->
        @include('layout.alert') {{-- Include your alert messages --}}
        <!-- Detail Surat Terkirim start -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Detail Surat Terkirim</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>No. Surat</th>
                                <td>{{ $suratTerkirim->no_surat }}</td>
                            </tr>
                            <tr>
                                <th>Sifat</th>
                                <td>{{ $suratTerkirim->sifat }}</td>
                            </tr>
                            <tr>
                                <th>Jenis</th>
                                <td>{{ $suratTerkirim->jenis }}</td>
                            </tr>
                            <tr>
                                <th>Hal</th>
                                <td>{{ $suratTerkirim->hal }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Surat</th>
                                <td>{{ $suratTerkirim->tgl_surat }}</td>
                            </tr>
                            <tr>
                                <th>Klasifikasi</th>
                                <td>{{ $suratTerkirim->klasifikasi }}</td>
                            </tr>
                            <tr>
                                <th>Kepada</th>
                                <td>{{ $suratTerkirim->kepada }}</td>
                            </tr>
                            <tr>
                                <th>U.P</th>
                                <td>{{ $suratTerkirim->up }}</td>
                            </tr>
                        </table>

                        <!-- Actions -->
                        <div class="mt-3">
                            <a href="{{ route('suratterkirim.cetak', $suratTerkirim->id) }}" class="btn btn-primary btn-sm">Cetak Surat</a>

                            <!-- Delete Form -->
                            <form action="{{ route('suratterkirim.destroy', $suratTerkirim->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus Surat</button>
                            </form>

                        </div>

                    </div> <!-- End of card body -->
                </div> <!-- End of card -->
            </div> <!-- End of col-md-12 -->
        </div> <!-- End of row -->
    </div> <!-- End of container-fluid -->
</main>
@endsection