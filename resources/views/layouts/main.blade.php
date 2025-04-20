<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Perpustakaan Sekolah</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0b5ed7;
            --secondary-color: #6c757d;
            --accent-color: #ffc107;
            --success-color: #198754;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-800: #343a40;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--gray-100);
            color: var(--dark-color);
        }

        /* Navbar styling */
        /* Navbar styling - Enhanced for better responsiveness */
        .fixed-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1030;
            transform: translateZ(0);
            will-change: transform;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease;
            min-height: 60px;
            /* Changed from fixed height to min-height for better mobile support */
        }

        /* Spacer for navbar */
        .navbar-spacer {
            height: 60px;
        }

        /* Avoid flicker when scrolling */
        body {
            overflow-anchor: none;
        }

        /* Navbar scroll behavior */
        .scroll-down .fixed-navbar {
            transform: translateY(-100%);
        }

        .scroll-up .fixed-navbar {
            transform: translateY(0);
        }

        /* Ensure dropdowns remain visible */
        .dropdown-menu {
            transform: none !important;
        }

        .navbar {
            padding: 0.5rem 0;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-scrolled {
            padding: 0.25rem 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.25rem;
            transition: color 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--primary-dark);
        }

        .nav-link {
            font-weight: 500;
            color: var(--gray-800);
            position: relative;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-color);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 70%;
        }

        /* Mobile optimizations */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                max-height: calc(100vh - 60px);
                overflow-y: auto;
                padding-bottom: 1rem;
            }

            .navbar .nav-link {
                padding: 0.75rem 1rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .navbar .nav-link::after {
                display: none;
                /* Remove the underline effect on mobile */
            }

            .navbar .nav-link.active {
                background-color: rgba(var(--bs-primary-rgb), 0.1);
                border-radius: 0.25rem;
            }

            .user-dropdown .dropdown-menu {
                width: 100%;
                margin-top: 0.5rem;
            }

            /* Enhanced styles for mobile toggle button */
            .navbar-toggler {
                padding: 0.25rem 0.5rem;
                font-size: 1rem;
            }

            .navbar-toggler:focus {
                box-shadow: none;
            }

            /* ✅ Tambahan: Responsive dropdown akun di mobile */
            .user-dropdown .dropdown-menu-mobile-show {
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                right: 0 !important;
                width: 100vw;
                margin-top: 0.25rem;
                border-radius: 0;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1050;
            }

            .user-dropdown .dropdown-menu-mobile-show .dropdown-item {
                padding: 1rem;
                font-size: 1.1rem;
            }

            .user-dropdown>a.btn {
                width: 100%;
                justify-content: center;
                font-size: 1rem;
            }
        }

        /* Add JS to toggle the fixed-navbar visibility while scrolling */
        @media (min-width: 992px) {
            .dropdown-menu {
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                border: none;
                animation: fadeIn 0.2s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }


        /* Content main area */
        .content {
            flex: 1;
        }

        /* Cards styling */
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .book-cover {
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .card:hover .book-cover {
            transform: scale(1.05);
        }

        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            border-radius: 20px;
            padding: 0.35em 0.8em;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .stock-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            border-radius: 20px;
            padding: 0.35em 0.8em;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        /* Footer styling */
        footer {
            background-color: var(--gray-800);
            color: white;
            padding: 3rem 0 2rem;
            margin-top: auto;
        }

        footer h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-color);
        }

        footer p,
        footer a {
            color: #ced4da;
            line-height: 1.8;
        }

        footer a:hover {
            color: white;
            text-decoration: none;
        }

        .footer-contact i {
            width: 20px;
            color: var(--primary-color);
        }

        .social-icons {
            margin-top: 1rem;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Buttons styling */
        .btn {
            border-radius: 5px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        /* User dropdown */
        .user-dropdown {
            transition: all 0.3s ease;
        }

        .user-dropdown:hover {
            transform: translateY(-2px);
        }

        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Hero section base styling moved to main layout */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/library-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 5rem 0;
        }

        /* Additional utility classes */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .shadow-sm {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
        }

        .shadow-lg {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .preloader.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .preloader .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e9e9e9;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="spinner"></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-navbar">
        <div class="container">
            <!-- Logo and brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2">
                <span class="d-sm-inline">Perpustakaan</span>
            </a>

            <!-- Mobile search toggle -->
            <div class="d-lg-none ms-auto me-2">
                <button class="btn btn-sm btn-outline-secondary border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mobileSearch" aria-expanded="false">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Hamburger menu -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Mobile search bar (collapsed by default) -->
            <div class="collapse w-100 mt-2 mb-2 d-lg-none" id="mobileSearch">
                <form action="{{ route('books.index') }}" method="GET" class="d-flex w-100">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari buku..."
                            name="search" value="{{ request('search') }}">
                        <button class="btn btn-sm btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Main navbar content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home d-lg-none me-2"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('books.*') ? 'active' : '' }}"
                            href="{{ route('books.index') }}">
                            <i class="fas fa-book d-lg-none me-2"></i>Katalog Buku
                        </a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('loans.*') ? 'active' : '' }}"
                                href="{{ route('loans.index') }}">
                                <i class="fas fa-book-reader d-lg-none me-2"></i>Peminjaman Saya
                            </a>
                        </li>

                        @if (Auth::user()->hasRole('guru'))
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('book-requests.index') ? 'active' : '' }}"
                                    href="{{ route('book-requests.index') }}">
                                    <i class="fas fa-clipboard-list d-lg-none me-2"></i>Daftar Permintaan Buku
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- Desktop search form -->
                <div class="d-none d-lg-flex me-3">
                    <form action="{{ route('books.index') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari buku..." name="search"
                                value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- User menu -->
                <div class="mt-3 mt-lg-0">
                    @auth
                        <div class="dropdown user-dropdown">
                            <a class="btn btn-outline-primary dropdown-toggle d-flex align-items-center w-100 w-sm-auto"
                                href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>
                                <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                                <span class="d-inline d-sm-none">Akun</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 dropdown-menu-mobile-show">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="fas fa-user me-2 text-primary"></i> Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('loans.index') }}">
                                        <i class="fas fa-book-reader me-2 text-primary"></i> Peminjaman Saya
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="d-grid d-lg-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                <span>Masuk</span>
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-user-plus me-1"></i>
                                <span>Daftar</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <div class="navbar-spacer"></div>

    <!-- Main Content -->
    <main class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                <div class="container">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                <div class="container">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>Perpustakaan Sekolah</h5>
                    <p>Memfasilitasi akses mudah ke sumber pengetahuan untuk meningkatkan literasi dan pembelajaran
                        sepanjang hayat.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>Jam Operasional</h5>
                    <ul class="list-unstyled footer-list">
                        <li><i class="far fa-clock me-2"></i> Senin - Jumat: 08.00 - 16.00</li>
                        <li><i class="far fa-clock me-2"></i> Sabtu: 09.00 - 13.00</li>
                        <li><i class="far fa-clock me-2"></i> Minggu: Tutup</li>
                    </ul>
                    <h5 class="mt-4">Tautan Cepat</h5>
                    <ul class="list-unstyled footer-list">
                        <li><a href="{{ route('home') }}"><i class="fas fa-angle-right me-2"></i> Beranda</a></li>
                        <li><a href="{{ route('books.index') }}"><i class="fas fa-angle-right me-2"></i> Katalog
                                Buku</a></li>
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i> Tentang Kami</a></li>
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i> Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5>Kontak Kami</h5>
                    <ul class="list-unstyled footer-contact">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Pendidikan No. 123</li>
                        <li><i class="fas fa-phone me-2"></i> (021) 123-4567</li>
                        <li><i class="fas fa-envelope me-2"></i> perpustakaan@sekolah.ac.id</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4 mb-4 bg-secondary opacity-25">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Perpustakaan Sekolah. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Script libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <!-- Custom script -->
    <script>
        // Initialize AOS animation
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Preloader
            window.addEventListener('load', function() {
                const preloader = document.querySelector('.preloader');
                preloader.classList.add('fade-out');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            });

            // Variables for the scroll detection
            let lastScrollTop = 0;
            const fixedNavbar = document.querySelector('.fixed-navbar');
            const navbar = document.querySelector('.navbar');
            const scrollThreshold = 10;

            // Handle scroll events
            window.addEventListener('scroll', function() {
                let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

                // If scrolled down and past the threshold - hide navbar
                if (currentScroll > lastScrollTop && currentScroll > scrollThreshold) {
                    document.body.classList.add('scroll-down');
                    document.body.classList.remove('scroll-up');
                }
                // If scrolled up - show navbar
                else if (currentScroll < lastScrollTop) {
                    document.body.classList.remove('scroll-down');
                    document.body.classList.add('scroll-up');
                }

                // Add scrolled class for styling
                if (currentScroll > 100) {
                    navbar.classList.add('navbar-scrolled');
                    if (fixedNavbar) fixedNavbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                    if (fixedNavbar) fixedNavbar.classList.remove('navbar-scrolled');
                }

                lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // For Mobile or negative scrolling
            }, {
                passive: true
            });

            // Close the mobile menu when a link is clicked
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            const menuToggle = document.getElementById('navbarNav');

            if (menuToggle) {
                const bsCollapse = new bootstrap.Collapse(menuToggle, {
                    toggle: false
                });

                navLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (menuToggle.classList.contains('show')) {
                            bsCollapse.toggle();
                        }
                    });
                });
            }

            // Close mobile search when clicking outside
            const mobileSearch = document.getElementById('mobileSearch');
            if (mobileSearch) {
                document.addEventListener('click', function(event) {
                    const isClickInside = mobileSearch.contains(event.target) ||
                        event.target.getAttribute('data-bs-target') === '#mobileSearch';

                    if (!isClickInside && mobileSearch.classList.contains('show')) {
                        const bsSearchCollapse = new bootstrap.Collapse(mobileSearch);
                        bsSearchCollapse.hide();
                    }
                });
            }

            // Back to top button
            const backToTopButton = document.getElementById('backToTop');
            if (backToTopButton) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        backToTopButton.classList.add('show');
                    } else {
                        backToTopButton.classList.remove('show');
                    }
                });

                backToTopButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
