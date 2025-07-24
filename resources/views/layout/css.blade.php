<!--font-awesome-css-->
<link href="{{ asset('assets/vendor/fontawesome/css/all.css') }}" rel="stylesheet">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/" rel="preconnect">
<link crossorigin href="https://fonts.gstatic.com/" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&amp;display=swap"
    rel="stylesheet">

<!-- iconoir icon css  -->
<link href="{{ asset('assets/vendor/ionio-icon/css/iconoir.css') }}" rel="stylesheet">

<!-- tabler icons-->
{{-- <link href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css"> --}}

<!--animation-css-->
<link href="{{ asset('assets/vendor/animation/animate.min.css') }}" rel="stylesheet">

<!--flag Icon css-->
<link href="{{ asset('assets/vendor/flag-icons-master/flag-icon.css') }}" rel="stylesheet" type="text/css">

<!-- Bootstrap css-->
<link href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

<!-- simplebar css-->
<link href="{{ asset('assets/vendor/simplebar/simplebar.css') }}" rel="stylesheet" type="text/css">

<!-- Selecrt css -->
<link href="{{ asset('assets/vendor/select/select2.min.css') }}" rel="stylesheet" type="text/css">

<!-- App css-->
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

<!-- Responsive css-->
<link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet" type="text/css">

<!-- Custom Navigation CSS -->
<style>
    /* Simple and clean collapse behavior */
    .main-nav .collapse {
        display: none;
        transition: all 0.3s ease;
    }
    
    .main-nav .collapse.show {
        display: block;
    }
    
    /* Ensure menu toggles work properly */
    .main-nav > li > a[href^="#"] {
        cursor: pointer;
        user-select: none;
    }
    
    /* Active menu styling */
    .main-nav .collapse li a.active {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
        border-radius: 0.375rem;
    }
    
    /* Prevent any Bootstrap interference */
    .main-nav .collapse.collapsing {
        display: none !important;
    }
</style>
