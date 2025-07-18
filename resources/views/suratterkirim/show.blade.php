@extends('layout.main') {{-- Use layout.main or your actual main layout --}}

@section('content')
    <main>
        <div class="container-fluid">
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
        @include('layout.alert') {{-- Include your alert messages --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Detail Surat Terkirim</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>No. Surat</th> {{-- Changed from 'No' to 'No. Surat' --}}
                                <td>{{ $suratTerkirim->nosurat ?? '-' }}</td> {{-- Adjusted to nosurat --}}
                            </tr>
                            <tr>
                                <th>Sifat</th>
                                <td>{{ $suratTerkirim->sifat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis</th>
                                <td>{{ $suratTerkirim->jenis }}</td>
                            </tr>
                            <tr>
                                <th>Hal</th>
                                <td>{{ $suratTerkirim->hal ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Surat</th>
                                <td>{{ $suratTerkirim->tgl_surat ? \Carbon\Carbon::parse($suratTerkirim->tgl_surat)->format('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Klasifikasi</th>
                                <td>{{ $suratTerkirim->klasifikasi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kepada</th>
                            <tr>
                                <th>Kepada</th>
                                <td>{!! $suratTerkirim->kepada_detail !!}</td> {{-- Use accessor, which handles formatting --}}
                            </tr>
                            </tr>
                            <tr>
                                <th>U.P</th>
                                <td>{{ $suratTerkirim->up ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $suratTerkirim->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>{{ $suratTerkirim->jabatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Satker</th>
                                <td>{{ $suratTerkirim->satker ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tembusan</th>
                                <td>{{ $suratTerkirim->tembusan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Referensi</th>
                                <td>{{ $suratTerkirim->referensi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Penandatangan</th>
                                <td>{{ $suratTerkirim->penandatangan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>File Lampiran</th>
                                <td>
                                    @if ($suratTerkirim->file_lampiran)
                                        <a href="{{ asset('storage/' . $suratTerkirim->file_lampiran) }}"
                                            target="_blank">Lihat File</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <div class="mt-3">
                            <a href="{{ route('suratterkirim.edit', $suratTerkirim->id) }}"
                                class="btn btn-warning btn-sm me-1">Edit Surat</a> {{-- Added Edit button --}}
                            <a href="{{ route('suratterkirim.cetak', $suratTerkirim->id) }}" target="_blank"
                                class="btn btn-primary btn-sm me-1">Cetak Surat</a>

                            <form action="{{ route('suratterkirim.destroy', $suratTerkirim->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus Surat</button>
                            </form>
                            <a href="{{ route('suratterkirim.index') }}" class="btn btn-secondary btn-sm ms-1">Kembali</a>
                            {{-- Back button --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
@endsection
