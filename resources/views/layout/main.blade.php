<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from phpstack-1384472-5121645.cloudwaysapps.com/template/html/axelit/template/blank.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 03 Jun 2025 03:17:04 GMT -->

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
    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="shortcut icon" type="image/x-icon">
    

    <title>axelit - Premium Admin Template</title>

    @include('layout.css')

    @stack('css')

</head>

<body>
    <div class="app-wrapper">

        <div class="loader-wrapper">
            <div class="app-loader">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Menu Navigation starts -->
        @include('layout.nav')
        <!-- Menu Navigation ends -->


        <div class="app-content">
            
            <div class="">
                <!-- Header Section starts -->
                @include('layout.header')
                <!-- Header Section ends -->

                @yield('content')
                <!-- Body main section ends -->

                <!-- tap on top -->
                <div class="go-top">
                    <span class="progress-value">
                        <i class="ti ti-chevron-up"></i>
                    </span>
                </div>

                <!-- Footer Section starts-->
                <footer>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-9 col-12">
                                <ul class="footer-text">
                                    <li>
                                        <p class="mb-0">Copyright © 2025 axelit. All rights reserved 💖</p>
                                    </li>
                                    <li><a href="#"> V1.0.0 </a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul class="footer-text text-end">
                                    <li><a href="mailto:teqlathemes@gmail.com."> Need Help <i
                                                class="ti ti-help"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- Footer Section ends-->

            </div>
        </div>
    </div>


    <!--customizer-->
    {{-- <div id="customizer"></div> --}}

    @include('layout.js')

    @stack('js')

</body>

</html>
