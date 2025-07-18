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
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Surat</th>
                                            <th>No. Surat</th>
                                            <th>Tgl Surat</th>
                                            <th>Hal</th>
                                            <th>Sifat</th>
                                            <th>Kepada</th>
                                            <th>Penandatangan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($suratTerkirim as $item)
                                        <tr>
                                            <td>{{ ($suratTerkirim->firstItem() ?? 1) + $loop->index }}</td>
                                            <td>{{ $item->jenis->name ?? '-' }}</td>
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
                                                    $kepada = $item->kepada;
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
                                                    <a href="{{ route('suratterkirim.show', $item->id) }}" class="btn btn-info btn-sm b-r-22" title="Detail"><i class="iconoir-eye"></i></a>
                                                    <a href="{{ route('suratterkirim.cetak', $item->id) }}" class="btn btn-secondary btn-sm b-r-22" target="_blank" title="Cetak"><i class="fa fa-file"></i></a>
                                                    <form action="{{ route('suratterkirim.destroy', $item->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm b-r-22" onclick="return confirm('Yakin hapus data?')"><i class="iconoir-trash"></i></button>
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

                            {{-- Pagination area --}}
                            @if ($suratTerkirim instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="d-flex justify-content-between align-items-center pt-3 mt-3">
                                    <div class="text-muted small fw-bold">
                                        Menampilkan {{ $suratTerkirim->firstItem() }} - {{ $suratTerkirim->lastItem() }}
                                        dari {{ $suratTerkirim->total() }} Data
                                    </div>
                                    <div>
                                        {!! $suratTerkirim->links('vendor.pagination.bootstrap-5') !!}
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
