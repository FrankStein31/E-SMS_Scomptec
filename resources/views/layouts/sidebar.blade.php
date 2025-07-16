<!-- partial:./partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item sidebar-category">
            <p>Menu Utama</p>
            <span></span>
        </li>

        @if (auth()->user()->jabatan == 'Administrator')
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-account-group menu-icon"></i> <span class="menu-title">Manajemen User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-view-dashboard menu-icon"></i> <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-account-circle menu-icon"></i> <span class="menu-title">User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-office-building menu-icon"></i> <span class="menu-title">Tindakan Disposisi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-map-marker-multiple menu-icon"></i> <span class="menu-title">Daftar Alamat</span>
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
                    <i class="mdi mdi-domain menu-icon"></i> <span class="menu-title">Unit Kerja</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-tag-multiple menu-icon"></i> <span class="menu-title">Klasifikasi</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-settings menu-icon"></i> <span class="menu-title">Pengaturan</span>
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
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="mdi mdi-view-dashboard menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-email menu-icon"></i> <span class="menu-title">Kotak Masuk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-file-plus-outline menu-icon"></i> <span class="menu-title">Buat Surat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-file-edit-outline menu-icon"></i> <span class="menu-title">Entri Surat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-file-cloud-outline menu-icon"></i> <span class="menu-title">Draft Surat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-email-send-outline menu-icon"></i> <span class="menu-title">Surat Keluar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="mdi mdi-email-check-outline menu-icon"></i> <span class="menu-title">Surat Terkirim</span>
                </a>
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
        @endif
</nav>
