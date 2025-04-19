@extends('layouts.main')

@section('title', 'Beranda')

@section('content')
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">Selamat Datang di Perpustakaan Sekolah</h1>
        <p class="lead mb-5">Temukan ribuan buku untuk menambah wawasan dan pengetahuan Anda</p>
        <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg px-4 me-2">Katalog Buku</a>
        @guest
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">Masuk untuk Meminjam</a>
        @endguest
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
                    <p class="text-muted">Ribuan koleksi buku dari berbagai kategori untuk mendukung pembelajaran Anda</p>
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
            <h2>Buku Terbaru</h2>
            <p class="text-muted">Koleksi buku-buku baru yang baru saja ditambahkan ke perpustakaan kami</p>
        </div>

        @foreach($latestBooks as $book)
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 position-relative">
                <span class="badge bg-{{ $book->availableStock > 0 ? 'success' : 'danger' }} stock-badge">
                    {{ $book->availableStock > 0 ? 'Tersedia' : 'Dipinjam' }}
                </span>
                <span class="badge bg-primary category-badge">{{ $book->category }}</span>
                <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/default-book-cover.png') }}" 
                     class="card-img-top book-cover" alt="{{ $book->title }}">
                <div class="card-body">
                    <h5 class="card-title text-truncate">{{ $book->title }}</h5>
                    <p class="card-text text-muted mb-0">{{ $book->author }}</p>
                    <p class="small text-muted">{{ $book->publication_year }}</p>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary w-100">Detail Buku</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mb-4">
        <div class="col-12 text-center mb-4">
            <h2>Kategori Buku</h2>
            <p class="text-muted">Jelajahi berbagai kategori buku yang tersedia di perpustakaan kami</p>
        </div>

        @foreach($categories as $category)
        <div class="col-md-4 col-lg-3 mb-4">
            <a href="{{ route('books.index', ['category' => $category]) }}" class="text-decoration-none">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category }}</h5>
                        <p class="card-text text-muted">{{ $categoryCounts[$category] ?? 0 }} buku</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection