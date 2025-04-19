@extends('layouts.main')

@section('title', 'Katalog Buku')

@section('content')
<div class="bg-light py-5">
    <div class="container">
        <h1 class="mb-4">Katalog Buku</h1>
        
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="{{ route('books.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari judul buku, penulis, atau ISBN..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-md-end mt-3 mt-md-0">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Kategori: {{ request('category') ?: 'Semua' }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('books.index') }}">Semua</a></li>
                            @foreach($categories as $category)
                                <li><a class="dropdown-item" href="{{ route('books.index', ['category' => $category]) }}">{{ $category }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 mb-4">
                <div class="btn-group">
                    <input type="radio" class="btn-check" name="stock-filter" id="all-books" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="all-books">Semua</label>

                    <input type="radio" class="btn-check" name="stock-filter" id="available-books" autocomplete="off">
                    <label class="btn btn-outline-primary" for="available-books">Tersedia</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    @if($books->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <h3>Buku tidak ditemukan</h3>
            <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary">Lihat Semua Buku</a>
        </div>
    @else
        <div class="row">
            @foreach($books as $book)
            <div class="col-md-6 col-lg-3 mb-4 book-card" data-available="{{ $book->availableStock > 0 ? 'yes' : 'no' }}">
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
                        <p class="small text-muted">{{ $book->publication_year }} • {{ $book->publisher }}</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary w-100">Detail Buku</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $books->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allBooksRadio = document.getElementById('all-books');
        const availableBooksRadio = document.getElementById('available-books');
        const bookCards = document.querySelectorAll('.book-card');

        allBooksRadio.addEventListener('change', function() {
            bookCards.forEach(card => {
                card.style.display = 'block';
            });
        });

        availableBooksRadio.addEventListener('change', function() {
            bookCards.forEach(card => {
                if (card.dataset.available === 'yes') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection