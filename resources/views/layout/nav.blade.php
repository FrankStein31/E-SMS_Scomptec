<nav>
    <div class="app-logo">
        <a class="logo d-inline-block" href="index.html">
            <img alt="#" src="../assets/images/logo/1.png">
        </a>

        <span class="bg-light-primary toggle-semi-nav">
            <i class="iconoir-chevron-double-right f-s-20"></i>
        </span>
    </div>

    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            <li class="menu-title">
                <span>Dashboard</span>
            </li>
            @if (auth()->user()->jabatan == 'Administrator')
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="iconoir-home-alt"></i>
                    Dashboard
                </a>
            </li>
            <li class="menu-title">
                <span>Menu Utama</span>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#master">
                    <i class="iconoir-settings"></i>
                    Data Master
                </a>
                <ul class="collapse" id="master">
                    <li><a href="">Klasifikasi</a></li>
                    <li><a href="">Unit Kerja</a></li>
                    <li><a href="">No. Surat</a></li>
                    <li><a href="">Daftar Alamat</a></li>
                    <li><a href="">Tindakan Disposisi</a></li>
                    <li><a href="">User</a></li>
                </ul>
            </li>
                <li>
                    <a href="">
                        <i class="iconoir-home-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="menu-title">
                    <span>Menu Utama</span>
                </li>
                <li>
                    <a aria-expanded="false" data-bs-toggle="collapse" href="#master">
                        <i class="iconoir-settings"></i>
                        Data Master
                    </a>
                    <ul class="collapse" id="master">
                        <li><a href="">Klasifikasi</a></li>
                        <li><a href="">Unit Kerja</a></li>
                        <li><a href="">No. Surat</a></li>
                        <li><a href="">Daftar Alamat</a></li>
                        <li><a href="">Tindakan Disposisi</a></li>
                        <li><a href="">User</a></li>
                    </ul>
                </li>
            @else
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="iconoir-home-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="menu-title">
                    <span>Menu Surat</span>
                </li>
                <li>
                    <a aria-expanded="false" data-bs-toggle="collapse" href="#dashboard">
                        <i class="iconoir-mail"></i>
                        Manajemen Surat
                    </a>
                    <ul class="collapse" id="dashboard">
                        <li><a href="{{ route('kotakmasuk.index') }}">Kotak Masuk</a></li>
                        <li><a href="{{ route('buatsurat.index') }}">Buat Surat</a></li>
                        <li><a href="{{ route('entrisurat.index') }}">Entri Surat</a></li>
                        <li><a href="{{ route('draft_surat.index') }}">Draft Surat</a></li>
                        <li><a href="{{ route('suratkeluar.index') }}">Surat Keluar</a></li>
                        <li><a href="{{ route('suratterkirim.index') }}">Surat Terkirim</a></li>
                    </ul>
                </li>
                <li>
                    <a aria-expanded="false" data-bs-toggle="collapse" href="#pengendalian">
                        <i class="iconoir-settings"></i>
                        Pengendalian
                    </a>
                    <ul class="collapse" id="pengendalian">
                        <li><a href="{{ route('disposisi.index') }}">Disposisi</a></li>
                        <li><a href="#">Nota Dinas</a></li>
                        <li><a href="#">Surat Dinas</a></li>
                        <li><a href="#">Memo Dinas</a></li>
                        <li><a href="#">Pengumuman</a></li>
                        <li><a href="#">Undangan</a></li>
                        <li><a href="#">Edaran</a></li>
                    </ul>
                </li>
                <li>
                    <a aria-expanded="false" data-bs-toggle="collapse" href="#more">
                        <i class="iconoir-more-horiz"></i>
                        Opsi Lain
                    </a>
                    <ul class="collapse" id="more">
                        <li><a href="{{ route('report.surat') }}">Laporan</a></li>
                        <li><a href="{{ route('report.statistik') }}">Statistik</a></li>
                        <li><a href="">Pesan No.</a></li>
                        <li><a href="">Aktivitas</a></li>
                    </ul>
                </li>
            @endif
            <li class="menu-title">
                <span>Lainnya</span>
            </li>
            <li class="no-sub">
                <a href="">
                    <i class="iconoir-settings"></i> Pengaturan
                </a>
            </li>
            <!-- <li class="no-sub">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger w-100 text-start">
                        <i class="iconoir-log-out"></i> Logout
                    </button>
                </form>
            </li> -->
        </ul>
    </div>

    <div class="menu-navs">
        <span class="menu-previous"><i class="iconoir-nav-arrow-left"></i></span>
        <span class="menu-next"><i class="iconoir-nav-arrow-right"></i></span>
    </div>
</nav>
