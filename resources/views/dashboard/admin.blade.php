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
            <div class="col-sm-6 col-xl-4 col-12">
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

            <div class="col-sm-6 col-xl-4 col-12">
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

            <div class="col-sm-6 col-xl-4 col-12">
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
        </div>
    </div>
</main>
@endsection 