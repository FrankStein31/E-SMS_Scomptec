<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Spica Admin</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('spica-1.0.0/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('spica-1.0.0/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('spica-1.0.0/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('spica-1.0.0/images/favicon.png') }}" />
    <style>
        .back-to-home {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 999;
        }
        .back-to-home a {
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .back-to-home a:hover {
            color: #4f46e5;
        }
    </style>
</head>

<body>
    <div class="back-to-home">
        <a href="/">
            <i class="mdi mdi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
    <div class="container-scroller d-flex">
        <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
            <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
                <div class="row flex-grow">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="auth-form-transparent text-left p-3">
                            <div class="brand-logo">
                                <img src="{{ asset('spica-1.0.0/images/logo.svg') }}" alt="logo">
                            </div>
                            <h4>Welcome back!</h4>
                            <h6 class="font-weight-light">Happy to see you again!</h6>
                            <form method="POST" action="{{ route('login') }}" class="pt-3">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-account-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="email" name="email"
                                            class="form-control form-control-lg border-left-0" id="exampleInputEmail"
                                            placeholder="Username" required autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-lock-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="password" name="password"
                                            class="form-control form-control-lg border-left-0" id="exampleInputPassword"
                                            placeholder="Password" required>
                                    </div>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input">
                                            Keep me signed in
                                        </label>
                                    </div>
                                    <a href="#" class="auth-link text-black">Forgot password?</a>
                                </div>
                                <div class="my-3">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">LOGIN</button>
                                </div>
                                {{-- <div class="mb-2 d-flex">
                                    <button type="button" class="btn btn-facebook auth-form-btn flex-grow mr-1">
                                        <i class="mdi mdi-facebook mr-2"></i>Facebook
                                    </button>
                                    <button type="button" class="btn btn-google auth-form-btn flex-grow ml-1">
                                        <i class="mdi mdi-google mr-2"></i>Google
                                    </button>
                                </div> --}}
                                <div class="text-center mt-4 font-weight-light">
                                    Don't have an account? <a href="{{ route('register') }}"
                                        class="text-primary">Create</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 login-half-bg d-none d-lg-flex flex-row">
                        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy;
                            2018 All rights reserved.</p>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- base:js -->
    <script src="{{ asset('spica-1.0.0/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('spica-1.0.0/js/off-canvas.js') }}"></script>
    <script src="{{ asset('spica-1.0.0/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('spica-1.0.0/js/template.js') }}"></script>
    <!-- endinject -->
</body>

</html>
