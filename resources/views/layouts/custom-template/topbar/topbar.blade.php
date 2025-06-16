{{-- @dd(); --}}
<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            @php
                $dashboard = array_filter($navs, function ($nav) {
                    return $nav['name'] == 'Dashboard';
                });
                $dashboard = array_shift($dashboard);
            @endphp
            <li class="nav-item d-none d-md-block"><a href="{{ $dashboard['url'] }}" class="nav-link"><i
                        class="fw-bold {{ $dashboard['icon'] }}"></i></a></li>
        </ul>
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a class="nav-link" href="javascript: void(0);" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>
            <!--end::Fullscreen Toggle-->

            {{-- Start Language Mode --}}
            <li class="nav-item dropdown me-2">
                <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                    id="bd-language" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                    data-bs-display="static">
                    <span class="" id="bd-language-text">{{ strtoupper(Lang::locale()) }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-language-text"
                    style="--bs-dropdown-min-width: 8rem;">
                    <li></li>
                    <li>
                        <a href="{{ route('change-locale', 'en') }}" class="dropdown-item d-flex align-items-center"
                            aria-pressed="false">
                            <span class="flag-icon flag-icon-us me-2"></span>
                            English
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('change-locale', 'id') }}" class="dropdown-item d-flex align-items-center"
                            aria-pressed="false">
                            <span class="flag-icon flag-icon-id me-2"></span>
                            Indonesia
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </a>
                    </li>
                </ul>
            </li>


            {{-- Start Theme Mode --}}
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                    id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                    data-bs-display="static">
                    <span class="theme-icon-active">
                        <i class="my-1"></i>
                    </span>
                    <span class="d-lg-none ms-2" id="bd-theme-text"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text"
                    style="--bs-dropdown-min-width: 8rem;">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active"
                            data-bs-theme-value="light" aria-pressed="false">
                            <i class="bi bi-sun-fill me-2"></i>
                            Light
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="dark" aria-pressed="false">
                            <i class="bi bi-moon-fill me-2"></i>
                            Dark
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="auto" aria-pressed="true">
                            <i class="bi bi-laptop me-2"></i>
                            Auto
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                </ul>
            </li>
            {{-- End Theme Mode --}}

            @php
                $profile_photo = Auth::user()?->userProfile?->profile_photo
                ? URL::asset('storage/' . Auth::user()->userProfile->profile_photo)
                : URL::asset('storage/lte/assets/img/default_photo_profile.png');
            @endphp

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ $profile_photo }}"
                        class="user-image rounded-circle shadow" alt="User Image" />
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!--begin::User Image-->
                    <li class="user-header text-bg-primary">
                        <img src="{{ $profile_photo }}"
                            class="rounded-circle shadow" alt="User Image" />
                        <p>
                            {{ Auth::user()->name }}
                            <small
                                class="btn btn-rounded btn-outline-dark text-white mb-2">{{ Auth::user()->email }}</small>
                            <small>Bergabung sejak, {{ Auth::user()->created_at->format('d M Y H:i:s') }}</small>
                        </p>
                    </li>
                    <!--end::User Image-->
                    <!--begin::Menu Body-->
                    <li class="user-body">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-12 text-center">
                                <small>{{ ucwords(Auth::user()->roles->pluck('name')->toArray()[0]) }}</small></div>
                        </div>
                        <!--end::Row-->
                    </li>
                    <!--end::Menu Body-->
                    <!--begin::Menu Footer-->
                    <li class="user-footer">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-flat"><i class="bi bi-person-bounding-box"></i> Profile</a>
                        <a href="javascript:0, void;" class="btn btn-danger btn-flat float-end"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-left"></i> Logout</a>

                        {{-- Logout Form --}}
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                            @csrf
                        </form>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->
