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
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#dashboard">
                    <i class="iconoir-home-alt"></i>
                    Home
                </a>
                <ul class="collapse" id="dashboard">
                    <li><a href="{{ route('kotakmasuk.index') }}">Kotak Masuk</a></li>
                    <li><a href="{{ route('buatsurat.create') }}">Buat Surat</a></li>
                    <li><a href="{{ route('entrisurat.create') }}">Entri Surat</a></li>
                    <li><a href="{{ route('draft_surat.create') }}">Draft Surat</a></li>
                    <li><a href="ecommerce_dashboard.html">Surat Keluar</a></li>
                    <li><a href="ecommerce_dashboard.html">Surat Terkirim</a></li>
                    <li><a href="ecommerce_dashboard.html">Pengendalian</a></li>
                </ul>
            </li>
            <!-- <li class="no-sub">
                <a href="widget.html">
                    <i class="iconoir-user"></i> Pegawai
                </a>
            </li> -->
            <li class="no-sub">
                <a href="{{ route('report.surat') }}">
                    <i class="iconoir-apple-wallet"></i> Laporan
                </a>
                
            </li>
            <li class="no-sub">
                <a href="{{ route('report.statistik') }}">
                    <i class="iconoir-activity"></i> Statistik
                </a>
            </li>
            <li class="no-sub">
                <a href="widget.html">
                    <i class="iconoir-mail"></i> Pesan No.
                </a>
            </li>
            <li class="no-sub">
                <a href="widget.html">
                    <i class="iconoir-activity"></i> Aktifitas
                </a>
            </li>
        </ul>
    </div>

    <div class="menu-navs">
        <span class="menu-previous"><i class="iconoir-nav-arrow-left"></i></span>
        <span class="menu-next"><i class="iconoir-nav-arrow-right"></i></span>
    </div>
</nav>
