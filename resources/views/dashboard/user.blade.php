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
                                <h5>Surat Masuk</h5>
                                <h2 class="counter">{{ $total_surat_masuk }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-primary">
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
                                <h2 class="counter">{{ $total_surat_keluar }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-warning">
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
                                <h5>Draft Surat</h5>
                                <h2 class="counter">{{ $total_draft }}</h2>
                            </div>
                            <div class="avatar-45 b-r-10 bg-success">
                                <i class="iconoir-doc-star f-s-22"></i>
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
@endsection 