@extends('layouts.main')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section Baru yang Ditingkatkan -->
    <div class="hero-section position-relative">
        <div class="overlay"></div>
        <div class="container position-relative z-3">
            <div class="row min-vh-75 align-items-center">
                <div class="col-lg-7 text-center text-lg-start">
                    <h1 class="display-4 fw-bold mb-3 hero-title">Selamat Datang di<br>Perpustakaan Sekolah</h1>
                    <p class="lead mb-4 hero-subtitle">Temukan ribuan buku untuk menambah wawasan dan pengetahuan Anda</p>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-search me-2"></i>Jelajahi Katalog
                        </a>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk untuk Meminjam
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <!-- <div class="hero-card">
                                <div class="card border-0 shadow hero-stats-card">
                                    <div class="card-body p-4">
                                        <div class="row g-4">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <i class="fas fa-book-open fa-2x text-primary mb-2"></i>
                                                    <h2 class="mb-1">{{ $totalBooks ?? '1000+' }}</h2>
                                                    <p class="mb-0 text-muted">Judul Buku</p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                                    <h2 class="mb-1">{{ $activeMembers ?? '500+' }}</h2>
                                                    <p class="mb-0 text-muted">Anggota Aktif</p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <i class="fas fa-list-alt fa-2x text-primary mb-2"></i>
                                                    <h2 class="mb-1">{{ count($categories) }}</h2>
                                                    <p class="mb-0 text-muted">Kategori</p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <i class="fas fa-sync-alt fa-2x text-primary mb-2"></i>
                                                    <h2 class="mb-1">{{ $activeLoans ?? '100+' }}</h2>
                                                    <p class="mb-0 text-muted">Peminjaman</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="fas fa-book fa-3x text-primary"></i>
                        </div>
                        <h3>Koleksi Lengkap</h3>
                        <p class="text-muted">Ribuan koleksi buku dari berbagai kategori untuk mendukung pembelajaran Anda
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="fas fa-sync-alt fa-3x text-primary"></i>
                        </div>
                        <h3>Peminjaman Mudah</h3>
                        <p class="text-muted">Proses peminjaman dan pengembalian buku yang cepat dan efisien</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <h3>Ruang Baca Nyaman</h3>
                        <p class="text-muted">Area baca yang nyaman dan kondusif untuk belajar dan mengerjakan tugas</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">🆕 Buku Terbaru</h2>
                <p class="text-muted">Koleksi buku-buku terbaru yang telah ditambahkan ke perpustakaan kami</p>
            </div>

            @foreach($latestBooks as $book)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 position-relative hover-shadow transition">

                        <!-- Badge Ketersediaan -->
                        <span
                            class="badge position-absolute top-0 start-0 m-2 bg-{{ $book->availableStock > 0 ? 'success' : 'secondary' }}">
                            {{ $book->availableStock > 0 ? 'Tersedia' : 'Kosong' }}
                        </span>

                        <!-- Badge Kategori -->
                        <span class="badge position-absolute top-0 end-0 m-2 bg-primary">
                            {{ $book->category }}
                        </span>

                        <!-- Gambar Cover -->
                        <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/default-book-cover.png') }}"
                            class="card-img-top object-fit-cover" alt="{{ $book->title }}" style="height: 220px;">

                        <!-- Info Buku -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="{{ $book->title }}">{{ $book->title }}</h5>
                            <p class="text-muted small mb-1">{{ $book->author }}</p>
                            <p class="text-muted small">{{ $book->publication_year }}</p>
                        </div>

                        <!-- Tombol Detail -->
                        <div class="card-footer bg-white border-top-0">
                            <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary w-100 btn-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="container py-5">
            <!-- Header -->
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">Jelajahi Koleksi Kami</h2>
                    <p class="text-muted lead">Temukan bacaan favorit Anda dari berbagai kategori yang tersedia</p>
                    <div class="mx-auto" style="width: 50px; height: 4px; background-color: #0d6efd; margin-bottom: 30px;">
                    </div>
                </div>
            </div>

            <!-- Kategori -->
            <div class="row g-4">
                @foreach($categories as $category)
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ route('books.index', ['category' => $category]) }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm category-card transition-hover">
                                        <div class="card-body p-4">
                                            <!-- Ikon Kategori -->
                                            <div class="category-icon mb-3">
                                                @php
                                                    $iconMap = [
                                                        'fiksi' => ['icon' => 'fa-book-open', 'color' => 'text-primary', 'bar' => '#0d6efd'],
                                                        'non-fiksi' => ['icon' => 'fa-landmark', 'color' => 'text-success', 'bar' => '#198754'],
                                                        'pendidikan' => ['icon' => 'fa-graduation-cap', 'color' => 'text-info', 'bar' => '#0dcaf0'],
                                                        'teknologi' => ['icon' => 'fa-laptop-code', 'color' => 'text-danger', 'bar' => '#dc3545'],
                                                        'bisnis' => ['icon' => 'fa-chart-line', 'color' => 'text-warning', 'bar' => '#ffc107'],
                                                        'sastra' => ['icon' => 'fa-feather-alt', 'color' => 'text-secondary', 'bar' => '#6c757d'],
                                                    ];
                                                    $slug = strtolower($category);
                                                    $icon = $iconMap[$slug]['icon'] ?? 'fa-book';
                                                    $color = $iconMap[$slug]['color'] ?? 'text-primary';
                                                    $barColor = $iconMap[$slug]['bar'] ?? '#0d6efd';
                                                @endphp
                                                <i class="fas {{ $icon }} fa-2x {{ $color }}"></i>
                                            </div>

                                            <!-- Judul dan Jumlah -->
                                            <h5 class="card-title fw-bold">{{ $category }}</h5>
                                            <p class="card-text text-muted">{{ $categoryCounts[$category] ?? 0 }} buku tersedia</p>

                                            <!-- CTA -->
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="small text-primary">Lihat semua</span>
                                                <i class="fas fa-arrow-right text-primary"></i>
                                            </div>
                                        </div>

                                        <!-- Bar Indikator Kategori -->
                                        <div class="category-indicator" style="height: 4px; background-color: {{ $barColor }};"></div>
                                    </div>
                                </a>
                            </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Style untuk Hero Section yang ditingkatkan */
        .hero-section {
            background: linear-gradient(rgba(13, 110, 253, 0.8), rgba(13, 110, 253, 0.9)), url('/images/library-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.9) 0%, rgba(0, 40, 120, 0.8) 100%);
            z-index: 1;
        }

        .min-vh-75 {
            min-height: 75vh;
        }

        .hero-title {
            font-size: 3.5rem;
            letter-spacing: -0.5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 1s ease 0.3s;
            animation-fill-mode: both;
        }

        .hero-card {
            margin-top: 2rem;
            animation: fadeIn 1s ease 0.5s;
            animation-fill-mode: both;
        }

        .hero-stats-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hero-stats-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .z-3 {
            z-index: 3;
        }

        /* Animasi */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Media queries */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .min-vh-75 {
                min-height: 60vh;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .min-vh-75 {
                min-height: 50vh;
            }
        }
    </style>
@endsection