<style>
    :root {
        --primary-color: #4C51BF;
        /* Contoh warna Indigo */
        --info-color: #3B82F6;
        /* Contoh warna Biru */
        --success-color: #10B981;
        /* Contoh warna Hijau */
        --warning-color: #F59E0B;
        /* Contoh warna Kuning */
        --danger-color: #EF4444;
        /* Contoh warna Merah */
        --dark-color: #1F2937;
        /* Contoh warna Abu-abu Gelap */
        --sidebar-bg: #FFFFFF;
        --sidebar-link-hover-bg: #f3f4f6;
        --sidebar-text-color: #6B7280;
        --sidebar-text-active-color: #FFFFFF;
    }

    .sidebar {
        min-height: 100vh;
        background: var(--sidebar-bg);
        border-radius: 1rem;
        transition: all 0.3s ease;
    }

    .sidebar-header {
        padding-bottom: 0.75rem;
    }

    .sidebar-logo {
        font-size: 5rem;
        /* Ukuran logo bisa disesuaikan */
        color: var(--primary-color);
    }

    .sidebar-title {
        font-weight: 700;
        color: var(--primary-color);
    }

    .sidebar-nav {
        list-style: none;
        padding-left: 0;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 0.8rem 1rem;
        margin-bottom: 0.25rem;
        border-radius: 0.5rem;
        color: var(--sidebar-text-color);
        font-weight: 500;
        transition: background-color 0.2s ease, color 0.2s ease;
        text-decoration: none;
    }

    .sidebar-link:hover {
        background-color: var(--sidebar-link-hover-bg);
        color: var(--dark-color);
    }

    .sidebar-link .icon-container {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 0.75rem;
        color: var(--sidebar-text-active-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
    }

    /* Styling untuk link aktif */
    .sidebar-link.active {
        color: var(--sidebar-text-active-color);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .sidebar-link.active .icon-container {
        background-color: transparent !important;
        /* Hapus latar belakang ikon saat induknya aktif */
        box-shadow: none;
    }

    /* Warna Gradien untuk setiap menu aktif */
    .sidebar-link.dashboard.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, #818CF8 100%);
    }

    .sidebar-link.manajemen-user.active {
        background: linear-gradient(135deg, var(--dark-color) 0%, #6B7280 100%);
    }

    .sidebar-link.surat-masuk.active {
        background: linear-gradient(135deg, var(--info-color) 0%, #93C5FD 100%);
    }

    .sidebar-link.surat-keluar.active {
        background: linear-gradient(135deg, var(--success-color) 0%, #6EE7B7 100%);
    }

    .sidebar-link.pengaturan.active {
        background: linear-gradient(135deg, var(--warning-color) 0%, #FCD34D 100%);
    }


    /* Styling Dropdown/Collapse */
    .submenu {
        list-style: none;
        padding-left: 2rem;
        /* Indentasi untuk submenu */
        margin-top: 0.25rem;
    }

    .submenu .sidebar-link {
        padding: 0.5rem 1rem 0.5rem 0.75rem;
        /* Padding lebih kecil untuk submenu */
        font-size: 0.9rem;
    }

    .submenu .sidebar-link:before {
        content: "›";
        font-weight: bold;
        margin-right: 1rem;
        color: var(--sidebar-text-color);
    }

    .submenu .sidebar-link:hover:before {
        color: var(--dark-color);
    }

    /* Animasi ikon dropdown */
    .dropdown-toggle-icon {
        transition: transform 0.3s ease;
    }

    a[aria-expanded="true"] .dropdown-toggle-icon {
        transform: rotate(180deg);
    }

    /* Tombol Logout */
    .btn-logout {
        border-radius: 0.75rem !important;
        background: linear-gradient(135deg, var(--danger-color) 0%, #FCA5A5 100%);
        border: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-logout:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 14px rgba(239, 68, 68, 0.2);
    }
</style>

<div class="card shadow-lg sidebar p-3 border-0 d-flex flex-column">
    <div class="sidebar-header text-center">
        <i class="material-icons sidebar-logo">move_to_inbox</i>
        <h5 class="mt-2 mb-0 sidebar-title">E-SMS Scomptec</h5>
    </div>

    <hr class="mt-2 mb-3">

    <ul class="nav nav-pills flex-column sidebar-nav">
        @if (auth()->user()->jabatan == 'Administrator')
            <li class="nav-item">
                <a class="sidebar-link manajemen-user {{ request()->is('users') ? 'active' : '' }}" href="#">
                    <span class="icon-container" style="background-color: var(--dark-color);">
                        <i class="material-icons">people</i>
                    </span>
                    <span>Manajemen User</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="sidebar-link dashboard {{ request()->is('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                <span class="icon-container" style="background-color: var(--primary-color);">
                    <i class="material-icons">dashboard</i>
                </span>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="sidebar-link surat-masuk {{ request()->is('surat-masuk') ? 'active' : '' }}" href="#">
                <span class="icon-container" style="background-color: var(--info-color);">
                    <i class="material-icons">mail</i>
                </span>
                <span>Kotak Masuk</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="sidebar-link surat-masuk {{ request()->is('buat-surat') ? 'active' : '' }}" href="#">
                <span class="icon-container" style="background-color: var(--info-color);">
                    <i class="material-icons">note_add</i>
                </span>
                <span>Buat Surat</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="sidebar-link surat-masuk {{ request()->is('entri-surat') ? 'active' : '' }}" href="#">
                <span class="icon-container" style="background-color: var(--info-color);">
                    <i class="material-icons">input</i>
                </span>
                <span>Entri Surat</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="sidebar-link surat-masuk {{ request()->is('draft-surat') ? 'active' : '' }}" href="#">
                <span class="icon-container" style="background-color: var(--info-color);">
                    <i class="material-icons">drafts</i>
                </span>
                <span>Draft Surat</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="sidebar-link surat-keluar {{ request()->is('surat-keluar') ? 'active' : '' }}" href="#">
                <span class="icon-container" style="background-color: var(--success-color);">
                    <i class="material-icons">outbox</i>
                </span>
                <span>Surat Keluar</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="sidebar-link surat-masuk {{ request()->is('surat-terkirim') ? 'active' : '' }}" href="#">
                <span class="icon-container" style="background-color: var(--info-color);">
                    <i class="material-icons">mark_email_read</i>
                </span>
                <span>Surat Terkirim</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="sidebar-link surat-masuk {{ Str::startsWith(request()->path(), 'pengendalian') ? 'active' : '' }}"
                href="#pengendalianMenu" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="pengendalianMenu">
                <span class="icon-container" style="background-color: var(--info-color);">
                    <i class="material-icons">manage_history</i>
                </span>
                <span>Pengendalian</span>
                <i class="material-icons ms-auto dropdown-toggle-icon">expand_more</i>
            </a>
            <div class="collapse" id="pengendalianMenu">
                <ul class="submenu">
                    <li><a class="sidebar-link" href="#">Disposisi</a></li>
                    <li><a class="sidebar-link" href="#">Nota Dinas</a></li>
                    <li><a class="sidebar-link" href="#">Surat Dinas</a></li>
                    <li><a class="sidebar-link" href="#">Memo Dinas</a></li>
                    <li><a class="sidebar-link" href="#">Pengumuman</a></li>
                    <li><a class="sidebar-link" href="#">Undangan</a></li>
                    <li><a class="sidebar-link" href="#">Edaran</a></li>
                </ul>
            </div>
        </li>
    </ul>

    <div class="mt-auto">
        <hr class="my-2">
        <ul class="nav nav-pills flex-column sidebar-nav">
            <li class="nav-item">
                <a class="sidebar-link pengaturan {{ request()->is('pengaturan') ? 'active' : '' }}" href="#">
                    <span class="icon-container" style="background-color: var(--warning-color);">
                        <i class="material-icons">settings</i>
                    </span>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" class="p-2">
            @csrf
            <button type="submit"
                class="btn btn-danger w-100 d-flex align-items-center justify-content-center btn-logout">
                <i class="material-icons me-2">logout</i> Logout
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
