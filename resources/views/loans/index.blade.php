@extends('layouts.main')

@section('title', 'Peminjaman Buku')

@section('content')
<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>Peminjaman Buku</h1>
                <p class="text-muted">Kelola dan pantau riwayat peminjaman buku Anda</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Filter Peminjaman</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('loans.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="date_from" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="date_to" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Peminjaman</h5>
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Pinjam Buku Baru
            </a>
        </div>
        <div class="card-body">
            @if ($loans->isEmpty())
                <div class="text-center py-3">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <p>Anda belum memiliki riwayat peminjaman buku.</p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary">Jelajahi Buku</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tenggat Waktu</th>
                                <th>Status</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loans as $loan)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $loan->book->cover_image ? asset('storage/' . $loan->book->cover_image) : asset('images/default-book-cover.png') }}" 
                                             alt="{{ $loan->book->title }}" class="img-fluid rounded" style="width: 50px; height: 70px; object-fit: cover;">
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ $loan->book->title }}</h6>
                                            <small class="text-muted">{{ $loan->book->author }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}
                                    @if ($loan->status == 'dipinjam' && \Carbon\Carbon::parse($loan->due_date)->isPast())
                                        <br><span class="text-danger small">Terlambat {{ \Carbon\Carbon::now()->diffInDays($loan->due_date) }} hari</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $loan->status == 'dipinjam' ? 'primary' : ($loan->status == 'terlambat' ? 'danger' : 'success') }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                    @if ($loan->return_date)
                                        <br><small class="text-muted">Dikembalikan: {{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td>{{ $loan->quantity }}</td>
                                <td>
                                    <a href="{{ route('books.show', $loan->book_id) }}" class="btn btn-sm btn-outline-primary">Detail Buku</a>
                                    
                                    @if ($loan->status == 'dipinjam')
                                        <button class="btn btn-sm btn-outline-success mt-1" data-bs-toggle="modal" data-bs-target="#extendModal{{ $loan->id }}">
                                            Perpanjang
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $loans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    
    @if (auth()->user()->hasRole('guru'))
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Request Buku Baru</h5>
        </div>
        <div class="card-body">
            <p>Sebagai guru, Anda dapat mengajukan permintaan untuk buku-buku baru yang diperlukan untuk kegiatan pembelajaran.</p>
            
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestBookModal">
                <i class="fas fa-plus me-1"></i> Ajukan Request Buku
            </button>
            
            @if (auth()->user()->bookRequests->isNotEmpty())
                <div class="mt-4">
                    <h6>Request Saya</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th>Tanggal Request</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (auth()->user()->bookRequests as $request)
                                <tr>
                                    <td>{{ $request->title }}</td>
                                    <td>{{ $request->author }}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : 'danger') }}">
                                            {{ $request->status == 'pending' ? 'Menunggu' : ($request->status == 'approved' ? 'Disetujui' : 'Ditolak') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Modal Request Buku -->
<div class="modal fade" id="requestBookModal" tabindex="-1" aria-labelledby="requestBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestBookModalLabel">Request Buku Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('book-requests.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="author" class="form-label">Penulis</label>
                        <input type="text" class="form-control" id="author" name="author">
                    </div>
                    
                    <div class="mb-3">
                        <label for="publisher" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="publisher" name="publisher">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi / Alasan</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Perpanjang untuk setiap peminjaman -->
@foreach ($loans as $loan)
@if ($loan->status == 'dipinjam')
<div class="modal fade" id="extendModal{{ $loan->id }}" tabindex="-1" aria-labelledby="extendModalLabel{{ $loan->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="extendModalLabel{{ $loan->id }}">Perpanjang Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('loans.extend', $loan) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Buku</label>
                        <input type="text" class="form-control" value="{{ $loan->book->title }}" readonly>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Peminjaman</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tenggat Saat Ini</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}" readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_due_date" class="form-label">Tanggal Pengembalian Baru</label>
                        <input type="date" class="form-control" id="new_due_date" name="new_due_date" 
                               value="{{ \Carbon\Carbon::parse($loan->due_date)->addDays(7)->format('Y-m-d') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Alasan Perpanjangan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" required></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Perpanjangan hanya dapat dilakukan satu kali. Mohon mengembalikan buku tepat waktu.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan Perpanjangan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection