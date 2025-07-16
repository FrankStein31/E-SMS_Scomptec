<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ESMS V2</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('spica-1.0.0/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('spica-1.0.0/vendors/css/vendor.bundle.base.css') }}">

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('spica-1.0.0/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('spica-1.0.0/images/favicon.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>

<body>
    <div class="container-scroller d-flex">
        @include('layouts.sidebar')

        <!-- PERBAIKAN DIMULAI DI SINI -->
        <div class="container-fluid page-body-wrapper">
            @include('layouts.navbar')

            <!-- Pindahkan main-panel ke dalam page-body-wrapper -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('contents')
                </div>

                <footer class="footer">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">
                                    Copyright © bootstrapdash.com 2020
                                </span>
                                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">
                                    Distributed By: <a href="https://www.themewagon.com/" target="_blank">ThemeWagon</a>
                                </span>
                                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
                                    Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard
                                        templates</a> from Bootstrapdash.com
                                </span>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
        <!-- PERBAIKAN SELESAI -->
    </div>
    <!-- container-scroller -->

    <!-- base:js -->
    <script src="{{ asset('spica-1.0.0/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('spica-1.0.0/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('spica-1.0.0/js/off-canvas.js') }}"></script>
    <script src="{{ asset('spica-1.0.0/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('spica-1.0.0/js/template.js') }}"></script>
    <script src="{{ asset('spica-1.0.0/js/dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>


</html>
