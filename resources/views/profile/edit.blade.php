@extends('layout.main')

@section('content')
<main>
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h5 class="main-title">Edit Profile</h5>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a class="f-s-14 f-w-500" href="#">
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="active">
                        <a class="f-s-14 f-w-500" href="#">Edit Profile</a>
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
                                <h5>Form Edit Profile</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="post">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Nama Lengkap</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-sm @error('fullname') is-invalid @enderror" 
                                        name="fullname" value="{{ old('fullname', $user->fullname) }}" required>
                                    @error('fullname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Username</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-sm @error('username') is-invalid @enderror" 
                                        name="username" value="{{ old('username', $user->username) }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Jabatan</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-sm" value="{{ $user->jabatan }}" disabled>
                                    <small class="text-muted">Jabatan tidak dapat diubah</small>
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-3">Ubah Password (opsional)</h6>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Password Saat Ini</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="password" class="form-control form-control-sm @error('current_password') is-invalid @enderror" 
                                        name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Password Baru</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="password" class="form-control form-control-sm @error('new_password') is-invalid @enderror" 
                                        name="new_password">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Konfirmasi Password</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="password" class="form-control form-control-sm" 
                                        name="new_password_confirmation">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Default Card end -->
        </div>
        <!-- Blank end -->
    </div>
</main>
@endsection 