@extends('layouts.main')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <!-- Hero Section -->
    <div class="hero-section pt-lg-5">
        <div class="container">
            <div class="row min-vh-lg-75 align-items-center">
                <div class="col-lg-6 text-lg-start text-start mb-4 mb-lg-0">
                    <div class="hero-content-wrapper">
                        <h1 class="display-4 fw-bold text-white mb-3">Selamat Datang di<br><span
                                class="text-warning">SI-TARLIB</span></h1>
                        <p class="lead text-white-80 mb-4">Platform Informasi Digital Perpustakaan SMAN Taruna Kasuari
                            Nusantara Papua Barat
                        </p>

                        <!-- Search Section -->
                        <div class="search-section mb-4">
                            <form action="{{ route('books.index') }}" method="GET">
                                <div class="input-group shadow">
                                    <div class="search-input-wrapper" style="position: relative; flex: 1;">
                                        <input type="text" class="form-control form-control-lg border-0 py-3"
                                            id="searchInput" placeholder="Cari judul buku, pengarang, atau kategori..."
                                            name="search" value="{{ request('search') }}" autocomplete="off">
                                        <div id="searchSuggestions" class="search-suggestions"></div>
                                    </div>

                                    <div class="dropdown dropdown-custom"> <!-- Tambahkan class dropdown-custom -->
                                        <button class="btn btn-white dropdown-toggle border-0 py-3" type="button"
                                            id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span id="selectedCategory">Semua</span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                                            <li>
                                                <a class="dropdown-item category-option" href="#" data-value="">Semua
                                                    Kategori</a>
                                            </li>
                                            @foreach ($categories as $category)
                                                <li>
                                                    <a class="dropdown-item category-option" href="#"
                                                        data-value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <input type="hidden" name="category" id="categoryInput" value="">
                                    </div>


                                    <button class="btn search-btn px-4" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                            <div class="mt-3">
                                <span class="text-white-50">Kategori Populer:</span>
                                <a href="{{ route('books.index', ['category' => 'fiksi']) }}"
                                    class="badge bg-light text-primary text-decoration-none mx-1 pill-badge">Fiksi</a>
                                <a href="{{ route('books.index', ['category' => 'sains']) }}"
                                    class="badge bg-light text-primary text-decoration-none mx-1 pill-badge">Sains</a>
                                <a href="{{ route('books.index', ['category' => 'sejarah']) }}"
                                    class="badge bg-light text-primary text-decoration-none mx-1 pill-badge">Sejarah</a>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="{{ route('books.index') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                                <i class="fas fa-book-open me-2"></i> Jelajahi Koleksi
                            </a>
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-warning btn-lg px-4 rounded-pill">
                                    <i class="fas fa-sign-in-alt me-2"></i> Masuk & Pinjam
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center">
                    <div class="hero-image-container">
                        {{-- <img src="/images/library-illustration.svg" alt="Library Illustration"
                            class="img-fluid hero-illustration"
                            onerror="this.src='/images/library-bg.jpg'; this.onerror=null;"> --}}
                        <div class="floating-badges">
                            <div class="badge-item bg-white shadow-lg p-3 rounded-circle">
                                <i class="fas fa-book fa-2x text-primary"></i>
                            </div>
                            <div class="badge-item bg-white shadow-lg p-3 rounded-circle">
                                <i class="fas fa-graduation-cap fa-2x text-warning"></i>
                            </div>
                            <div class="badge-item bg-white shadow-lg p-3 rounded-circle">
                                <i class="fas fa-laptop fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1"
                    d="M0,160L48,170.7C96,181,192,203,288,197.3C384,192,480,160,576,154.7C672,149,768,171,864,176C960,181,1056,171,1152,149.3C1248,128,1344,96,1392,80L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>
    </div>

    <!-- Fitur Utama -->
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 text-center p-4 feature-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="feature-icon-wrapper mb-4">
                            <i class="fas fa-book fa-3x text-primary"></i>
                        </div>
                        <h3>Koleksi Lengkap</h3>
                        <p class="text-muted">Ribuan koleksi buku dari berbagai kategori untuk mendukung pembelajaran Anda
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 text-center p-4 feature-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="feature-icon-wrapper mb-4">
                            <i class="fas fa-sync-alt fa-3x text-primary"></i>
                        </div>
                        <h3>Peminjaman Mudah</h3>
                        <p class="text-muted">Proses peminjaman dan pengembalian buku yang cepat dan efisien</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 feature-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="feature-icon-wrapper mb-4">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <h3>Ruang Baca Nyaman</h3>
                        <p class="text-muted">Area baca yang nyaman dan kondusif untuk belajar dan mengerjakan tugas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buku Terbaru -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">🆕 Buku Terbaru</h2>
                <p class="text-muted">Koleksi buku-buku terbaru yang telah ditambahkan ke perpustakaan kami</p>
            </div>

            @foreach ($latestBooks as $book)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 position-relative book-card">
                        <!-- Badge Stok -->
                        <span
                            class="badge position-absolute top-0 start-0 m-2 bg-{{ $book->availableStock > 0 ? 'success' : 'secondary' }}">
                            {{ $book->availableStock > 0 ? 'Tersedia' : 'Kosong' }}
                        </span>
                        <!-- Badge Kategori -->
                        <span class="badge position-absolute top-0 end-0 m-2 bg-primary">
                            {{ $book->category ? $book->category->name : 'Tidak ada Kategori' }}
                        </span>


                        <!-- Cover -->
                        <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/default-book-cover.png') }}"
                            class="card-img-top object-fit-cover" alt="{{ $book->title }}">

                        <!-- Info -->
                        <div class="card-body">
                            <h5 class="card-title text-truncate" title="{{ $book->title }}">{{ $book->title }}</h5>
                            <p class="text-muted small mb-1">{{ $book->author }}</p>
                            <p class="text-muted small">{{ $book->publication_year }}</p>
                        </div>

                        <!-- Tombol -->
                        <div class="card-footer bg-white border-top-0">
                            <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary w-100 btn-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <!-- Kategori -->
        <div class="row mb-5">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold mb-3">Jelajahi Koleksi Kami</h2>
                <p class="text-muted lead">Temukan bacaan favorit Anda dari berbagai kategori yang tersedia</p>
                <div class="mx-auto section-divider"></div>
            </div>

            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ route('books.index', ['category' => $category->id]) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm category-card">
                                <div class="card-body p-4">
                                    @php
                                        // Ambil ikon dan warna dari kategori
                                        $icon = $category->icon ?? 'fa-book';
                                        $color = $category->color ?? 'text-primary';
                                        $barColor = $category->color ?? '#0d6efd';
                                    @endphp

                                    <i class="fas {{ $icon }} fa-2x {{ $color }} mb-3"></i>
                                    <h5 class="card-title fw-bold">{{ $category->name }}</h5>
                                    <p class="card-text text-muted">{{ $categoryCounts[$category->name] ?? 0 }} buku
                                        tersedia</p>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="small text-primary">Lihat semua</span>
                                        <i class="fas fa-arrow-right text-primary"></i>
                                    </div>
                                </div>
                                <div class="category-indicator" style="background-color: {{ $barColor }};"></div>
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
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.95), rgba(9, 84, 195, 0.9)), url('/images/library-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 0;
            position: relative;
            overflow: hidden;
            min-height: 600px;
        }

        .hero-content-wrapper {
            padding: 3rem 0;
            position: relative;
            z-index: 2;
        }

        .hero-section h1 {
            font-weight: 800;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hero-section .lead {
            font-size: 1.2rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .hero-image-container {
            position: relative;
            height: 100%;
            padding: 2rem;
        }

        .hero-illustration {
            max-height: 500px;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.2));
            animation: float 6s ease-in-out infinite;
        }

        .floating-badges {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .badge-item {
            position: absolute;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 4s ease-in-out infinite;
        }

        .badge-item:nth-child(1) {
            top: 20%;
            left: 15%;
            animation-delay: 0.5s;
        }

        .badge-item:nth-child(2) {
            top: 60%;
            left: 10%;
            animation-delay: 1s;
        }

        .badge-item:nth-child(3) {
            top: 40%;
            right: 15%;
            animation-delay: 1.5s;
        }

        .hero-wave {
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* Search Section */
        /* .search-section .input-group {
                                                                                                    border-radius: 0.5rem;
                                                                                                    background-color: white;
                                                                                                    max-width: 600px;
                                                                                                    height: 50px;
                                                                                                } */
        /* Search Section */
        .search-section .input-group {
            border-radius: 2rem;
            background-color: white;
            max-width: 600px;
            position: relative;
        }

        /* Custom Dropdown */
        .search-section .dropdown-custom {
            position: relative;
            display: flex;
            align-items: stretch;
        }

        .search-section .dropdown-custom .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            left: 0;
            max-height: 300px;
            overflow-y: auto;
            width: auto;
            min-width: 40%;
            z-index: 1050;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f1f1;
            padding: 0.5rem;
            margin-top: 0.25rem;
            transform: none !important;
        }

        .search-section .form-control {
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            box-shadow: none;
        }

        .search-section .form-control:focus {
            box-shadow: none;
        }

        .search-section .dropdown {
            position: static;
            /* Ubah dari relative ke static */
        }

        /* Dropdown toggle */
        .search-section .dropdown-toggle {
            border-left: 1px solid #dee2e6;
            min-width: 140px;
            font-size: 0.95rem;
            height: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #495057;
            padding: 0 1rem;
            background: white;
        }

        .search-section .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            width: 100%;
            min-width: 180px;
            /* Lebar minimum yang cukup */
            z-index: 1050;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f1f1;
            padding: 0.5rem;
            margin-top: 0.25rem;
            position: absolute;
            right: 0;
            top: 100% !important;
            /* Pastikan muncul di bawah */
            left: auto !important;
            /* Pastikan posisi horizontal benar */
            transform: none !important;
            /* Nonaktifkan transform Bootstrap */
        }


        /* Dropdown items */
        .search-section .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            white-space: nowrap;
        }

        .search-section .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }

        /* Untuk mencegah scrolling horizontal pada dropdown */
        .search-section .dropdown-menu::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .search-section .dropdown-menu::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .search-section .dropdown-menu::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        /* Search button */
        .search-section .search-btn {
            background-color: #E4A11B;
            border: none;
            color: white;
            padding: 0 1.5rem;
            border-radius: 0 0.5rem 0.5rem 0;
            z-index: 1;
        }

        .search-section .search-btn:hover {
            background-color: #F1B84D;
        }

        /* Scrollbar styling */
        .search-section .dropdown-menu::-webkit-scrollbar {
            width: 6px;
        }

        .search-section .dropdown-menu::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .pill-badge {
            padding: 0.35rem 0.8rem;
            border-radius: 2rem;
            font-weight: normal;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .pill-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


        /* Cards */
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 1rem;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .feature-icon-wrapper {
            background-color: rgba(13, 110, 253, 0.1);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .book-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 1rem;
            overflow: hidden;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .book-card .card-img-top {
            height: 350px;
            object-fit: fill;
        }

        .category-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 1rem;
            overflow: hidden;
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .category-indicator {
            height: 4px;
        }

        .section-divider {
            width: 60px;
            height: 4px;
            background-color: #0d6efd;
            margin-bottom: 30px;
            border-radius: 2px;
        }

        /* Suggestion styles */
        .search-input-wrapper {
            position: relative;
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            background: #ffffff;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            z-index: 1051;
            display: none;
        }

        .suggestion-item {
            padding: 12px 20px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s ease;
            color: #495057;
        }

        .suggestion-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }

        /* Desktop Optimization */
        @media (min-width: 992px) {
            .hero-section {
                min-height: 100vh;
                display: flex;
                align-items: center;
            }

            .min-vh-lg-75 {
                min-height: 75vh;
            }

            .pt-lg-5 {
                padding-top: 3rem !important;
            }
        }

        /* Mobile Optimization */
        @media (max-width: 991.98px) {
            .hero-section {
                padding-top: 3rem;
                padding-bottom: 3rem;
            }

            .hero-section h1.display-4 {
                font-size: 2.5rem;
            }

            .hero-section .lead {
                font-size: 1.1rem;
            }

            .hero-section .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }

            .search-section .form-control-lg {
                font-size: 1rem;
                padding: 0.75rem 1rem;
            }

            .search-section .dropdown-toggle {
                padding: 0.75rem 1rem;
            }

            .search-section .search-btn {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Ensure text alignment */
        .text-lg-start.text-start {
            text-align: left !important;
        }
    </style>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Existing code for category selection
            const categoryOptions = document.querySelectorAll('.category-option');
            const selectedCategory = document.getElementById('selectedCategory');
            const categoryInput = document.getElementById('categoryInput');
            const dropdownToggle = document.getElementById('categoryDropdown');

            // New code for search suggestions
            const searchInput = document.getElementById('searchInput');
            const suggestionsContainer = document.getElementById('searchSuggestions');

            // Initialize dropdown
            if (dropdownToggle) {
                new bootstrap.Dropdown(dropdownToggle);
            }

            // Handle category selection
            categoryOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const value = this.getAttribute('data-value');
                    const text = this.textContent.trim();

                    selectedCategory.textContent = text;
                    categoryInput.value = value;
                });
            });

            // Search suggestions functionality
            let debounceTimer;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    const category = categoryInput.value;

                    clearTimeout(debounceTimer);

                    if (query.length < 2) {
                        suggestionsContainer.style.display = 'none';
                        return;
                    }

                    // Debounce to prevent too many requests
                    debounceTimer = setTimeout(() => {
                        fetchSuggestions(query, category);
                    }, 300);
                });

                // Prevent suggestions from showing when input loses focus
                searchInput.addEventListener('blur', function(e) {
                    // Using setTimeout to allow click on suggestion to work before hiding
                    setTimeout(() => {
                        suggestionsContainer.style.display = 'none';
                    }, 200);
                });

                // Show suggestions again when input gains focus if there's text
                searchInput.addEventListener('focus', function() {
                    const query = this.value.trim();
                    if (query.length >= 2) {
                        const category = categoryInput.value;
                        fetchSuggestions(query, category);
                    }
                });
            }

            // Fetch suggestions function
            function fetchSuggestions(query, category) {
                // Gunakan variabel category yang benar (bukan category_name)
                fetch(
                        `/api/search-suggestions?query=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`
                    )
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        displaySuggestions(data);
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                        // For testing without backend:
                        const dummySuggestions = [
                            `${query} - ${generateRandomAuthor()} (${generateRandomYear()})`,
                            `${query} Lanjutan - ${generateRandomAuthor()} (${generateRandomYear()})`,
                            `Pengantar ${query} - ${generateRandomAuthor()} (${generateRandomYear()})`,
                            `${query} untuk Pemula - ${generateRandomAuthor()} (${generateRandomYear()})`
                        ];
                        displaySuggestions(dummySuggestions);
                    });
            }

            // Helper functions for generating random sample data
            function generateRandomAuthor() {
                const authors = ['Pramoedya Ananta Toer', 'Andrea Hirata', 'Tere Liye', 'Dee Lestari',
                    'Eka Kurniawan'
                ];
                return authors[Math.floor(Math.random() * authors.length)];
            }

            function generateRandomYear() {
                return Math.floor(Math.random() * (2023 - 2000 + 1)) + 2000;
            }

            // Display suggestions
            function displaySuggestions(suggestions) {
                suggestionsContainer.innerHTML = '';

                if (suggestions.length === 0) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }

                suggestions.forEach(suggestion => {
                    const item = document.createElement('div');
                    item.className = 'suggestion-item';

                    // Periksa apakah suggestion adalah string atau objek
                    if (typeof suggestion === 'string') {
                        item.textContent = suggestion;

                        item.addEventListener('click', function() {
                            searchInput.value = suggestion;
                            suggestionsContainer.style.display = 'none';
                        });
                    } else {
                        // Format: Title - Author (Year)
                        const year = suggestion.publication_year ? ` (${suggestion.publication_year})` : '';
                        const displayText = `${suggestion.title} - ${suggestion.author}${year}`;

                        item.textContent = displayText;

                        item.addEventListener('click', function() {
                            // Saat diklik, isi hanya dengan judul buku
                            searchInput.value = suggestion.title;
                            suggestionsContainer.style.display = 'none';

                            // Atau jika ingin mengisi dengan format lengkap:
                            // searchInput.value = displayText;
                        });
                    }

                    suggestionsContainer.appendChild(item);
                });

                suggestionsContainer.style.display = 'block';
            }

            // Set initial value if in URL (existing code)
            const urlParams = new URLSearchParams(window.location.search);
            const initialCategory = urlParams.get('category');
            if (initialCategory) {
                const activeOption = document.querySelector(`.category-option[data-value="${initialCategory}"]`);
                if (activeOption) {
                    selectedCategory.textContent = activeOption.textContent.trim();
                    categoryInput.value = initialCategory;
                }
            }
        });
    </script>
@endsection
