@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Dashboard Admin</h4>
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
                                <h5>Total Users</h5>
                                <h2 class="counter">{{ $total_users }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-primary">
                                <i class="iconoir-user f-s-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h5>Total Admin</h5>
                                <h2 class="counter">{{ $total_admin }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-warning">
                                <i class="iconoir-shield-check f-s-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h5>Total User Biasa</h5>
                                <h2 class="counter">{{ $total_user }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-success">
                                <i class="iconoir-user-circle f-s-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h5>Klasifikasi</h5>
                                <h2 class="counter">{{ $total_klasifikasi ?? 0 }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-info">
                                <i class="iconoir-folder f-s-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h5>Instansi</h5>
                                <h2 class="counter">{{ $total_instansi ?? 0 }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-secondary">
                                <i class="iconoir-building f-s-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h5>Tindakan Disposisi</h5>
                                <h2 class="counter">{{ $total_tindakan ?? 0 }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-danger">
                                <i class="iconoir-check-circle f-s-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h5>Surat Masuk</h5>
                                <h2 class="counter">{{ $total_surat_masuk ?? 0 }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-dark">
                                <i class="iconoir-mail f-s-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h5>Surat Keluar</h5>
                                <h2 class="counter">{{ $total_surat_keluar ?? 0 }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-primary">
                                <i class="iconoir-send f-s-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h5>Surat Terkirim</h5>
                                <h2 class="counter">{{ $total_surat_terkirim ?? 0 }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-success">
                                <i class="iconoir-paper-plane f-s-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <b>User Terbaru</b>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latest_users ?? [] as $u)
                                <tr>
                                    <td>{{ $u->username }}</td>
                                    <td>{{ $u->fullname }}</td>
                                    <td>{{ $u->jabatan }}</td>
                                    <td>{{ $u->satker->satker ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <b>Surat Keluar Terbaru</b>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>No Surat</th>
                                    <th>Perihal</th>
                                    <th>Tanggal</th>
                                    <th>Pembuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latest_surat_keluar ?? [] as $s)
                                <tr>
                                    <td>{{ $s->nosurat }}</td>
                                    <td>{{ $s->hal }}</td>
                                    <td>{{ $s->tgl_surat }}</td>
                                    <td>{{ $s->user->fullname ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection 