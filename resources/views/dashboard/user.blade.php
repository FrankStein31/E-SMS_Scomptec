@extends('layout.main')

@section('content')
    <main>
        <div class="container-fluid">
            <div class="row m-1">
                <div class="col-12">
                    <h4 class="main-title">Dashboard</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Dashboard</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-xl-3 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h5>Entry Surat</h5>
                                    <h2 class="counter">{{ $total_entry_surat }}</h2>
                                </div>
                                <div class="avatar-45 b-r-10 bg-dark">
                                    <i class="iconoir-document f-s-22"></i> {{-- Ganti icon jika perlu --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- <div class="row">
                <div class="col-sm-6 col-xl-3 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h5>Surat Masuk</h5>
                                    <h2 class="counter">{{ $total_surat_masuk }}</h2>
                                </div>
                                <div class="avatar-45 b-r-10 bg-primary">
                                    <i class="iconoir-mail f-s-22"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="col-sm-6 col-xl-3 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h5>Surat Keluar</h5>
                                    <h2 class="counter">{{ $total_surat_keluar }}</h2>
                                </div>
                                <div class="avatar-45 b-r-10 bg-warning">
                                    <i class="iconoir-send f-s-22"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="col-sm-6 col-xl-3 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h5>Draft Surat</h5>
                                    <h2 class="counter">{{ $total_draft }}</h2>
                                </div>
                                <div class="avatar-45 b-r-10 bg-success">
                                    <i class="iconoir-doc-star f-s-22"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="col-sm-6 col-xl-3 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h5>Disposisi</h5>
                                    <h2 class="counter">{{ $total_disposisi }}</h2>
                                </div>
                                <div class="avatar-45 b-r-10 bg-info">
                                    <i class="iconoir-share f-s-22"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <b>Surat Masuk Terbaru</b>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>No Surat</th>
                                    <th>Perihal</th>
                                    <th>Tanggal</th>
                                    <th>Pengirim</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latest_surat_masuk ?? [] as $s)
                                    <tr>
                                        <td>{{ $s->nomor_surat ?? '-' }}</td>
                                        <td>{{ $s->hal ?? '-' }}</td>
                                        <td>{{ $s->tgl_surat ?? '-' }}</td>
                                        <td>{{ $s->dari ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <b>Surat Keluar Terbaru</b>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>No Surat</th>
                                    <th>Perihal</th>
                                    <th>Tanggal</th>
                                    <th>Penerima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latest_surat_keluar ?? [] as $s)
                                    <tr>
                                        <td>{{ $s->nosurat ?? '-' }}</td>
                                        <td>{{ $s->hal ?? '-' }}</td>
                                        <td>{{ date('Y-m-d', strtotime($s->tgl_surat)) ?? '-' }}</td>
                                        <td>
                                            @php
                                                $penerima = [];
                                                if (!empty($s->kepada)) {
                                                    $arr = is_array($s->kepada) ? $s->kepada : json_decode($s->kepada);
                                                    if ($arr) {
                                                        foreach ($arr as $k) {
                                                            $data = is_string($k) ? json_decode($k) : $k;
                                                            if (isset($data->name)) {
                                                                $jabatan = isset($data->jabatan) ? $data->jabatan : '';
                                                                $penerima[] =
                                                                    $data->name .
                                                                    ($jabatan ? ' (' . $jabatan . ')' : '');
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                            {{ count($penerima) ? implode(', ', $penerima) : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
