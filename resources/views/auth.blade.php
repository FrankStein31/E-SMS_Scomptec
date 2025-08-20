<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Multipurpose, super flexible, powerful, clean modern responsive bootstrap 5 admin template"
        name="description">
    <meta
        content="admin template, axelit admin template, dashboard template, flat admin template, responsive admin template, web app"
        name="keywords">
    <meta content="la-themes" name="author">
    <!-- <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="shortcut icon" type="image/x-icon"> -->

    <title>E-SMs Sistem Manajemen Surat</title>

    <!--font-awesome-css-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com/" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&amp;display=swap"
        rel="stylesheet">

    <!-- iconoir icon css  -->
    <link href="{{ asset('assets/vendor/ionio-icon/css/iconoir.css') }}" rel="stylesheet">

    <!-- tabler icons-->
    <link href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css">

    <!-- Bootstrap css-->
    <link href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- App css-->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet" type="text/css">

</head>

<body class="sign-in-bg">
    <div class="app-wrapper d-block">
        <div class="main-container">
            <!-- Body main section starts -->
            <div class="container">
                <div class="row sign-in-content-bg">
                    <div class="col-lg-6 image-contentbox d-none d-lg-block">
                        <div class="form-container ">
                            {{-- <div class="signup-content mt-4">
                                <span>
                                    <img alt="" class="img-fluid " src="../assets/images/logo/1.png">
                                </span>
                            </div> --}}

                            <div class="signup-bg-img">
                                <img alt="" class="img-fluid" src="../assets/images/login/01.png">
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6 form-contentbox">
                        <div class="form-container">
                            <form action="{{ route('post.login') }}" method="POST" class="app-form rounded-control">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-5 text-center text-lg-start">
                                            <a href="/">
                                                <h2 class="text-primary-dark f-w-600">Welcome To Esms! </h2>
                                            </a>
                                            <p>Sign in with your data that you enterd during your registration</p>
                                        </div>
                                    </div>

                                    @if(session('error'))
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    </div>
                                    @endif

                                    @if(session('success'))
                                    <div class="col-12">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="username">Username</label>
                                            <input class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Enter Your Username"
                                                type="text" value="{{ old('username') }}">
                                            @error('username')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="password">Password</label>
                                            {{-- <a class="link-primary-dark float-end" href="password_reset.html">Forgot Password
                                            ?</a> --}}
                                            <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter Your Password"
                                                type="password">
                                            @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-light-primary w-100">LOGIN</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Body main section ends -->
        </div>
    </div>
    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Safe DOM utilities -->
    <script src="{{ asset('assets/js/safe-dom.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <!-- Auth specific js-->
    <script src="{{ asset('assets/js/auth.js') }}"></script>

</body>

</html>
