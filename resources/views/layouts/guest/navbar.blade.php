<header id="header" class="header d-flex align-items-center position-relative"
    style="padding: 5px 0; background-color: #ffffff00; z-index: 999;">
    <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
        <!-- Logo -->
        <a class="navbar-brand ps-3 d-flex align-items-center" href="{{ route('Home') }}">
            <img src="{{ asset_or_default('assets/img/log1.svg') }}" alt="Logo" class="img-fluid">
        </a>

        <!-- NAV MENU - Desktop -->
        <nav id="navmenu" class="d-none d-lg-flex align-items-center mx-auto">
            <ul class="d-flex flex-row align-items-center gap-3 mb-0 p-0 list-unstyled">
                <li><a href="{{ route('Home') }}" class="{{ request()->routeIs('Home') ? 'text-success fw-bold' : 'text-dark' }}">Beranda</a></li>
                <li><a href="{{ route('About') }}" class="{{ request()->is('about') ? 'text-success fw-bold' : 'text-dark' }}">Tentang Kami</a></li>
                <li><a href="{{ url('/product') }}" class="{{ request()->is('service') ? 'text-success fw-bold' : 'text-dark' }}">Produk</a></li>
                <li><a href="{{ url('/blog') }}" class="{{ request()->is('blog') ? 'text-success fw-bold' : 'text-dark' }}">Berita</a></li>
                <li><a href="{{ url('/contact') }}" class="{{ request()->is('contact') ? 'text-success fw-bold' : 'text-dark' }}">Kontak</a></li>
            </ul>
        </nav>

        <!-- ICONS & PROFILE - Desktop -->
        <div class="d-none d-lg-flex align-items-center gap-3">
            @auth
                <!-- Messages -->
                <a href="{{ route('message.index') }}" class="position-relative text-dark me-3">
                    <i class="bi bi-envelope fs-5"></i>
                    @if ($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>

                <!-- Cart -->
                <a href="{{ url('/cart') }}" class="position-relative text-dark me-3">
                    <i class="bi bi-cart3 fs-5"></i>
                    @if ($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            @endauth

            <!-- Profile Dropdown -->
            <div class="dropdown ms-lg-2">
                <a href="#" class="text-dark" id="desktopProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="desktopProfileDropdown">
                    @auth
                        <li><a href="{{ route('profile.show') }}" class="dropdown-item"><i class="bi bi-person me-2"></i> Profile</a></li>
                        <li><a href="#" class="dropdown-item"><i class="bi bi-gear me-2"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item bg-transparent border-0 text-start w-100">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    @endauth
                    @guest
                        <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                    @endguest
                </ul>
            </div>
        </div>

        <!-- MOBILE TOGGLE -->
        <div class="d-flex align-items-center d-lg-none">
            <i class="mobile-nav-toggle bi bi-list fs-3"></i>
        </div>
    </div>

    <!-- MOBILE NAV MENU -->
    <nav id="navmenu" class="mobile-nav d-lg-none bg-white shadow-sm">
        <ul class="list-unstyled mb-0 p-3">
            <!-- Menu Utama -->
            <li><a href="{{ route('Home') }}" class="d-block py-2 {{ request()->routeIs('Home') ? 'text-success fw-bold' : 'text-dark' }}">Beranda</a></li>
            <li><a href="{{ route('About') }}" class="d-block py-2 {{ request()->is('about') ? 'text-success fw-bold' : 'text-dark' }}">Tentang Kami</a></li>
            <li><a href="{{ url('/product') }}" class="d-block py-2 {{ request()->is('service') ? 'text-success fw-bold' : 'text-dark' }}">Produk</a></li>
            <li><a href="{{ url('/blog') }}" class="d-block py-2 {{ request()->is('blog') ? 'text-success fw-bold' : 'text-dark' }}">Berita</a></li>
            <li><a href="{{ url('/contact') }}" class="d-block py-2 {{ request()->is('contact') ? 'text-success fw-bold' : 'text-dark' }}">Kontak</a></li>

            @auth
                <hr>
                <!-- Pesan & Cart -->
                <li>
                    <a href="{{ route('message.index') }}" class="d-block py-2 position-relative">
                        <i class="bi bi-envelope me-2"></i> Pesan
                        @if ($unreadCount > 0)
                            <span class="badge rounded-pill bg-danger ms-2">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ url('/cart') }}" class="d-block py-2 position-relative">
                        <i class="bi bi-cart3 me-2"></i> Keranjang
                        @if ($cartCount > 0)
                            <span class="badge rounded-pill bg-success ms-2">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>

                <hr>
                <!-- Profile & Dropdown -->
                <li>
                    <button type="button" class="d-flex align-items-center justify-content-between py-2 text-dark toggle-dropdown">
                        <span><i class="bi bi-person-circle me-2"></i> Profil</span>
                        <i class="bi bi-chevron-down small"></i>
                    </button>
                    <ul class="dropdown list-unstyled ps-3">
                        <li><a href="{{ route('profile.show') }}" class="d-block py-2"><i class="bi bi-person me-2"></i> Profile</a></li>
                        <li><a href="#" class="d-block py-2"><i class="bi bi-gear me-2"></i> Settings</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="d-block py-2 bg-transparent border-0 text-start w-100">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @endauth

            @guest
                <hr>
                <li><a href="{{ route('login') }}" class="d-block py-2"><i class="bi bi-box-arrow-in-right me-2"></i> Login</a></li>
            @endguest
        </ul>
    </nav>
</header>
