@extends('layouts.main')

@section('title', 'Profil')

@section('content')
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Profil Saya</h1>
                    <p class="lead">Kelola informasi personal dan akun Anda</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body text-center">
                        @if (auth()->user()->student && auth()->user()->student->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->student->profile_photo) }}" alt="Profile Photo"
                                class="rounded-circle img-fluid mb-3 shadow"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @elseif (auth()->user()->teacher && auth()->user()->teacher->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->teacher->profile_photo) }}" alt="Profile Photo"
                                class="rounded-circle img-fluid mb-3 shadow"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF"
                                alt="Profile Photo" class="rounded-circle img-fluid mb-3 shadow"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @endif

                        <h5 class="fw-bold">{{ auth()->user()->name }}</h5>
                        <p class="text-muted mb-3">
                            @if (auth()->user()->hasRole('siswa') && auth()->user()->student)
                                <span class="badge bg-info text-dark">Siswa</span> {{ auth()->user()->student->class }}
                            @elseif (auth()->user()->hasRole('guru') && auth()->user()->teacher)
                                <span class="badge bg-info text-dark">Guru</span> {{ auth()->user()->teacher->subject }}
                            @else
                                <span
                                    class="badge bg-secondary">{{ auth()->user()->roles->pluck('name')->implode(', ') }}</span>
                            @endif
                        </p>

                        @if ($member = auth()->user()->member) <!-- Pastikan member ada -->
                            <div class="mt-3 mb-4">
                                {{-- Menampilkan badge jika anggota aktif --}}
                                @if ($member->is_active)
                                    <span class="badge bg-success p-2 mb-2">
                                        <i class="fas fa-check-circle me-1"></i> Anggota Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger p-2 mb-2">
                                        <i class="fas fa-times-circle me-1"></i> Anggota Tidak Aktif
                                    </span>
                                @endif

                                <p class="small mt-2 mb-0">ID: {{ $member->member_number }}</p>
                                <p class="small">Berlaku hingga:
                                    {{ \Carbon\Carbon::parse($member->valid_until)->format('d M Y') }}</p>

                                <div class="d-grid gap-2 mt-3">
                                    <a href="{{ route('member.card.show', $member->id) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-id-card me-1"></i> Lihat Kartu Anggota
                                    </a>
                                    <a href="{{ route('member.card.download') }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-download me-1"></i> Download Kartu
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-secondary mt-3">
                                <i class="fas fa-info-circle me-1"></i> Bukan Anggota
                                <p class="small mt-2 mb-0">Silakan hubungi petugas perpustakaan untuk mendaftar sebagai
                                    anggota.</p>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white py-3 border-top">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle text-primary me-2 fs-4"></i>
                            <h5 class="mb-0 fw-bold">Informasi Akun</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">Nama Pengguna</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ auth()->user()->name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ auth()->user()->email }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-lock text-primary me-2 fs-4"></i>
                            <h5 class="mb-0 fw-bold">Ganti Password</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="current_password" class="form-label fw-bold">Password Saat Ini</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" id="current_password"
                                            name="current_password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password
                                        Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-key me-1"></i> Ganti Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if (auth()->user()->hasRole('siswa') && auth()->user()->student)
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-graduate text-primary me-2 fs-4"></i>
                                <h5 class="mb-0 fw-bold">Informasi Siswa</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.student.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label fw-bold">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name"
                                            value="{{ auth()->user()->student->full_name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nis" class="form-label fw-bold">NIS</label>
                                        <input type="text" class="form-control bg-light" id="nis"
                                            name="nis" value="{{ auth()->user()->student->nis }}" readonly>
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> NIS tidak dapat
                                            diubah</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="class" class="form-label fw-bold">Kelas</label>
                                        <input type="text" class="form-control bg-light" id="class"
                                            name="class" value="{{ auth()->user()->student->class }}" readonly>
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Hubungi admin
                                            untuk mengubah kelas</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label fw-bold">Nomor Telepon</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" class="form-control" id="phone_number"
                                                name="phone_number" value="{{ auth()->user()->student->phone_number }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label fw-bold">Tanggal Lahir</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input type="date" class="form-control" id="date_of_birth"
                                            name="date_of_birth" value="{{ auth()->user()->student->date_of_birth }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label fw-bold">Alamat</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                        <textarea class="form-control" id="address" name="address" rows="3">{{ auth()->user()->student->address }}</textarea>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="profile_photo" class="form-label fw-bold">Foto Profil</label>
                                    <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Biarkan kosong jika
                                        tidak ingin mengubah foto</small>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif (auth()->user()->hasRole('guru') && auth()->user()->teacher)
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chalkboard-teacher text-primary me-2 fs-4"></i>
                                <h5 class="mb-0 fw-bold">Informasi Guru</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.teacher.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label fw-bold">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name"
                                            value="{{ auth()->user()->teacher->full_name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nip" class="form-label fw-bold">NIP</label>
                                        <input type="text" class="form-control bg-light" id="nip"
                                            name="nip" value="{{ auth()->user()->teacher->nip }}" readonly>
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> NIP tidak dapat
                                            diubah</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="subject" class="form-label fw-bold">Mata Pelajaran</label>
                                        <input type="text" class="form-control bg-light" id="subject"
                                            name="subject" value="{{ auth()->user()->teacher->subject }}" readonly>
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Hubungi admin
                                            untuk mengubah mata pelajaran</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label fw-bold">Nomor Telepon</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="text" class="form-control" id="phone_number"
                                                name="phone_number" value="{{ auth()->user()->teacher->phone_number }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label fw-bold">Alamat</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                        <textarea class="form-control" id="address" name="address" rows="3">{{ auth()->user()->teacher->address }}</textarea>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="profile_photo" class="form-label fw-bold">Foto Profil</label>
                                    <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Biarkan kosong jika
                                        tidak ingin mengubah foto</small>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-history text-primary me-2 fs-4"></i>
                                <h5 class="mb-0 fw-bold">Aktivitas Peminjaman</h5>
                            </div>
                            <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-list me-1"></i> Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (auth()->user()->bookLoans->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-book fa-4x text-muted mb-3 opacity-50"></i>
                                <h5 class="text-muted">Belum Ada Aktivitas Peminjaman</h5>
                                <p>Anda belum memiliki riwayat peminjaman buku apapun.</p>
                                <a href="{{ route('books.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-search me-1"></i> Jelajahi Buku
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
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
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-book text-primary me-2"></i>
                                                        {{ $loan->book->title }}
                                                    </div>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                                                <td>
                                                    @if ($loan->status == 'dipinjam')
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-clock me-1"></i> Dipinjam
                                                        </span>
                                                    @elseif($loan->status == 'terlambat')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-circle me-1"></i> Terlambat
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i> Dikembalikan
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
