<!-- partial:./partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item sidebar-category">
            <div class="logo-container">
                <a href="{{ route('landing') }}" class="logo-link">
                    <div class="logo-text">ESMS</div>
                </a>
            </div>
            <span></span>
        </li>

        <style>
        /* Logo Container Styles */
        .logo-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 12px;
            margin: 8px 8px 16px 8px;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .logo-container:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Minimize Sidebar Styles */
        body.sidebar-icon-only .sidebar .logo-container {
            padding: 8px !important;
            margin: 8px 6px 16px 6px !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        body.sidebar-icon-only .sidebar .logo-text {
            font-size: 16px !important;
            letter-spacing: 1px !important;
        }

        .logo-link {
            text-decoration: none !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-link:hover {
            text-decoration: none !important;
        }

        .logo-text {
            color: white !important;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            text-align: center;
            letter-spacing: 2px;
            line-height: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .logo-container {
                margin: 6px 4px 12px 4px;
                padding: 10px 8px;
            }
            
            .logo-text {
                font-size: 20px;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .logo-container {
                box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
            }
            
            .logo-container:hover {
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.5);
            }
        }
        </style>

        @if (auth()->user()->jabatan == 'Administrator')
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-view-dashboard menu-icon"></i> <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item sidebar-category">
                <p>Menu Utama</p>
                <span></span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-tag-multiple menu-icon"></i> <span class="menu-title">Klasifikasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-domain menu-icon"></i> <span class="menu-title">Unit Kerja</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-file-document-box-multiple-outline menu-icon"></i> <span class="menu-title">No.
                        Surat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-map-marker-multiple menu-icon"></i> <span class="menu-title">Daftar Alamat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-office-building menu-icon"></i> <span class="menu-title">Tindakan Disposisi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-account-circle menu-icon"></i> <span class="menu-title">User</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="mdi mdi-view-dashboard menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item sidebar-category">
                <p>Menu Surat</p>
                <span></span>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-email menu-icon"></i> <span class="menu-title">Kotak Masuk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-file-plus menu-icon"></i> <span class="menu-title">Buat Surat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-file-import menu-icon"></i> <span class="menu-title">Entri Surat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-file-document menu-icon"></i> <span class="menu-title">Draft Surat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-send menu-icon"></i> <span class="menu-title">Surat Keluar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-check-circle menu-icon"></i> <span class="menu-title">Surat Terkirim</span>
                </a>
            </li>
            <li class="nav-item sidebar-category">
                <p>Tambahan</p>
                <span></span>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#pengendalian" aria-expanded="false"
                    aria-controls="pengendalian">
                    <i class="mdi mdi-cogs menu-icon"></i> <span class="menu-title">Pengendalian</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="pengendalian">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="#">Disposisi</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Nota Dinas</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Surat Dinas</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Memo Dinas</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Pengumuman</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Undangan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Edaran</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#more" aria-expanded="false"
                    aria-controls="more">
                    <i class="mdi mdi-dots-horizontal menu-icon"></i> <span class="menu-title">Opsi Lain</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="more">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="#"></i> Laporan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"></i> Statistik</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"></i> Pesan No.</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"></i> Aktivitas</a></li>
                    </ul>
                </div>
            </li>
            @endif
        <li class="nav-item sidebar-category">
            <p>Lainnya</p>
            <span></span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="mdi mdi-settings-outline menu-icon"></i> <span class="menu-title">Pengaturan</span>
            </a>
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link btn btn-block text-left text-danger">
                    <i class="mdi mdi-logout menu-icon"></i>
                    <span class="menu-title">Logout</span>
                </button>
            </form>
        </li>
</nav>