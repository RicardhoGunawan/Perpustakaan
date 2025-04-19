@extends('layouts.main')

@section('title', 'Profil')

@section('content')
<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>Profil Saya</h1>
                <p class="text-muted">Kelola informasi personal dan akun Anda</p>
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
    
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-body text-center">
                    @if (auth()->user()->student && auth()->user()->student->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->student->profile_photo) }}" alt="Profile Photo" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @elseif (auth()->user()->teacher && auth()->user()->teacher->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->teacher->profile_photo) }}" alt="Profile Photo" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="Profile Photo" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                    
                    <h5>{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-1">
                        @if (auth()->user()->hasRole('siswa') && auth()->user()->student)
                            Siswa - {{ auth()->user()->student->class }}
                        @elseif (auth()->user()->hasRole('guru') && auth()->user()->teacher)
                            Guru - {{ auth()->user()->teacher->subject }}
                        @else
                            {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                        @endif
                    </p>
                    
                    @if (auth()->user()->member)
                        <div class="mt-3">
                            <span class="badge bg-success">Anggota Aktif</span>
                            <p class="small mt-2 mb-0">ID: {{ auth()->user()->member->member_number }}</p>
                            <p class="small">Berlaku hingga: {{ \Carbon\Carbon::parse(auth()->user()->member->valid_until)->format('d/m/Y') }}</p>
                        </div>
                    @else
                        <div class="mt-3">
                            <span class="badge bg-secondary">Bukan Anggota</span>
                            <p class="small mt-2">Silakan hubungi petugas perpustakaan untuk mendaftar sebagai anggota.</p>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer text-center bg-white">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Pengguna</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ganti Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Ganti Password</button>
                        </div>
                    </form>
                </div>
            </div>
            
            @if (auth()->user()->hasRole('siswa') && auth()->user()->student)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Siswa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.student.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ auth()->user()->student->full_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nis" class="form-label">NIS</label>
                                <input type="text" class="form-control" id="nis" name="nis" value="{{ auth()->user()->student->nis }}" readonly>
                                <small class="text-muted">NIS tidak dapat diubah</small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="class" class="form-label">Kelas</label>
                                <input type="text" class="form-control" id="class" name="class" value="{{ auth()->user()->student->class }}" readonly>
                                <small class="text-muted">Hubungi admin untuk mengubah kelas</small>
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ auth()->user()->student->phone_number }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ auth()->user()->student->date_of_birth }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ auth()->user()->student->address }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            @elseif (auth()->user()->hasRole('guru') && auth()->user()->teacher)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Guru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.teacher.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ auth()->user()->teacher->full_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" value="{{ auth()->user()->teacher->nip }}" readonly>
                                <small class="text-muted">NIP tidak dapat diubah</small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="subject" class="form-label">Mata Pelajaran</label>
                                <input type="text" class="form-control" id="subject" name="subject" value="{{ auth()->user()->teacher->subject }}" readonly>
                                <small class="text-muted">Hubungi admin untuk mengubah mata pelajaran</small>
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ auth()->user()->teacher->phone_number }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ auth()->user()->teacher->address }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    @if (auth()->user()->bookLoans->isEmpty())
                        <div class="text-center py-3">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <p>Anda belum memiliki riwayat peminjaman buku.</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary">Jelajahi Buku</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (auth()->user()->bookLoans->take(5) as $loan)
                                    <tr>
                                        <td>{{ $loan->book->title }}</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $loan->status == 'dipinjam' ? 'primary' : ($loan->status == 'terlambat' ? 'danger' : 'success') }}">
                                                {{ ucfirst($loan->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('loans.index') }}" class="btn btn-outline-primary">Lihat Semua Peminjaman</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection