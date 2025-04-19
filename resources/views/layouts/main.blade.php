<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Perpustakaan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --accent-color: #ffc107;
        }

        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
        }

        .content {
            flex: 1;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/images/library-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 5rem 0;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .book-cover {
            height: 250px;
            object-fit: cover;
        }

        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }

        .stock-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
    </style>
    @yield('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-book-open me-2"></i>
                Perpustakaan Sekolah
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('books.*') ? 'active' : '' }}"
                            href="{{ route('books.index') }}">Katalog Buku</a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('loans.*') ? 'active' : '' }}"
                                href="{{ route('loans.index') }}">Peminjaman Saya</a>
                        </li>

                        @auth
                            @if(Auth::user()->hasRole('guru'))
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('book-requests.index') ? 'active' : '' }}"
                                        href="{{ route('book-requests.index') }}">Daftar Permintaan Buku</a>
                                </li>
                            @endif
                        @endauth

                    @endauth
                </ul>

                <div class="d-flex align-items-center">
                    @auth
                        <div class="dropdown">
                            <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profil Saya</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                    @endauth
                </div>
            </div>


        </div>
    </nav>

    <main class="content">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>Perpustakaan Sekolah</h5>
                    <p>Memfasilitasi akses mudah ke sumber pengetahuan untuk meningkatkan literasi dan pembelajaran
                        sepanjang hayat.</p>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>Jam Operasional</h5>
                    <p>Senin - Jumat: 08.00 - 16.00<br>
                        Sabtu: 09.00 - 13.00<br>
                        Minggu: Tutup</p>
                </div>
                <div class="col-lg-4">
                    <h5>Kontak Kami</h5>
                    <p>
                        <i class="fas fa-map-marker-alt me-2"></i> Jl. Pendidikan No. 123<br>
                        <i class="fas fa-phone me-2"></i> (021) 123-4567<br>
                        <i class="fas fa-envelope me-2"></i> perpustakaan@sekolah.ac.id
                    </p>
                </div>
            </div>
            <hr class="mt-4 mb-4 bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Perpustakaan Sekolah. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>