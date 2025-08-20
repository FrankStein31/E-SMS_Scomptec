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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="shortcut icon" type="image/x-icon"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    <!-- Summernote CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    
    <!-- CodeMirror CSS -->
    <link rel="stylesheet" href="{{ asset('dist/plugins/codemirror/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/plugins/codemirror/theme/monokai.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dist/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Theme style -->
    <!-- <link rel="stylesheet" href="{{ asset('dist/dist/css/adminlte.min.css') }}"> -->
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Poppins Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body,
        html,
        .app-wrapper,
        .app-content,
        .table,
        .card,
        .btn,
        .form-control,
        .form-label,
        .form-select,
        .footer-text,
        .navbar,
        .dropdown-menu,
        .modal-content,
        .alert,
        .dataTables_wrapper,
        .select2-container,
        .select2-selection,
        .select2-results__option {
            font-family: 'Poppins', Arial, sans-serif !important;
            font-size: 0.95rem !important;
            font-weight: 400;
        }

        .table th,
        .table td,
        .form-label,
        .form-control,
        .form-select,
        .btn,
        .card-header,
        .card-title,
        .navbar,
        .dropdown-menu,
        .modal-content,
        .alert,
        .footer-text,
        .select2-selection__rendered,
        .select2-results__option {
            font-family: 'Poppins', Arial, sans-serif !important;
            font-size: 0.95rem !important;
            font-weight: 400;
        }

        .table th {
            font-weight: 500 !important;
        }

        .fw-bold {
            font-weight: 600 !important;
        }
    </style>

    <!-- Fix untuk tabel DataTables agar bisa di-klik dan di-select -->
    <style>
        .dataTables_wrapper table,
        .dataTables_wrapper table tbody,
        .dataTables_wrapper table tbody tr,
        .dataTables_wrapper table tbody tr td {
            user-select: text !important;
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
            pointer-events: auto !important;
            cursor: text !important;
        }

        .dataTables_wrapper table tbody tr:hover {
            background-color: #f8f9fa !important;
            cursor: pointer !important;
        }

        .dataTables_wrapper table tbody tr td {
            cursor: text !important;
        }

        /* Pastikan link dan button tetap bisa di-klik */
        .dataTables_wrapper table tbody tr td a,
        .dataTables_wrapper table tbody tr td button,
        .dataTables_wrapper table tbody tr td .btn {
            cursor: pointer !important;
            pointer-events: auto !important;
            user-select: none !important;
        }

        /* Override untuk elemen yang seharusnya tidak bisa di-select */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
        }

        /* Fix untuk semua tabel di aplikasi */
        .table,
        .table tbody,
        .table tbody tr,
        .table tbody tr td {
            user-select: text !important;
            -webkit-user-select: text !important;
            -moz-user-select: text !important;
            -ms-user-select: text !important;
            pointer-events: auto !important;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        .table tbody tr td {
            cursor: text !important;
        }

        /* Pastikan elemen interaktif tetap berfungsi */
        .table tbody tr td a,
        .table tbody tr td button,
        .table tbody tr td .btn {
            cursor: pointer !important;
            pointer-events: auto !important;
            user-select: none !important;
        }
    </style>

    <title>E-SMS Sistem Manajemen Surat</title>

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
                                        <p class="mb-0">Copyright © 2025. E-SMS Sistem Manajemen Surat</p>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="{{ asset('js/app.js') }}"></script> -->
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    
    <!-- CodeMirror JS -->
    <script src="{{ asset('dist/plugins/codemirror/codemirror.js') }}"></script>
    <script src="{{ asset('dist/plugins/codemirror/mode/xml/xml.js') }}"></script>
    <script src="{{ asset('dist/plugins/codemirror/mode/javascript/javascript.js') }}"></script>
    <script src="{{ asset('dist/plugins/codemirror/mode/css/css.js') }}"></script>
    <script src="{{ asset('dist/plugins/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>
    
    @stack('scripts') {{-- PENTING! Untuk script dari child view, seperti TinyMCE --}}
    <script>
        $(function() {
            // Summernote - only if element exists
            if ($('#summernote').length) {
                $('#summernote').summernote();
            }

            // CodeMirror - only if element exists
            if (document.getElementById("codeMirrorDemo")) {
                SafeDOM.codeMirror("codeMirrorDemo", {
                    mode: "htmlmixed",
                    theme: "monokai",
                    lineNumbers: true,
                    autoCloseTags: true,
                    lineWrapping: true
                });
            }
        })
    </script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('dist/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dist/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- Page specific script - now handled by plugin-init.js -->

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</body>

</html>
