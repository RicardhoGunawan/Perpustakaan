@extends('layouts.main')

@section('title', 'Katalog Buku')

@section('styles')
    <style>
        .book-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .book-cover {
            height: 300px;
            object-fit: fill;
        }

        .stock-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
            font-size: 0.75rem;
            padding: 0.5em 0.75em;
            border-radius: 20px;
        }

        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            font-size: 0.75rem;
            padding: 0.5em 0.75em;
            border-radius: 20px;
        }

        .search-container {
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            border: none;
            padding-left: 20px;
        }

        .search-input:focus {
            box-shadow: none;
        }

        .search-btn {
            border-radius: 0 50px 50px 0;
            padding-right: 20px;
        }

        .filter-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .pagination .page-link {
            color: #0d6efd;
        }

        .empty-state {
            padding: 60px 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
        }

        .book-title {
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            height: 48px;
        }

        .book-author {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .book-info {
            font-size: 0.8rem;
            color: #adb5bd;
        }

        .filter-label {
            font-weight: 500;
            margin-bottom: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="bg-primary bg-gradient text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">Katalog Buku</h1>
                    <p class="lead mb-0">Telusuri koleksi buku-buku berkualitas kami</p>
                </div>
                <div class="col-md-4 d-none d-md-block text-end">
                    <i class="fas fa-books fa-4x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="filter-container shadow-sm mb-5">
            <div class="row g-3">
                <!-- Search Bar -->
                <div class="col-lg-6">
                    <p class="filter-label"><i class="fas fa-search me-2"></i>Pencarian</p>
                    <form action="{{ route('books.index') }}" method="GET" class="d-flex search-container">
                        <input type="text" name="search" class="form-control search-input"
                            placeholder="Cari judul buku, penulis, atau ISBN..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary search-btn">Cari</button>
                    </form>
                </div>

                <!-- Category Filter -->
                <div class="col-lg-3">
                    <p class="filter-label"><i class="fas fa-tag me-2"></i>Kategori</p>
                    <select class="form-select" id="categorySelect" onchange="window.location = this.value;">
                        <option value="{{ route('books.index') }}" {{ !request('category') ? 'selected' : '' }}>Semua
                            Kategori</option>
                        @foreach ($categories as $id => $name)
                            <option value="{{ route('books.index', ['category' => $id]) }}"
                                {{ request('category') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <!-- Availability Filter -->
                <div class="col-lg-3">
                    <p class="filter-label"><i class="fas fa-book-open me-2"></i>Ketersediaan</p>
                    <div class="btn-group w-100">
                        <input type="radio" class="btn-check" name="stock-filter" id="all-books" autocomplete="off"
                            checked>
                        <label class="btn btn-outline-primary" for="all-books">Semua</label>

                        <input type="radio" class="btn-check" name="stock-filter" id="available-books" autocomplete="off">
                        <label class="btn btn-outline-primary" for="available-books">Tersedia</label>
                    </div>
                </div>
            </div>
        </div>

        @if ($books->isEmpty())
            <div class="empty-state shadow-sm text-center">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h3>Buku tidak ditemukan</h3>
                <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-sync-alt me-2"></i>Lihat Semua Buku
                </a>
            </div>
        @else
            <div class="row">
                @foreach ($books as $book)
                    <div class="col-sm-6 col-lg-3 mb-4 book-card"
                        data-available="{{ $book->availableStock > 0 ? 'yes' : 'no' }}">
                        <div class="card h-100 border-0 shadow-sm position-relative">
                            <span class="badge bg-{{ $book->availableStock > 0 ? 'success' : 'danger' }} stock-badge">
                                {{ $book->availableStock > 0 ? 'Tersedia' : 'Dipinjam' }}
                            </span>
                            <span class="badge position-absolute top-0 end-0 m-2 bg-primary">
                                {{ $book->category ? $book->category->name : 'Tidak ada Kategori' }}
                            </span>                            <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/default-book-cover.png') }}"
                                class="card-img-top book-cover" alt="{{ $book->title }}">
                            <div class="card-body">
                                <h5 class="card-title book-title">{{ $book->title }}</h5>
                                <p class="book-author">{{ $book->author }}</p>
                                <p class="book-info">
                                    <i class="far fa-calendar-alt me-1"></i> {{ $book->publication_year }}
                                    <span class="mx-2">•</span>
                                    <i class="far fa-building me-1"></i> {{ $book->publisher }}
                                </p>
                            </div>
                            <div class="card-footer bg-white border-top-0 pt-0">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-info-circle me-1"></i>Detail Buku
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($books->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="Pagination">
                        <ul class="pagination pagination-md">
                            {{-- Previous Page Link --}}
                            @if ($books->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $books->previousPageUrl() }}" rel="prev">&laquo;</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                                @if ($page == $books->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($books->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $books->nextPageUrl() }}" rel="next">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allBooksRadio = document.getElementById('all-books');
            const availableBooksRadio = document.getElementById('available-books');
            const bookCards = document.querySelectorAll('.book-card');

            // Set initial state based on URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('availability') && urlParams.get('availability') === 'available') {
                availableBooksRadio.checked = true;
                filterBooks('available');
            } else {
                allBooksRadio.checked = true;
            }

            allBooksRadio.addEventListener('change', function() {
                if (this.checked) {
                    filterBooks('all');
                    updateUrlParam('availability', null);
                }
            });

            availableBooksRadio.addEventListener('change', function() {
                if (this.checked) {
                    filterBooks('available');
                    updateUrlParam('availability', 'available');
                }
            });

            function filterBooks(filter) {
                bookCards.forEach(card => {
                    if (filter === 'all' || (filter === 'available' && card.dataset.available === 'yes')) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            function updateUrlParam(key, value) {
                const url = new URL(window.location);
                if (value === null) {
                    url.searchParams.delete(key);
                } else {
                    url.searchParams.set(key, value);
                }
                history.replaceState(null, '', url);
            }

            // Add animation effect on hover
            bookCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    const cardElement = this.querySelector('.card');
                    cardElement.classList.add('shadow');
                });

                card.addEventListener('mouseleave', function() {
                    const cardElement = this.querySelector('.card');
                    cardElement.classList.remove('shadow');
                });
            });
        });
    </script>
@endsection
