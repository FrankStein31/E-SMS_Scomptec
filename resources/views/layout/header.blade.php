<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 col-sm-4 d-flex align-items-center header-left p-0">
                <span class="header-toggle me-3">
                    <i class="iconoir-view-grid"></i>
                </span>
            </div>

            <div class="col-6 col-sm-8 d-flex align-items-center justify-content-end header-right p-0">
                <ul class="d-flex align-items-center">
                    <li class="header-profile">
                        <a aria-controls="profilecanvasRight" class="d-block head-icon" 
                            data-bs-target="#profilecanvasRight" data-bs-toggle="offcanvas" href="#" 
                            role="button">
                            <div class="d-flex align-items-center">
                                <span class="ms-2 d-none d-sm-block text-dark small">{{ Auth::user()->fullname }}</span>
                            </div>
                        </a>

                        <div aria-labelledby="profilecanvasRight" class="offcanvas offcanvas-end header-profile-canvas"
                            id="profilecanvasRight" tabindex="-1" style="width: 300px;">
                            <div class="offcanvas-body p-0">
                                <div class="bg-primary p-4 text-center text-white">
                                    <img alt="" class="img-fluid rounded-circle mb-3" width="100"
                                        src="{{ asset('assets/images/avatar.png') }}">
                                    <h5 class="mb-1">{{ Auth::user()->fullname }}</h5>
                                    <p class="mb-0">{{ Auth::user()->jabatan }}</p>
                                    <small>{{ Auth::user()->username }}</small>
                                </div>
                                <div class="p-4">
                                    <a href="{{ route('profile.edit') }}" class="btn btn-light-primary btn-sm d-block mb-2">
                                        <i class="ph-duotone ph-user-circle pe-1"></i> Edit Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-light-danger btn-sm d-block w-100">
                                            <i class="ph-duotone ph-sign-out pe-1"></i> Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
