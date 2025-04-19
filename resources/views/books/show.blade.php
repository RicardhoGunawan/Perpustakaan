@extends('layouts.main')

@section('title', $book->title)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fas fa-home me-1"></i>Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Katalog Buku</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Kolom Kiri - Gambar dan Tombol -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm overflow-hidden rounded-3 position-sticky" style="top: 2rem;">
                <div class="position-relative book-cover-container">
                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/default-book-cover.png') }}" 
                         class="img-fluid w-100" alt="{{ $book->title }}" style="object-fit: cover; height: 400px;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-{{ $book->availableStock > 0 ? 'success' : 'danger' }} py-2 px-3 rounded-pill fs-6">
                            <i class="fas {{ $book->availableStock > 0 ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>{{ $book->availableStock > 0 ? 'Tersedia' : 'Dipinjam' }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-primary rounded-pill py-2 px-3">{{ $book->category }}</span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        @if($book->availableStock > 0)
                            @auth
                                <button type="button" class="btn btn-primary py-2" data-bs-toggle="modal" data-bs-target="#borrowModal">
                                    <i class="fas fa-book me-2"></i>Pinjam Buku
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary py-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login untuk Meminjam
                                </a>
                            @endauth
                        @else
                            <button class="btn btn-secondary py-2" disabled>
                                <i class="fas fa-times-circle me-2"></i>Tidak Tersedia
                            </button>
                        @endif
                        
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary py-2">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Katalog
                        </a>
                    </div>
                </div>
                
                <!-- Book Statistics -->
                <div class="card-footer bg-light p-3 border-0">
                    <div class="row text-center g-2">
                        <div class="col">
                            <div class="p-2 rounded bg-white">
                                <div class="small text-muted">Total Stok</div>
                                <div class="fw-bold">{{ $book->stock }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2 rounded bg-white">
                                <div class="small text-muted">Tersedia</div>
                                <div class="fw-bold">{{ $book->availableStock }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2 rounded bg-white">
                                <div class="small text-muted">Dipinjam</div>
                                <div class="fw-bold">{{ $book->stock - $book->availableStock }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Kolom Kanan - Informasi Buku -->
        <div class="col-md-8">
            <div class="mb-4">
                <h1 class="display-6 fw-bold mb-2">{{ $book->title }}</h1>
                <p class="lead mb-3 text-muted">oleh <span class="fw-medium">{{ $book->author }}</span></p>
            </div>
            
            <!-- Tabs untuk informasi buku -->
            <ul class="nav nav-tabs mb-4" id="bookDetailTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab" aria-controls="details-tab-pane" aria-selected="true">Detail</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="description-tab" data-bs-toggle="tab" data-bs-target="#description-tab-pane" type="button" role="tab" aria-controls="description-tab-pane" aria-selected="false">Deskripsi</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="similar-tab" data-bs-toggle="tab" data-bs-target="#similar-tab-pane" type="button" role="tab" aria-controls="similar-tab-pane" aria-selected="false">Buku Serupa</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rules-tab" data-bs-toggle="tab" data-bs-target="#rules-tab-pane" type="button" role="tab" aria-controls="rules-tab-pane" aria-selected="false">Aturan Peminjaman</button>
                </li>
            </ul>
            
            <div class="tab-content" id="bookDetailTabContent">
                <!-- Tab Detail -->
                <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Informasi Buku</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="book-detail-item border-bottom pb-3">
                                        <div class="small text-muted">ISBN</div>
                                        <div class="fw-medium">{{ $book->isbn }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="book-detail-item border-bottom pb-3">
                                        <div class="small text-muted">Penerbit</div>
                                        <div class="fw-medium">{{ $book->publisher }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="book-detail-item border-bottom pb-3">
                                        <div class="small text-muted">Tahun Terbit</div>
                                        <div class="fw-medium">{{ $book->publication_year }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="book-detail-item border-bottom pb-3">
                                        <div class="small text-muted">Kategori</div>
                                        <div class="fw-medium">{{ $book->category }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Deskripsi -->
                <div class="tab-pane fade" id="description-tab-pane" role="tabpanel" aria-labelledby="description-tab" tabindex="0">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Deskripsi</h5>
                            <div class="book-description">
                                @if($book->description)
                                    <p class="mb-0">{{ $book->description }}</p>
                                @else
                                    <div class="alert alert-light mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Tidak ada deskripsi tersedia untuk buku ini.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Buku Serupa -->
                <div class="tab-pane fade" id="similar-tab-pane" role="tabpanel" aria-labelledby="similar-tab" tabindex="0">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Buku Serupa</h5>
                            
                            @if($similarBooks->count() > 0)
                                <div class="row g-3">
                                    @foreach($similarBooks as $similar)
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm similar-book">
                                            <div class="row g-0 h-100">
                                                <div class="col-4">
                                                    <img src="{{ $similar->cover_image ? asset('storage/' . $similar->cover_image) : asset('images/default-book-cover.png') }}" 
                                                         class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $similar->title }}">
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body p-3">
                                                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2">{{ $similar->category }}</span>
                                                        <h6 class="card-title fw-bold text-truncate">{{ $similar->title }}</h6>
                                                        <p class="card-text small text-muted mb-2">{{ $similar->author }}</p>
                                                        <div class="d-flex align-items-center small mb-3">
                                                            <div class="text-warning me-2">
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="fas fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                            </div>
                                                            <span>4.0</span>
                                                        </div>
                                                        <a href="{{ route('books.show', $similar) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-light mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Tidak ada buku serupa yang tersedia.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Tab Aturan Peminjaman -->
                <div class="tab-pane fade" id="rules-tab-pane" role="tabpanel" aria-labelledby="rules-tab" tabindex="0">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Aturan Peminjaman</h5>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="rule-icon bg-primary bg-opacity-10 rounded-circle p-3">
                                                <i class="fas fa-calendar-alt fa-lg text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold">Durasi Peminjaman</h6>
                                            <p class="text-muted small mb-0">Maksimal durasi peminjaman adalah 7 hari kalender. Untuk guru, dapat diperpanjang hingga 14 hari.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="rule-icon bg-danger bg-opacity-10 rounded-circle p-3">
                                                <i class="fas fa-exclamation-circle fa-lg text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold">Sanksi Keterlambatan</h6>
                                            <p class="text-muted small mb-0">Keterlambatan pengembalian akan dikenakan denda Rp 1.000,- per hari untuk setiap buku.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="rule-icon bg-success bg-opacity-10 rounded-circle p-3">
                                                <i class="fas fa-sync-alt fa-lg text-success"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold">Perpanjangan</h6>
                                            <p class="text-muted small mb-0">Perpanjangan dapat dilakukan 1 kali dengan durasi maksimal 7 hari, selama tidak ada antrean peminjaman.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="rule-icon bg-warning bg-opacity-10 rounded-circle p-3">
                                                <i class="fas fa-book-open fa-lg text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold">Kerusakan Buku</h6>
                                            <p class="text-muted small mb-0">Kerusakan atau kehilangan buku akan dikenakan denda sesuai dengan harga buku atau penggantian dengan buku yang sama.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section for Library Staff - Admin Note -->
            @role('admin|staff')
            <div class="card border-0 shadow-sm bg-light mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3"><i class="fas fa-clipboard-list me-2"></i>Catatan untuk Staf Perpustakaan</h5>
                    <div class="mb-3">
                        <p class="mb-2 small">Status Pemeliharaan:</p>
                        <select class="form-select form-select-sm" aria-label="Status pemeliharaan">
                            <option value="1" selected>Baik</option>
                            <option value="2">Perlu Perbaikan</option>
                            <option value="3">Dalam Perawatan</option>
                        </select>
                    </div>
                    <div>
                        <p class="mb-2 small">Catatan Internal:</p>
                        <textarea class="form-control form-control-sm" rows="2" placeholder="Tambahkan catatan internal tentang buku ini..."></textarea>
                    </div>
                </div>
            </div>
            @endrole
        </div>
    </div>
</div>

@auth
<!-- Modal Peminjaman -->
<div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="borrowModalLabel">
                    <i class="fas fa-book me-2"></i>Form Peminjaman Buku
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('loans.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/default-book-cover.png') }}" 
                                     class="img-thumbnail" style="width: 60px;" alt="{{ $book->title }}">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold">{{ $book->title }}</h6>
                                <p class="text-muted small mb-0">oleh {{ $book->author }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if(auth()->user()->hasRole('guru'))
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah Buku</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            <input type="number" class="form-control" name="quantity" id="quantity" min="1" max="{{ $book->availableStock }}" value="1" required>
                            <span class="input-group-text">dari {{ $book->availableStock }}</span>
                        </div>
                        <div class="form-text">Maksimal peminjaman {{ min(5, $book->availableStock) }} buku untuk guru.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="borrowed_for" class="form-label">Untuk Kelas</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                            <input type="text" class="form-control" name="borrowed_for" id="borrowed_for" required>
                        </div>
                        <div class="form-text">Contoh: XII IPA 1, XI IPS 2, etc.</div>
                    </div>
                    @else
                    <input type="hidden" name="quantity" value="1">
                    @endif
                    
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Tanggal Pengembalian</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" class="form-control" name="due_date" id="due_date" 
                                   value="{{ date('Y-m-d', strtotime('+7 days')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>
                        <div class="form-text">Maksimal 7 hari dari tanggal peminjaman.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="notes" class="form-label">Catatan (opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                            <textarea class="form-control" name="notes" id="notes" rows="2"></textarea>
                        </div>
                    </div>
                    
                    <div class="alert alert-info d-flex" role="alert">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle mt-1"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="alert-heading fw-bold mb-1">Perhatian</h6>
                            <p class="small mb-0">Buku harus dikembalikan tepat waktu untuk menghindari sanksi keterlambatan. Denda keterlambatan sebesar Rp 1.000,- per hari.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>Konfirmasi Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
@endsection