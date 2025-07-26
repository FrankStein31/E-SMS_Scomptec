<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>E-SMS Scomptec</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --accent: #06b6d4;
            --dark: #1e293b;
            --light: #f8fafc;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        /* Navbar */
        .navbar {
            padding: 20px 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 15px 0;
            background: rgba(255, 255, 255, 0.98);
        }

        .navbar .logo {
            font-size: 32px;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar .nav-link {
            color: var(--dark);
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar .nav-link:hover {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }

        .navbar .nav-link.active {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }

        .navbar .getstarted {
            background: var(--gradient);
            color: white !important;
            border-radius: 50px;
            padding: 12px 30px !important;
            margin-left: 20px;
            font-weight: 600;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .navbar .getstarted:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Dropdown styling for authenticated user */
        .navbar .dropdown-menu {
            border: none;
            box-shadow: var(--shadow);
            border-radius: 15px;
            padding: 10px 0;
            margin-top: 10px;
            min-width: 200px;
        }

        .navbar .dropdown-item {
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 10px;
            margin: 0 10px;
        }

        .navbar .dropdown-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .navbar .dropdown-item.text-danger:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .navbar .dropdown-toggle::after {
            margin-left: 8px;
        }

        /* Hero Section */
        #hero {
            min-height: 100vh;
            padding: 80px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        #hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="grad1" cx="50%" cy="50%" r="50%"><stop offset="0%" style="stop-color:rgba(255,255,255,0.1);stop-opacity:1" /><stop offset="100%" style="stop-color:rgba(255,255,255,0);stop-opacity:1" /></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23grad1)"/><circle cx="800" cy="300" r="150" fill="url(%23grad1)"/><circle cx="400" cy="700" r="80" fill="url(%23grad1)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        #hero .container {
            position: relative;
            z-index: 2;
        }

        #hero h1 {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #hero h2 {
            font-size: 1.2rem;
            font-weight: 400;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            max-width: 600px;
        }

        .hero-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .hero-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .hero-image {
            animation: heroFloat 6s ease-in-out infinite;
        }

        @keyframes heroFloat {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Section styling */
        .section-title {
            text-align: center;
            padding-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient);
            border-radius: 2px;
        }

        .section-title p {
            font-size: 1.2rem;
            color: #64748b;
            font-weight: 400;
        }

        /* Icon boxes */
        .icon-box {
            padding: 40px 30px;
            border-radius: 20px;
            background: white;
            box-shadow: var(--shadow);
            transition: all 0.4s ease;
            height: 100%;
            border: 1px solid rgba(99, 102, 241, 0.1);
            position: relative;
            overflow: hidden;
        }

        .icon-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .icon-box:hover::before {
            transform: scaleX(1);
        }

        .icon-box:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .icon-box i {
            font-size: 48px;
            margin-bottom: 20px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .icon-box h4 {
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.3rem;
            color: var(--dark);
        }

        .icon-box p {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* Features section */
        #features {
            padding: 100px 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
        }

        #features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:rgba(99,102,241,0.05);stop-opacity:1" /><stop offset="100%" style="stop-color:rgba(139,92,246,0.05);stop-opacity:1" /></linearGradient></defs><polygon points="0,0 1000,200 1000,1000 0,800" fill="url(%23grad2)"/></svg>');
        }

        #features .container {
            position: relative;
            z-index: 2;
        }

        /* About section */
        #about {
            padding: 100px 0;
            background: white;
        }

        /* Contact section */
        .contact {
            padding: 100px 0;
            background: var(--light);
        }

        .contact .info {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            height: 100%;
        }

        .contact .info .d-flex {
            padding: 20px;
            border-radius: 15px;
            background: rgba(99, 102, 241, 0.05);
            transition: all 0.3s ease;
        }

        .contact .info .d-flex:hover {
            background: rgba(99, 102, 241, 0.1);
            transform: translateX(10px);
        }

        .contact form {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow);
        }

        .contact .form-control {
            padding: 15px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .contact .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .contact .btn-primary {
            background: var(--gradient);
            border: none;
            padding: 15px 40px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .contact .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Footer */
        footer {
            padding: 60px 0;
            background: var(--dark);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
        }

        footer p {
            font-size: 1.1rem;
            margin-bottom: 0;
            opacity: 0.9;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            #hero {
                padding: 60px 0;
                text-align: center;
            }

            #hero h1 {
                font-size: 2rem;
            }

            #hero h2 {
                font-size: 1rem;
                margin-left: auto;
                margin-right: auto;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .icon-box {
                margin-bottom: 30px;
            }

            .hero-image {
                margin-top: 40px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="logo text-decoration-none" href="/">
                <img alt="#" src="{{ asset('assets/images/logo/esms.png') }}"
                    style="height:48px; width:auto; max-width:180px; object-fit:contain; display:block;">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#hero">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        @auth
                            <div class="dropdown">
                                <a class="nav-link getstarted dropdown-toggle" href="#" role="button"
                                    id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i
                                        class="bi bi-person-circle me-2"></i>{{ auth()->user()->fullname ?? 'Hei! Mau kemanaa?? Sini balik' }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                                    <ul>
                                        <i class="bi me-2"></i>Game
                                    </ul>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="openScratchCard()">
                                            <i class="bi bi-heart-fill me-2"></i>💕 Kartu Keberuntungan
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="openMemoryGame()">
                                            <i class="bi bi-grid-3x3-gap-fill me-2"></i>🧠 Memory Game
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a class="nav-link getstarted" href="{{ route('login') }}">Login</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="animate-on-scroll">Sistem Manajemen Surat Modern dengan E-SMS</h1>
                    <h2 class="animate-on-scroll">Kelola surat masuk dan keluar dengan lebih efisien menggunakan
                        teknologi digital terdepan</h2>
                    @auth
                        <a href="{{ route('dashboard') }}" class="hero-btn animate-on-scroll">
                            <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hero-btn animate-on-scroll">
                            <i class="bi bi-rocket me-2"></i>Get Started
                        </a>
                    @endauth
                </div>
                <div class="col-lg-5">
                    <div class="hero-image animate-on-scroll">
                        <svg width="100%" height="350" viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="heroGrad" x1="0%" y1="0%" x2="100%"
                                    y2="100%">
                                    <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                                </linearGradient>
                                <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
                                    <feDropShadow dx="0" dy="10" stdDeviation="10"
                                        flood-color="rgba(0,0,0,0.2)" />
                                </filter>
                            </defs>
                            <!-- Document -->
                            <rect x="150" y="80" width="200" height="240" rx="10" fill="white"
                                filter="url(#shadow)" />
                            <rect x="170" y="100" width="160" height="8" rx="4" fill="#e2e8f0" />
                            <rect x="170" y="120" width="120" height="6" rx="3" fill="#cbd5e1" />
                            <rect x="170" y="140" width="140" height="6" rx="3" fill="#cbd5e1" />
                            <rect x="170" y="160" width="100" height="6" rx="3" fill="#cbd5e1" />
                            <!-- Envelope -->
                            <polygon points="100,200 200,200 150,240" fill="url(#heroGrad)" filter="url(#shadow)" />
                            <rect x="80" y="200" width="140" height="80" rx="5" fill="white"
                                stroke="url(#heroGrad)" stroke-width="2" />
                            <!-- Digital elements -->
                            <circle cx="400" cy="120" r="30" fill="rgba(255,255,255,0.2)" stroke="white"
                                stroke-width="2" />
                            <circle cx="400" cy="120" r="15" fill="white" />
                            <circle cx="380" cy="280" r="20" fill="rgba(255,255,255,0.2)" stroke="white"
                                stroke-width="2" />
                            <circle cx="380" cy="280" r="10" fill="white" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="section-title animate-on-scroll">
                <h2>About Us</h2>
                <p>Tentang E-SMS Scomptec - Solusi Digital untuk Manajemen Surat Modern</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="icon-box animate-on-scroll">
                        <i class="bi bi-shield-check"></i>
                        <h4>Keamanan Terjamin</h4>
                        <p>Sistem dilengkapi dengan enkripsi end-to-end dan fitur keamanan berlapis untuk melindungi
                            data surat Anda dari ancaman cyber</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="icon-box animate-on-scroll">
                        <i class="bi bi-laptop"></i>
                        <h4>Mudah Digunakan</h4>
                        <p>Interface yang intuitif dan user-friendly dengan desain modern memudahkan pengguna dari
                            berbagai tingkat keahlian teknologi</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="icon-box animate-on-scroll">
                        <i class="bi bi-gear"></i>
                        <h4>Terintegrasi</h4>
                        <p>Terintegrasi seamlessly dengan sistem manajemen dokumen lain untuk workflow yang lebih
                            efisien dan produktif</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="section-title animate-on-scroll">
                <h2>Features</h2>
                <p>Fitur Unggulan E-SMS yang Memudahkan Pekerjaan Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="icon-box animate-on-scroll">
                        <i class="bi bi-envelope-fill"></i>
                        <h4>Manajemen Surat Masuk</h4>
                        <p>Kelola surat masuk dengan sistem kategorisasi otomatis, filter advanced, dan dashboard yang
                            informatif</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="icon-box animate-on-scroll">
                        <i class="bi bi-arrow-left-right"></i>
                        <h4>Disposisi Digital</h4>
                        <p>Proses disposisi surat secara digital dengan approval workflow, tracking status, dan
                            notifikasi real-time</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="icon-box animate-on-scroll">
                        <i class="bi bi-search"></i>
                        <h4>Tracking Surat</h4>
                        <p>Lacak perjalanan surat dengan detail timeline, status update, dan laporan komprehensif untuk
                            audit trail</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="icon-box animate-on-scroll">
                        <i class="bi bi-bell-fill"></i>
                        <h4>Notifikasi Real-time</h4>
                        <p>Dapatkan notifikasi push instant untuk setiap aktivitas penting dengan prioritas yang dapat
                            dikustomisasi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-title animate-on-scroll">
                <h2>Contact</h2>
                <p>Hubungi Kami untuk Informasi Lebih Lanjut</p>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="info animate-on-scroll">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-geo-alt-fill fs-3 me-3 text-primary"></i>
                            <div>
                                <h5 class="mb-1">Location:</h5>
                                <p class="mb-0">Jakarta, Indonesia</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-envelope-fill fs-3 me-3 text-primary"></i>
                            <div>
                                <h5 class="mb-1">Email:</h5>
                                <p class="mb-0">info@scomptec.com</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone-fill fs-3 me-3 text-primary"></i>
                            <div>
                                <h5 class="mb-1">Call:</h5>
                                <p class="mb-0">+62 XXX XXX XXX</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <form class="animate-on-scroll">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="Your Email" required>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" placeholder="Subject" required>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" rows="5" placeholder="Message" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 <strong>E-SMS Scomptec</strong>. All Rights Reserved. Designed with ❤️ for better document
                management.</p>
        </div>
    </footer>

    <!-- Audio for Forever Young -->
    <audio id="foreverYoungAudio" preload="auto" loop>
        <!-- Forever Young MP3 -->
        <source src="https://feeldreams.github.io/audio/foreveryoung.mp3" type="audio/wav">
        <!-- Alternative romantic melody -->
        <source src="https://actions.google.com/sounds/v1/ambiences/forest_birds.ogg" type="audio/ogg">
        Your browser does not support the audio element.
    </audio>

    <!-- Scratch Card Modal -->
    <div class="modal fade" id="scratchCardModal" tabindex="-1" aria-labelledby="scratchCardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="scratchCardModalLabel">
                        <i class="bi bi-heart-fill me-2"></i>💕 Surprise Card untuk Kamu
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div id="gameContainer">
                        <!-- Stage 1: Welcome -->
                        <div id="stage1" class="game-stage">
                            <div class="sticker-container mb-4">
                                <img src="https://feeldreams.github.io/pandapanah.gif" class="game-sticker" />
                            </div>
                            <h2 class="text-white mb-3">Halo Kamu! 🫢</h2>
                            <p class="text-white-50 mb-4">Aku ada sesuatu special buat kamu nih...</p>
                            <button class="btn btn-light btn-lg rounded-pill px-4" onclick="nextStage(2)">
                                <i class="bi bi-arrow-right me-2"></i>Lanjut
                            </button>
                        </div>

                        <!-- Stage 2: Introduction -->
                        <div id="stage2" class="game-stage d-none">
                            <div class="sticker-container mb-4">
                                <img src="https://feeldreams.github.io/pandahiya.gif" class="game-sticker" />
                            </div>
                            <h2 class="text-white mb-3">Ada Surprise buat Kamu! 🫣</h2>
                            <p class="text-white-50 mb-4">Tapi harus main dulu nih... hihi</p>
                            <button class="btn btn-light btn-lg rounded-pill px-4" onclick="nextStage(3)">
                                <i class="bi bi-gift me-2"></i>Apa itu?
                            </button>
                        </div>

                        <!-- Stage 3: Scratch Game -->
                        <div id="stage3" class="game-stage d-none">
                            <h2 class="text-white mb-3">Gosok Kartu Ini!</h2>
                            <p class="text-white-50 mb-4">
                                Awas kena <span style="color: #ff6b9d;">💣 Bom</span> yaa! 😆
                            </p>
                            <div class="scratch-game-board mx-auto mb-4">
                                <div class="scratch-card" data-emoji="🫶"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="❤️"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="🥳"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="🫣"><canvas></canvas></div>

                                <div class="scratch-card" data-emoji="🤍"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="😍"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="🥰"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="🩷"><canvas></canvas></div>

                                <div class="scratch-card" data-emoji="💖"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="💕"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="💓"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="💗"><canvas></canvas></div>

                                <div class="scratch-card" data-emoji="💝"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="💞"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="💘"><canvas></canvas></div>
                                <div class="scratch-card" data-emoji="💣"><canvas></canvas></div>
                            </div>
                        </div>

                        <!-- Stage 4: Result -->
                        <div id="stage4" class="game-stage d-none">
                            <div class="sticker-container mb-4">
                                <img id="resultSticker" src="https://feeldreams.github.io/weee.gif"
                                    class="game-sticker" />
                            </div>
                            <div id="resultText" class="text-white mb-4">
                                <!-- Result text will be inserted here -->
                            </div>
                            <div id="loveAnimation" class="d-none">
                                <div class="heart-rain"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Scratch Card CSS -->
    <style>
        .game-stage {
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .sticker-container {
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .game-sticker {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            box-shadow: 0 4px 30px rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            background: rgba(255, 255, 255, .2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 10px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .scratch-game-board {
            display: grid;
            grid-template-columns: repeat(4, 60px);
            grid-gap: 15px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .scratch-card {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            animation: cardAppear 0.5s ease forwards;
            opacity: 0;
            transform: scale(0.5);
        }

        .scratch-card:not(.revealed) {
            font-size: 0 !important;
        }

        .scratch-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .scratch-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .scratch-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .scratch-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .scratch-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .scratch-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .scratch-card:nth-child(7) {
            animation-delay: 0.7s;
        }

        .scratch-card:nth-child(8) {
            animation-delay: 0.8s;
        }

        .scratch-card:nth-child(9) {
            animation-delay: 0.9s;
        }

        .scratch-card:nth-child(10) {
            animation-delay: 1.0s;
        }

        .scratch-card:nth-child(11) {
            animation-delay: 1.1s;
        }

        .scratch-card:nth-child(12) {
            animation-delay: 1.2s;
        }

        .scratch-card:nth-child(13) {
            animation-delay: 1.3s;
        }

        .scratch-card:nth-child(14) {
            animation-delay: 1.4s;
        }

        .scratch-card:nth-child(15) {
            animation-delay: 1.5s;
        }

        .scratch-card:nth-child(16) {
            animation-delay: 1.6s;
        }

        @keyframes cardAppear {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .scratch-card canvas {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .scratch-card.revealed {
            font-size: 2em !important;
            color: #2c3e50 !important;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 60px;
            animation: cardReveal 0.5s ease;
        }

        @keyframes cardReveal {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .heart-rain {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
        }

        .heart-fall {
            position: absolute;
            color: #ff6b9d;
            font-size: 20px;
            animation: heartFall linear infinite;
        }

        @keyframes heartFall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        .type-animation {
            border-right: 2px solid white;
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            50% {
                border-color: white;
            }

            51%,
            100% {
                border-color: transparent;
            }
        }
    </style>

    <!-- Memory Game Modal -->
    <div class="modal fade" id="memoryGameModal" tabindex="-1" aria-labelledby="memoryGameModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="memoryGameModalLabel">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i>🧠 Memory Game Challenge
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div id="memoryGameContainer">
                        <!-- Stage 1: Welcome -->
                        <div id="memoryStage1" class="memory-stage">
                            <div class="sticker-container mb-4">
                                <img src="https://htmlku.com/0/panda/hiya.gif" class="game-sticker" />
                            </div>
                            <h2 class="text-white mb-3">Halo Kamu! 🫢</h2>
                            <p class="text-white-50 mb-4">Mari bermain Memory Game bareng aku yuk!</p>
                            <button class="btn btn-light btn-lg rounded-pill px-4" onclick="nextMemoryStage(2)">
                                <i class="bi bi-arrow-right me-2"></i>Ayo Main!
                            </button>
                        </div>

                        <!-- Stage 2: Introduction -->
                        <div id="memoryStage2" class="memory-stage d-none">
                            <div class="sticker-container mb-4">
                                <img src="https://htmlku.com/0/panda/inicinta2.gif" class="game-sticker" />
                            </div>
                            <h2 class="text-white mb-3">Ada Sesuatu Nih 😝</h2>
                            <p class="text-white-50 mb-4">Tapi harus selesaikan Memory Game dulu ya...</p>
                            <button class="btn btn-light btn-lg rounded-pill px-4" onclick="nextMemoryStage(3)">
                                <i class="bi bi-play me-2"></i>Mulai Game
                            </button>
                        </div>

                        <!-- Stage 3: Memory Game -->
                        <div id="memoryStage3" class="memory-stage d-none">
                            <h2 class="text-white mb-3">Selesaikan <span style="color: #ff6b9d;">Memory Game</span></h2>
                            <p class="text-white-50 mb-4">di Bawah Ini yaa! 😆🩷</p>
                            <div class="memory-game-container mx-auto mb-4">
                                <div class="memory-game-board">
                                    <div class="memory-card" data-emoji="🫶"></div>
                                    <div class="memory-card" data-emoji="💞"></div>
                                    <div class="memory-card" data-emoji="🥳"></div>
                                    <div class="memory-card" data-emoji="❤️‍🔥"></div>

                                    <div class="memory-card" data-emoji="💐"></div>
                                    <div class="memory-card" data-emoji="😍"></div>
                                    <div class="memory-card" data-emoji="🥰"></div>
                                    <div class="memory-card" data-emoji="🩷"></div>

                                    <div class="memory-card" data-emoji="🫶"></div>
                                    <div class="memory-card" data-emoji="❤️‍🔥"></div>
                                    <div class="memory-card" data-emoji="💐"></div>
                                    <div class="memory-card" data-emoji="🥰"></div>

                                    <div class="memory-card" data-emoji="💞"></div>
                                    <div class="memory-card" data-emoji="🩷"></div>
                                    <div class="memory-card" data-emoji="🥳"></div>
                                    <div class="memory-card" data-emoji="😍"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Stage 4: Success -->
                        <div id="memoryStage4" class="memory-stage d-none">
                            <div class="sticker-container mb-4">
                                <img id="memoryResultSticker" src="https://htmlku.com/0/panda/pusn3.gif" class="game-sticker" />
                            </div>
                            <div id="memoryResultText" class="text-white mb-4">
                                <h2 class="mb-3">Yeaayy!! 🥳</h2>
                                <p>Aku mau ngomong sesuatu nih 🫣</p>
                                <p>Tunggu bentar yaa~ 😋🤏</p>
                            </div>
                            <div id="memoryLoveAnimation" class="d-none">
                                <div class="heart-rain"></div>
                            </div>
                            <div id="finalMessage" class="mt-4 d-none">
                                <div class="typing-container">
                                    <p class="text-white" id="typingText"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Memory Game CSS -->
    <style>
        .memory-stage {
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .memory-game-container {
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .memory-game-board {
            display: grid;
            grid-template-columns: repeat(4, 60px);
            grid-gap: 15px;
            margin: 0 auto;
        }

        .memory-card {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            animation: memoryCardAppear 0.5s ease forwards;
            opacity: 0;
            transform: scale(0);
            font-size: 0;
        }

        .memory-card:nth-child(1) { animation-delay: 0.1s; }
        .memory-card:nth-child(2) { animation-delay: 0.2s; }
        .memory-card:nth-child(3) { animation-delay: 0.3s; }
        .memory-card:nth-child(4) { animation-delay: 0.4s; }
        .memory-card:nth-child(5) { animation-delay: 0.5s; }
        .memory-card:nth-child(6) { animation-delay: 0.6s; }
        .memory-card:nth-child(7) { animation-delay: 0.7s; }
        .memory-card:nth-child(8) { animation-delay: 0.8s; }
        .memory-card:nth-child(9) { animation-delay: 0.9s; }
        .memory-card:nth-child(10) { animation-delay: 1.0s; }
        .memory-card:nth-child(11) { animation-delay: 1.1s; }
        .memory-card:nth-child(12) { animation-delay: 1.2s; }
        .memory-card:nth-child(13) { animation-delay: 1.3s; }
        .memory-card:nth-child(14) { animation-delay: 1.4s; }
        .memory-card:nth-child(15) { animation-delay: 1.5s; }
        .memory-card:nth-child(16) { animation-delay: 1.6s; }

        @keyframes memoryCardAppear {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .memory-card.flipped {
            font-size: 1.8em !important;
            background: rgba(255, 255, 255, 0.9);
            color: #2c3e50;
            animation: memoryCardFlip 0.5s ease;
        }

        @keyframes memoryCardFlip {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .memory-card.matched {
            background: rgba(144, 238, 144, 0.8);
            cursor: default;
        }

        .typing-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .typing-effect::after {
            content: '|';
            animation: blink 1s infinite;
        }

        /* Memory Game Click Effect */
        .memory-click-effect {
            position: fixed;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.8);
            pointer-events: none;
            transform: translate(-50%, -50%) scale(1);
            animation: memoryClickAnimation 0.3s ease-out forwards;
            z-index: 9999;
        }

        @keyframes memoryClickAnimation {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(2);
                opacity: 0;
            }
        }
    </style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animate on scroll
        function animateOnScroll() {
            const elements = document.querySelectorAll('.animate-on-scroll');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight - 100) {
                    element.classList.add('animated');
                }
            });
        }

        // Initial animation check
        animateOnScroll();

        // Check on scroll
        window.addEventListener('scroll', animateOnScroll);

        // Active nav link highlighting
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.getBoundingClientRect().top;
                const sectionHeight = section.clientHeight;
                if (sectionTop <= 200 && sectionTop + sectionHeight > 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Form submission handler
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Simple form validation and submission feedback
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
            submitBtn.disabled = true;

            // Simulate form submission
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Sent!';
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    this.reset();
                }, 2000);
            }, 2000);
        });

        // Scratch Card Game Logic
        let currentStage = 1;
        let gameComplete = false;


        function openScratchCard() {
            // Acak urutan emoji setiap kali modal dibuka
            const emojis = [
                "🫶", "❤️", "🥳", "🫣",
                "🤍", "😍", "🥰", "🩷",
                "💖", "💕", "💓", "💗",
                "💝", "💞", "💘", "💣"
            ];
            // Fisher-Yates shuffle
            for (let i = emojis.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [emojis[i], emojis[j]] = [emojis[j], emojis[i]];
            }

            // Render ulang scratch card dengan urutan baru
            const board = document.querySelector('.scratch-game-board');
            if (board) {
                board.innerHTML = '';
                emojis.forEach(emoji => {
                    const card = document.createElement('div');
                    card.className = 'scratch-card';
                    card.setAttribute('data-emoji', emoji);
                    const canvas = document.createElement('canvas');
                    card.appendChild(canvas);
                    board.appendChild(card);
                });
            }

            const modal = new bootstrap.Modal(document.getElementById('scratchCardModal'));
            modal.show();

            // Auto-play Forever Young music
            const audio = document.getElementById('foreverYoungAudio');
            if (audio) {
                audio.volume = 0.4; // Set volume to 40%

                // Force play the audio
                audio.play().then(() => {
                    console.log('🎵 Forever Young started playing!');
                }).catch((error) => {
                    console.log('Audio blocked by browser, trying alternative method:', error);

                    // Alternative method - play on any user interaction
                    const forcePlay = () => {
                        audio.play().then(() => {
                            console.log('🎵 Forever Young started after interaction!');
                        });
                        // Remove event listeners after first play
                        document.removeEventListener('click', forcePlay);
                        document.removeEventListener('keydown', forcePlay);
                        document.removeEventListener('touchstart', forcePlay);
                    };

                    // Add multiple event listeners to catch any user interaction
                    document.addEventListener('click', forcePlay);
                    document.addEventListener('keydown', forcePlay);
                    document.addEventListener('touchstart', forcePlay);
                });
            }

            resetGame();
        }

        // Stop music when modal is closed
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('scratchCardModal');
            if (modal) {
                modal.addEventListener('hidden.bs.modal', function() {
                    const audio = document.getElementById('foreverYoungAudio');
                    if (audio) {
                        audio.pause();
                        audio.currentTime = 0; // Reset to beginning
                        console.log('🎵 Music stopped and reset');
                    }
                });
            }
        });

        function resetGame() {
            currentStage = 1;
            gameComplete = false;
            document.querySelectorAll('.game-stage').forEach(stage => stage.classList.add('d-none'));
            document.getElementById('stage1').classList.remove('d-none');
        }

        function nextStage(stageNumber) {
            document.getElementById(`stage${currentStage}`).classList.add('d-none');
            document.getElementById(`stage${stageNumber}`).classList.remove('d-none');
            currentStage = stageNumber;

            if (stageNumber === 3) {
                setTimeout(initScratchCards, 500);
            }
        }

        function initScratchCards() {
            const cards = document.querySelectorAll('.scratch-card');
            cards.forEach(card => {
                setupScratchCard(card);
            });
        }

        function setupScratchCard(card) {
            const canvas = card.querySelector('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = 60;
            canvas.height = 60;

            // Fill canvas with gray overlay for scratching
            ctx.fillStyle = 'rgba(255,255,255,0.8)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            let isScratching = false;
            let revealed = false;

            function handleScratch(e) {
                if (gameComplete || revealed) return;

                const rect = canvas.getBoundingClientRect();
                const x = (e.type.includes('mouse') ? e.clientX : e.touches[0].clientX) - rect.left;
                const y = (e.type.includes('mouse') ? e.clientY : e.touches[0].clientY) - rect.top;

                // Scale coordinates to canvas size
                const scaleX = canvas.width / rect.width;
                const scaleY = canvas.height / rect.height;

                ctx.globalCompositeOperation = 'destination-out';
                ctx.beginPath();
                ctx.arc(x * scaleX, y * scaleY, 15, 0, Math.PI * 2);
                ctx.fill();
                ctx.globalCompositeOperation = 'source-over';

                // Check if enough area is scratched
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
                let transparentPixels = 0;
                for (let i = 3; i < imageData.length; i += 4) {
                    if (imageData[i] === 0) transparentPixels++;
                }
                const totalPixels = canvas.width * canvas.height;

                if (transparentPixels / totalPixels > 0.15 && !revealed) {
                    revealed = true;
                    card.classList.add('revealed');
                    card.removeChild(canvas); // Hapus canvas dari DOM
                    card.textContent = card.dataset.emoji; // Tampilkan emoji di tengah kartu

                    if (card.dataset.emoji === '💣') {
                        gameComplete = true;
                        setTimeout(showResult, 800);
                    }
                }
            }

            canvas.addEventListener('mousedown', (e) => {
                isScratching = true;
                handleScratch(e);
            });
            canvas.addEventListener('mouseup', () => {
                isScratching = false;
            });
            canvas.addEventListener('mouseleave', () => {
                isScratching = false;
            });
            canvas.addEventListener('mousemove', (e) => {
                if (isScratching) handleScratch(e);
            });

            // Touch events
            canvas.addEventListener('touchstart', (e) => {
                e.preventDefault();
                isScratching = true;
                handleScratch(e);
            });
            canvas.addEventListener('touchend', () => {
                isScratching = false;
            });
            canvas.addEventListener('touchcancel', () => {
                isScratching = false;
            });
            canvas.addEventListener('touchmove', (e) => {
                e.preventDefault();
                if (isScratching) handleScratch(e);
            });
        }

        function showResult() {
            document.getElementById('stage3').classList.add('d-none');
            document.getElementById('stage4').classList.remove('d-none');

            const resultText = document.getElementById('resultText');
            const resultSticker = document.getElementById('resultSticker');

            // Change sticker to explosion
            resultSticker.src = 'https://feeldreams.github.io/emawh.gif';

            // Type animation for result text
            const messages = [
                "Yaahh kena Boom!! 💥😝",
                "Kalau kamu kena boom...",
                "Kamu harus jadi pacarku yaa! 😆😍🩷",
                "Gaboleh nolak!! 😝🫣💐"
            ];

            let messageIndex = 0;

            function typeMessage() {
                if (messageIndex >= messages.length) {
                    // Start love percentage animation
                    setTimeout(startLoveAnimation, 1000);
                    return;
                }

                const message = messages[messageIndex];
                let charIndex = 0;
                resultText.innerHTML = '<div class="type-animation"></div>';
                const textElement = resultText.querySelector('.type-animation');

                function typeChar() {
                    if (charIndex < message.length) {
                        textElement.textContent = message.substring(0, charIndex + 1);
                        charIndex++;
                        setTimeout(typeChar, 50);
                    } else {
                        textElement.classList.remove('type-animation');
                        messageIndex++;
                        setTimeout(() => {
                            resultText.innerHTML += '<br><div class="type-animation"></div>';
                            typeMessage();
                        }, 800);
                    }
                }
                typeChar();
            }

            typeMessage();
        }

        function startLoveAnimation() {
            const resultText = document.getElementById('resultText');
            const loveContainer = document.createElement('div');
            loveContainer.style.marginTop = '20px';

            // Love percentage animation
            let percentage = 10;
            const loveText = document.createElement('div');
            loveText.className = 'type-animation';
            loveText.style.fontSize = '1.2em';
            loveText.style.color = '#ff6b9d';
            loveContainer.appendChild(loveText);
            resultText.appendChild(loveContainer);

            function updatePercentage() {
                if (percentage <= 100) {
                    loveText.textContent = `I Love You ${percentage}% ❤️`;
                    percentage += 10;
                    setTimeout(updatePercentage, 200);
                } else {
                    loveText.classList.remove('type-animation');

                    // Add final message
                    setTimeout(() => {
                        const finalMessage = document.createElement('div');
                        finalMessage.innerHTML =
                            '<br><strong style="color: #ff6b9d;">Makasii udah mau jadi pacarku! 😆🫣💐</strong>';
                        loveContainer.appendChild(finalMessage);

                        // Start heart rain
                        startHeartRain();

                        // Change sticker to love
                        document.getElementById('resultSticker').src = 'https://htmlku.com/0/panda/terlope.gif';
                    }, 500);
                }
            }
            updatePercentage();
        }

        function startHeartRain() {
            const heartRain = document.getElementById('loveAnimation');
            heartRain.classList.remove('d-none');

            function createHeart() {
                const heart = document.createElement('div');
                heart.className = 'heart-fall';
                heart.innerHTML = ['❤️', '💕', '💖', '💗', '💘', '💝'][Math.floor(Math.random() * 6)];
                heart.style.left = Math.random() * 100 + 'vw';
                heart.style.animationDuration = (Math.random() * 3 + 2) + 's';
                heart.style.fontSize = (Math.random() * 10 + 15) + 'px';

                document.querySelector('.heart-rain').appendChild(heart);

                // Remove heart after animation
                setTimeout(() => {
                    if (heart.parentNode) {
                        heart.parentNode.removeChild(heart);
                    }
                }, 5000);
            }

            // Create hearts continuously
            const heartInterval = setInterval(createHeart, 300);

            // Stop after 10 seconds
            setTimeout(() => {
                clearInterval(heartInterval);
            }, 10000);
        }

        // Memory Game Logic
        let memoryCurrentStage = 1;
        let memoryGameComplete = false;
        let memoryCards = [];
        let hasFlippedCard = false;
        let lockBoard = false;
        let firstCard, secondCard;
        let matchedPairs = 0;

        function openMemoryGame() {
            // Reset game state
            memoryCurrentStage = 1;
            memoryGameComplete = false;
            hasFlippedCard = false;
            lockBoard = false;
            firstCard = null;
            secondCard = null;
            matchedPairs = 0;

            // Show stage 1 and hide others
            document.getElementById('memoryStage1').classList.remove('d-none');
            document.getElementById('memoryStage2').classList.add('d-none');
            document.getElementById('memoryStage3').classList.add('d-none');
            document.getElementById('memoryStage4').classList.add('d-none');

            // Open modal
            const modal = new bootstrap.Modal(document.getElementById('memoryGameModal'));
            modal.show();

            // Auto-play Forever Young music
            const audio = document.getElementById('foreverYoungAudio');
            if (audio) {
                audio.play().catch(e => console.log('Audio play failed:', e));
            }
        }

        function nextMemoryStage(stageNumber) {
            document.getElementById(`memoryStage${memoryCurrentStage}`).classList.add('d-none');
            document.getElementById(`memoryStage${stageNumber}`).classList.remove('d-none');
            memoryCurrentStage = stageNumber;

            if (stageNumber === 3) {
                setTimeout(initMemoryGame, 500);
            }
        }

        function initMemoryGame() {
            memoryCards = document.querySelectorAll('.memory-card');
            
            // Reset all cards
            memoryCards.forEach(card => {
                card.classList.remove('flipped', 'matched');
                card.textContent = '';
                card.addEventListener('click', flipMemoryCard);
            });

            // Shuffle cards
            shuffleMemoryCards();
        }

        function shuffleMemoryCards() {
            const emojis = ['🫶', '💞', '🥳', '❤️‍🔥', '💐', '😍', '🥰', '🩷'];
            const doubledEmojis = [...emojis, ...emojis]; // Create pairs
            
            // Fisher-Yates shuffle
            for (let i = doubledEmojis.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [doubledEmojis[i], doubledEmojis[j]] = [doubledEmojis[j], doubledEmojis[i]];
            }

            // Assign shuffled emojis to cards
            memoryCards.forEach((card, index) => {
                card.dataset.emoji = doubledEmojis[index];
            });
        }

        function flipMemoryCard() {
            if (lockBoard) return;
            if (this === firstCard) return;
            if (this.classList.contains('matched')) return;

            this.classList.add('flipped');
            this.textContent = this.dataset.emoji;

            if (!hasFlippedCard) {
                hasFlippedCard = true;
                firstCard = this;
                return;
            }

            secondCard = this;
            checkForMemoryMatch();
        }

        function checkForMemoryMatch() {
            let isMatch = firstCard.dataset.emoji === secondCard.dataset.emoji;
            isMatch ? disableMemoryCards() : unflipMemoryCards();
        }

        function disableMemoryCards() {
            firstCard.removeEventListener('click', flipMemoryCard);
            secondCard.removeEventListener('click', flipMemoryCard);
            firstCard.classList.add('matched');
            secondCard.classList.add('matched');

            matchedPairs++;
            if (matchedPairs === 8) {
                endMemoryGame();
            }

            resetMemoryBoard();
        }

        function unflipMemoryCards() {
            lockBoard = true;

            setTimeout(() => {
                firstCard.classList.remove('flipped');
                secondCard.classList.remove('flipped');
                firstCard.textContent = '';
                secondCard.textContent = '';

                resetMemoryBoard();
            }, 1000);
        }

        function resetMemoryBoard() {
            [hasFlippedCard, lockBoard] = [false, false];
            [firstCard, secondCard] = [null, null];
        }

        function endMemoryGame() {
            memoryGameComplete = true;
            
            setTimeout(() => {
                // Hide game board and show success stage
                document.getElementById('memoryStage3').classList.add('d-none');
                document.getElementById('memoryStage4').classList.remove('d-none');

                // Start typing animation after a delay
                setTimeout(startMemoryTypingAnimation, 1000);

                // Start heart rain
                setTimeout(() => {
                    document.getElementById('memoryLoveAnimation').classList.remove('d-none');
                    startMemoryHeartRain();
                }, 2000);

            }, 1000);
        }

        function startMemoryTypingAnimation() {
            const texts = [
                "Di dunia, yang luas ini\nada 87% manusia~",
                "Dan 70% air di dalamnya~", 
                "Tapi kalau hatiku?? 🤔\n\n1000% isinya cuma kamuu 😆🫵"
            ];

            let currentTextIndex = 0;
            const typingElement = document.getElementById('typingText');
            
            function typeText() {
                if (currentTextIndex >= texts.length) {
                    // Show final message
                    setTimeout(showFinalMemoryMessage, 1000);
                    return;
                }

                typingElement.innerHTML = '';
                typingElement.classList.add('typing-effect');
                
                const text = texts[currentTextIndex];
                let charIndex = 0;

                function typeChar() {
                    if (charIndex < text.length) {
                        if (text[charIndex] === '\n') {
                            typingElement.innerHTML += '<br>';
                        } else {
                            typingElement.innerHTML += text[charIndex];
                        }
                        charIndex++;
                        setTimeout(typeChar, 50);
                    } else {
                        typingElement.classList.remove('typing-effect');
                        setTimeout(() => {
                            currentTextIndex++;
                            setTimeout(typeText, 1000);
                        }, 1500);
                    }
                }

                typeChar();
            }

            document.getElementById('finalMessage').classList.remove('d-none');
            typeText();
        }

        function showFinalMemoryMessage() {
            const finalMessage = document.createElement('div');
            finalMessage.className = 'mt-4 text-white';
            finalMessage.innerHTML = `
                <h3 style="color: #ff6b9d;">ᯓᡣ𐭩</h3>
                <p><strong>Lopyuu ayangkuu</strong> tersayang,<br>
                termanis, terlucu, terimuutttt<br>
                <strong>semangat terus yaw 🫣😍😋💐</strong></p>
            `;
            
            document.getElementById('memoryResultText').appendChild(finalMessage);
            
            // Change sticker to love
            document.getElementById('memoryResultSticker').src = 'https://htmlku.com/0/panda/terlope2.gif';
        }

        function startMemoryHeartRain() {
            const heartRain = document.querySelector('#memoryLoveAnimation .heart-rain');
            
            function createMemoryHeart() {
                const heart = document.createElement('div');
                heart.className = 'heart-fall';
                heart.innerHTML = ['❤️', '💕', '💖', '💗', '💘', '💝', '🩷', '🫶'][Math.floor(Math.random() * 8)];
                heart.style.left = Math.random() * 100 + 'vw';
                heart.style.animationDuration = (Math.random() * 3 + 2) + 's';
                heart.style.fontSize = (Math.random() * 10 + 15) + 'px';

                heartRain.appendChild(heart);

                // Remove heart after animation
                setTimeout(() => {
                    if (heart.parentNode) {
                        heart.parentNode.removeChild(heart);
                    }
                }, 5000);
            }

            // Create hearts continuously
            const heartInterval = setInterval(createMemoryHeart, 300);

            // Stop after 10 seconds
            setTimeout(() => {
                clearInterval(heartInterval);
            }, 10000);
        }

        // Stop memory game music when modal is closed
        document.addEventListener('DOMContentLoaded', function() {
            const memoryModal = document.getElementById('memoryGameModal');
            if (memoryModal) {
                memoryModal.addEventListener('hidden.bs.modal', function () {
                    const audio = document.getElementById('foreverYoungAudio');
                    if (audio) {
                        audio.pause();
                        audio.currentTime = 0;
                    }
                });

                // Add click effect for memory game
                memoryModal.addEventListener('click', function(e) {
                    const circle = document.createElement("div");
                    circle.classList.add("memory-click-effect");
                    circle.style.left = `${e.pageX}px`;
                    circle.style.top = `${e.pageY}px`;

                    document.body.appendChild(circle);

                    circle.addEventListener("animationend", () => {
                        circle.remove();
                    });
                });
            }
        });
    </script>
</body>

</html>
