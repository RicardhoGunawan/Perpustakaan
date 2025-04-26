@extends('layouts.main')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section dengan Dropdown Responsif -->
    <div class="hero-section position-relative">
        <div class="overlay"></div>
        <div class="container position-relative z-3">
            <div class="row min-vh-75 align-items-center">
                <div class="col-lg-7 text-center text-lg-start">
                    <h1 class="display-4 fw-bold mb-3 hero-title">Selamat Datang di<br><span class="">SI-TARLIB</span>
                    </h1>
                    <p class="lead mb-4 hero-subtitle">Platform Informasi Digital Perpustakaan SMAN Taruna Kasuari Nusantara
                        Papua Barat</p>

                    <!-- Search Section Responsif -->
                    <div class="search-section mb-4">
                        <form action="{{ route('books.index') }}" method="GET">
                            <div class="input-group shadow-sm search-container">
                                <input type="text" class="form-control border-0 py-2 search-input"
                                    placeholder="Cari judul buku, pengarang, atau kategori..." name="search"
                                    value="{{ request('search') }}" aria-label="Search books">

                                <!-- Dropdown hanya muncul di desktop -->
                                <div class="d-none d-lg-block">
                                    <button class="btn btn-white dropdown-toggle px-3" type="button" id="categoryDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="selectedCategory" class="text-dark">Semua</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="categoryDropdown">
                                        <li><a class="dropdown-item category-option" href="#" data-value="">Semua
                                                Kategori</a></li>
                                        <li><a class="dropdown-item category-option" href="#" data-value="K13">Teks
                                                K13</a></li>
                                        <li><a class="dropdown-item category-option" href="#"
                                                data-value="merdeka">Kurikulum Merdeka</a></li>
                                        <li><a class="dropdown-item category-option" href="#"
                                                data-value="nonteks">Nonteks</a></li>
                                    </ul>
                                    <input type="hidden" name="category" id="categoryInput" value="">
                                </div>

                                <button class="btn btn-white px-3 search-btn" type="submit">
                                    <i class="fas fa-search text-dark"></i>
                                </button>
                            </div>
                        </form>
                        <div class="mt-2 text-white-50">
                            <small>
                                Kategori Populer:
                                <a href="{{ route('books.index', ['category' => 'fiksi']) }}"
                                    class="text-white text-decoration-none mx-1">Fiksi</a> •
                                <a href="{{ route('books.index', ['category' => 'sains']) }}"
                                    class="text-white text-decoration-none mx-1">Sains</a> •
                                <a href="{{ route('books.index', ['category' => 'sejarah']) }}"
                                    class="text-white text-decoration-none mx-1">Sejarah</a>
                            </small>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('books.index') }}" class="btn btn-outline-light btn-sm px-3">
                            <i class="fas fa-book-open me-1"></i> Jelajahi Koleksi
                        </a>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-light btn-sm px-3">
                                <i class="fas fa-sign-in-alt me-1"></i> Masuk & Pinjam
                            </a>
                        @endguest
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <!-- Tetap kosong -->
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

            @foreach ($latestBooks as $book)
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
                    <div class="mx-auto"
                        style="width: 50px; height: 4px; background-color: #0d6efd; margin-bottom: 30px;">
                    </div>
                </div>
            </div>

            <!-- Kategori -->
            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ route('books.index', ['category' => $category]) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm category-card transition-hover">
                                <div class="card-body p-4">
                                    <!-- Ikon Kategori -->
                                    <div class="category-icon mb-3">
                                        @php
                                            $iconMap = [
                                                'fiksi' => [
                                                    'icon' => 'fa-book-open',
                                                    'color' => 'text-primary',
                                                    'bar' => '#0d6efd',
                                                ],
                                                'non-fiksi' => [
                                                    'icon' => 'fa-landmark',
                                                    'color' => 'text-success',
                                                    'bar' => '#198754',
                                                ],
                                                'pendidikan' => [
                                                    'icon' => 'fa-graduation-cap',
                                                    'color' => 'text-info',
                                                    'bar' => '#0dcaf0',
                                                ],
                                                'teknologi' => [
                                                    'icon' => 'fa-laptop-code',
                                                    'color' => 'text-danger',
                                                    'bar' => '#dc3545',
                                                ],
                                                'bisnis' => [
                                                    'icon' => 'fa-chart-line',
                                                    'color' => 'text-warning',
                                                    'bar' => '#ffc107',
                                                ],
                                                'sastra' => [
                                                    'icon' => 'fa-feather-alt',
                                                    'color' => 'text-secondary',
                                                    'bar' => '#6c757d',
                                                ],
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
                                    <p class="card-text text-muted">{{ $categoryCounts[$category] ?? 0 }} buku tersedia
                                    </p>

                                    <!-- CTA -->
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="small text-primary">Lihat semua</span>
                                        <i class="fas fa-arrow-right text-primary"></i>
                                    </div>
                                </div>

                                <!-- Bar Indikator Kategori -->
                                <div class="category-indicator"
                                    style="height: 4px; background-color: {{ $barColor }};"></div>
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
        /* CSS Utama */
        .hero-section {
            background: linear-gradient(rgba(13, 110, 253, 0.8), rgba(13, 110, 253, 0.9)), url('/images/library-bg.jpg');
            background-size: cover;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        /* Search Section Responsif */
        .search-section .search-container {
            border-radius: 0.5rem;
            background-color: white;
            max-width: 600px;
            height: 50px; /* atau 3.5rem */
        }

        .search-section .search-input {
            border: none !important;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
            box-shadow: none !important;
            flex-grow: 1;
        }

        .search-section .btn-white {
            background-color: white;
            border: none;
            color: #495057;
            padding: 0.5rem 0.8rem;
        }

        .search-section .dropdown-toggle {
            border-left: 1px solid #dee2e6 !important;
            min-width: 100px;
            font-size: 0.9rem;
            height: 50px; /* atau 3.5rem */
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .search-section .search-container {
                max-width: 100%;
            }

            .search-section .search-input {
                padding: 0.7rem 1rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 767.98px) {
            .search-section .search-input {
                padding: 0.8rem 1.2rem;
            }

            .search-section .search-btn {
                padding: 0.7rem 1rem;
            }
        }

        @media (max-width: 575.98px) {
            .search-section .search-input {
                padding: 0.9rem 1.3rem;
                font-size: 1.05rem;
            }

            .search-section .form-control::placeholder {
                font-size: 0.9rem;
            }
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
    <script>
        // Script untuk dropdown kategori (sama seperti sebelumnya)
        document.addEventListener('DOMContentLoaded', function() {
            const categoryOptions = document.querySelectorAll('.category-option');
            const selectedCategory = document.getElementById('selectedCategory');
            const categoryInput = document.getElementById('categoryInput');

            categoryOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const value = this.getAttribute('data-value');
                    const text = this.textContent;

                    selectedCategory.textContent = text;
                    categoryInput.value = value;
                });
            });

            // Set nilai awal jika ada di URL
            const urlParams = new URLSearchParams(window.location.search);
            const initialCategory = urlParams.get('category');
            if (initialCategory) {
                const activeOption = document.querySelector(`.category-option[data-value="${initialCategory}"]`);
                if (activeOption) {
                    selectedCategory.textContent = activeOption.textContent;
                    categoryInput.value = initialCategory;
                }
            }
        });
    </script>
@endsection
