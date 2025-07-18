@extends('layout.main')

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
        @include('layout.alert')
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
                                <td>{{ $suratTerkirim->nosurat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Surat</th>
                                <td>{{ $suratTerkirim->jenis->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Surat</th>
                                <td>{{ $suratTerkirim->tgl_surat ? \Carbon\Carbon::parse($suratTerkirim->tgl_surat)->format('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Hal</th>
                                <td>{{ $suratTerkirim->hal ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Sifat</th>
                                <td>
                                    @if($suratTerkirim->sifat == 1)
                                        <span class="badge bg-danger">Penting</span>
                                    @elseif($suratTerkirim->sifat == 2)
                                        <span class="badge bg-warning">Rahasia</span>
                                    @else
                                        <span class="badge bg-info">Biasa</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Klasifikasi</th>
                                <td>{{ $suratTerkirim->kodeklasifikasi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kepada</th>
                                <td>
                                    @php
                                        $kepada = $suratTerkirim->kepada;
                                        $names = [];
                                        if($kepada) {
                                            $arr = is_array($kepada) ? $kepada : json_decode($kepada);
                                            if($arr) {
                                                foreach($arr as $k) {
                                                    $data = is_string($k) ? json_decode($k) : $k;
                                                    if(isset($data->name)) $names[] = $data->name;
                                                }
                                            }
                                        }
                                        echo implode(', ', $names);
                                    @endphp
                                </td>
                            </tr>
                            <tr>
                                <th>Penandatangan</th>
                                <td>{{ $suratTerkirim->ttd_nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($suratTerkirim->status == 1)
                                        <span class="badge bg-warning">Draft</span>
                                    @elseif($suratTerkirim->status == 2)
                                        <span class="badge bg-info">Diproses</span>
                                    @elseif($suratTerkirim->status == 3)
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tembusan</th>
                                <td>{{ $suratTerkirim->tembusan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Isi Surat</th>
                                <td>{!! $suratTerkirim->isi ?? '-' !!}</td>
                            </tr>
                        </table>

                        <div class="mt-3">
                            <a href="{{ route('suratterkirim.cetak', $suratTerkirim->id) }}" target="_blank" class="btn btn-primary btn-sm me-1">Cetak Surat</a>
                            <form action="{{ route('suratterkirim.destroy', $suratTerkirim->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus Surat</button>
                            </form>
                            <a href="{{ route('suratterkirim.index') }}" class="btn btn-secondary btn-sm ms-1">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
