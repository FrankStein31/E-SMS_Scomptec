<nav>
    <div class="app-logo">
        <a class="logo d-inline-block" href="/">
            <img alt="#" src="{{ asset('assets/images/logo/esms.png') }}">
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
                <li class="no-sub">
                    <a href="{{ route('dashboard') }}">
                        <i class="iconoir-home-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="menu-title">
                    <span>Menu Utama</span>
                </li>
                <li>
                    <a aria-expanded="false" data-bs-toggle="collapse" href="#master" aria-controls="master"
                        role="button">
                        <i class="iconoir-settings"></i>
                        Data Master
                    </a>
                    <ul class="collapse" id="master">
                        <li><a href="{{ route('klasifikasi.index') }}"
                                class="{{ request()->is('klasifikasi*') ? 'active' : '' }}">Klasifikasi</a></li>
                        <li><a href="{{ route('unitkerja.index') }}">Unit Kerja</a></li>
                        <!-- <li><a href="">No. Surat</a></li> -->
                        <li><a href="{{ route('daftar-alamat.index') }}">Daftar Alamat</a></li>
                        <li><a href="{{ route('tindakan-disposisi.index') }}">Tindakan Disposisi</a></li>
                        <li><a href="{{ route('user.index') }}">User</a></li>
                    </ul>
                </li>
            @else
                <li class="no-sub">
                    <a href="{{ route('dashboard') }}">
                        <i class="iconoir-home-alt"></i>
                        Dashboard
                    </a>
                </li>
                <!-- <li class="no-sub">
                    <a href="{{ route('kotakmasuk.index') }}">
                        <i class="iconoir-mail"></i>
                        Kotak Masuk
                    </a>
                </li> -->
                <li class="menu-title">
                    <span>Menu Surat</span>
                </li>
                <li>
                    <a aria-expanded="false" data-bs-toggle="collapse" href="#manajemen-surat"
                        aria-controls="manajemen-surat" role="button">
                        <i class="iconoir-mail"></i>
                        Manajemen Surat
                    </a>
                    <ul class="collapse" id="manajemen-surat">
                        <li><a href="{{ route('kotakmasuk.index') }}">Kotak Masuk</a></li>
                        <!-- <li><a href="{{ route('buatsurat.index') }}">Buat Surat</a></li> -->
                        <li><a href="{{ route('entrisurat.index') }}">Entri Surat</a></li>
                        <!-- <li><a href="{{ route('draft_surat.index') }}">Draft Surat</a></li> -->
                        <!-- <li><a href="{{ route('suratkeluar.index') }}">Surat Keluar</a></li> -->
                        <!-- <li><a href="{{ route('suratterkirim.index') }}">Surat Terkirim</a></li> -->
                    </ul>
                </li>
                <li>
                    <a aria-expanded="false" data-bs-toggle="collapse" href="#disposisi" aria-controls="disposisi"
                        role="button">
                        <i class="iconoir-settings"></i>
                        Pengendalian
                    </a>
                    <ul class="collapse" id="disposisi">
                        <li><a href="{{ route('disposisi.index') }}">Disposisi</a></li>
                    </ul>
                </li>
                <!-- <li>
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
                </li> -->
                <li>
                    <a aria-expanded="false" data-bs-toggle="collapse" href="#more" aria-controls="more"
                        role="button">
                        <i class="iconoir-more-horiz"></i>
                        Opsi Lain
                    </a>
                    <ul class="collapse" id="more">
                        <li><a href="{{ route('report.surat') }}">Laporan</a></li>
                        <li><a href="{{ route('report.statistik') }}">Statistik</a></li>
                        <!-- <li><a href="" hidden>Pesan No.</a></li> -->
                        <li><a href="{{ route('aktivitas') }}">Aktivitas</a></li>
                    </ul>
                </li>
            @endif
            <!-- <li class="menu-title">
                <span>Lainnya</span>
            </li>
            <li class="no-sub">
                <a href="">
                    <i class="iconoir-settings"></i> Pengaturan
                </a>
            </li> -->
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
