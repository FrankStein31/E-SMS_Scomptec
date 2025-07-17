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
            <a class="logo text-decoration-none" href="/">E-SMS</a>
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
                        <a class="nav-link getstarted" href="{{ route('login') }}">Login</a>
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
                        <a href="{{ route('login') }}" class="hero-btn animate-on-scroll">
                            <i class="me-2">Get Started</i>
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
    </script>
</body>

</html>
