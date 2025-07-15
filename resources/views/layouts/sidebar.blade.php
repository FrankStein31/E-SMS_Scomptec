<div class="card shadow-lg sidebar-md p-3 border-0" style="min-height: 100vh; background: #fff; border-radius: 1rem;">
    <div class="mb-4 text-center">
        <i class="material-icons text-primary" style="font-size: 60px;">sms</i>
        <h5 class="mt-2 mb-0 text-primary" style="font-weight:700;">E-SMS Scomptec</h5>
    </div>

    @if(auth()->user()->jabatan == 'Administrator')
    <nav class="nav flex-column mb-3">
        <a class="nav-link d-flex align-items-center {{ request()->is('users') ? 'active bg-gradient-dark text-white' : '' }}" href="#" style="border-radius: .5rem;">
            <span class="d-inline-flex align-items-center justify-content-center bg-gradient-dark shadow text-white rounded-circle me-2 border-radius-md" style="width: 32px; height: 32px;">
                <i class="material-icons">people</i>
            </span>
            <span>Manajemen User</span>
        </a>
    </nav>
    @endif

    <nav class="nav flex-column">
        <a class="nav-link d-flex align-items-center {{ request()->is('dashboard') ? 'active bg-gradient-primary text-white' : '' }}" href="{{ route('dashboard') }}" style="border-radius: .5rem;">
            <span class="d-inline-flex align-items-center justify-content-center bg-gradient-primary shadow text-white rounded-circle me-2 border-radius-md" style="width: 32px; height: 32px;">
                <i class="material-icons">dashboard</i>
            </span>
            <span>Dashboard</span>
        </a>
        <a class="nav-link d-flex align-items-center {{ request()->is('surat-masuk') ? 'active bg-gradient-info text-white' : '' }}" href="#" style="border-radius: .5rem;">
            <span class="d-inline-flex align-items-center justify-content-center bg-gradient-info shadow text-white rounded-circle me-2 border-radius-md" style="width: 32px; height: 32px;">
                <i class="material-icons">mail</i>
            </span>
            <span>Surat Masuk</span>
        </a>
        <a class="nav-link d-flex align-items-center {{ request()->is('surat-keluar') ? 'active bg-gradient-success text-white' : '' }}" href="#" style="border-radius: .5rem;">
            <span class="d-inline-flex align-items-center justify-content-center bg-gradient-success shadow text-white rounded-circle me-2 border-radius-md" style="width: 32px; height: 32px;">
                <i class="material-icons">send</i>
            </span>
            <span>Surat Keluar</span>
        </a>
        <a class="nav-link d-flex align-items-center {{ request()->is('pengaturan') ? 'active bg-gradient-warning text-white' : '' }}" href="#" style="border-radius: .5rem;">
            <span class="d-inline-flex align-items-center justify-content-center bg-gradient-warning shadow text-white rounded-circle me-2 border-radius-md" style="width: 32px; height: 32px;">
                <i class="material-icons">settings</i>
            </span>
            <span>Pengaturan</span>
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="btn btn-danger w-100" style="border-radius: 0.75rem;">
            <i class="material-icons me-1">logout</i> Logout
        </button>
    </form>
</div>