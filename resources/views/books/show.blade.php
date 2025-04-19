@extends('layouts.main')

@section('title', $book->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card">
                <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/default-book-cover.png') }}" 
                     class="img-fluid rounded" alt="{{ $book->title }}">
                     
                <div class="card-body text-center">
                    <div class="d-grid gap-2">
                        @if($book->availableStock > 0)
                            @auth
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#borrowModal">
                                    <i class="fas fa-book me-2"></i>Pinjam Buku
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login untuk Meminjam
                                </a>
                            @endauth
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-times-circle me-2"></i>Tidak Tersedia
                            </button>
                        @endif
                        
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Katalog Buku</a></li>
                    <li class="breadcrumb-item active">{{ $book->title }}</li>
                </ol>
            </nav>
        
            <h1 class="mb-2">{{ $book->title }}</h1>
            <p class="lead mb-1">oleh {{ $book->author }}</p>
            
            <div class="mb-4">
                <span class="badge bg-primary me-2">{{ $book->category }}</span>
                <span class="badge bg-{{ $book->availableStock > 0 ? 'success' : 'danger' }}">
                    {{ $book->availableStock > 0 ? 'Tersedia' : 'Dipinjam' }}
                </span>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th style="width: 40%">ISBN</th>
                            <td>{{ $book->isbn }}</td>
                        </tr>
                        <tr>
                            <th>Penerbit</th>
                            <td>{{ $book->publisher }}</td>
                        </tr>
                        <tr>
                            <th>Tahun Terbit</th>
                            <td>{{ $book->publication_year }}</td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td>{{ $book->stock }}</td>
                        </tr>
                        <tr>
                            <th>Tersedia</th>
                            <td>{{ $book->availableStock }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="mb-4">
                <h4>Deskripsi</h4>
                <p>{{ $book->description ?? 'Tidak ada deskripsi tersedia untuk buku ini.' }}</p>
            </div>
            
            <hr class="my-4">
            
            <div class="mb-4">
                <h4>Buku Serupa</h4>
                <div class="row">
                    @forelse($similarBooks as $similar)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="row g-0 h-100">
                                <div class="col-4">
                                    <img src="{{ $similar->cover_image ? asset('storage/' . $similar->cover_image) : asset('images/default-book-cover.png') }}" 
                                         class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $similar->title }}">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h6 class="card-title text-truncate mb-1">{{ $similar->title }}</h6>
                                        <p class="card-text small text-muted mb-2">{{ $similar->author }}</p>
                                        <a href="{{ route('books.show', $similar) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">Tidak ada buku serupa yang tersedia.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@auth
<!-- Modal Peminjaman -->
<div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="borrowModalLabel">Pinjam Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('loans.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Buku</label>
                        <input type="text" class="form-control" value="{{ $book->title }}" readonly>
                    </div>
                    
                    @if(auth()->user()->hasRole('guru'))
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" min="1" max="{{ $book->availableStock }}" value="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="borrowed_for" class="form-label">Untuk Kelas</label>
                        <input type="text" class="form-control" name="borrowed_for" id="borrowed_for" required>
                    </div>
                    @else
                    <input type="hidden" name="quantity" value="1">
                    @endif
                    
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" name="due_date" id="due_date" 
                               value="{{ date('Y-m-d', strtotime('+7 days')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Buku harus dikembalikan tepat waktu untuk menghindari sanksi keterlambatan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
@endsection