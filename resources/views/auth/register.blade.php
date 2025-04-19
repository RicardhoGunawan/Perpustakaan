@extends('layouts.main')

@section('title', 'Daftar')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2>Daftar Akun Baru</h2>
                        <p class="text-muted">Silakan isi form berikut untuk membuat akun baru</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="name" class="form-label">Nama Pengguna</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Daftar Sebagai</label>
                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="" selected disabled>Pilih status Anda...</option>
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Bagian Siswa -->
                        <div id="siswaFields" class="mb-3" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="nis" class="form-label">NIS</label>
                                    <input id="nis" type="text" class="form-control @error('nis') is-invalid @enderror" name="nis" value="{{ old('nis') }}">
                                    @error('nis')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="class" class="form-label">Kelas</label>
                                    <select id="class" name="class" class="form-select @error('class') is-invalid @enderror">
                                        <option value="" selected disabled>Pilih kelas...</option>
                                        <option value="X-1" {{ old('class') == 'X-1' ? 'selected' : '' }}>X-1</option>
                                        <option value="X-2" {{ old('class') == 'X-2' ? 'selected' : '' }}>X-2</option>
                                        <option value="X-3" {{ old('class') == 'X-3' ? 'selected' : '' }}>X-3</option>
                                        <option value="XI-IPA-1" {{ old('class') == 'XI-IPA-1' ? 'selected' : '' }}>XI-IPA-1</option>
                                        <option value="XI-IPA-2" {{ old('class') == 'XI-IPA-2' ? 'selected' : '' }}>XI-IPA-2</option>
                                        <option value="XI-IPS-1" {{ old('class') == 'XI-IPS-1' ? 'selected' : '' }}>XI-IPS-1</option>
                                        <option value="XI-IPS-2" {{ old('class') == 'XI-IPS-2' ? 'selected' : '' }}>XI-IPS-2</option>
                                        <option value="XII-IPA-1" {{ old('class') == 'XII-IPA-1' ? 'selected' : '' }}>XII-IPA-1</option>
                                        <option value="XII-IPA-2" {{ old('class') == 'XII-IPA-2' ? 'selected' : '' }}>XII-IPA-2</option>
                                        <option value="XII-IPS-1" {{ old('class') == 'XII-IPS-1' ? 'selected' : '' }}>XII-IPS-1</option>
                                        <option value="XII-IPS-2" {{ old('class') == 'XII-IPS-2' ? 'selected' : '' }}>XII-IPS-2</option>
                                    </select>
                                    @error('class')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Guru -->
                        <div id="guruFields" class="mb-3" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input id="nip" type="text" class="form-control @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip') }}">
                                    @error('nip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Mata Pelajaran</label>
                                    <input id="subject" type="text" class="form-control @error('subject') is-invalid @enderror" name="subject" value="{{ old('subject') }}">
                                    @error('subject')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Daftar
                            </button>
                        </div>

                        <div class="text-center">
                            <p>Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Masuk</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const siswaFields = document.getElementById('siswaFields');
        const guruFields = document.getElementById('guruFields');
        
        // Fields required status change function
        function setRequiredFields(role) {
            const nisField = document.getElementById('nis');
            const classField = document.getElementById('class');
            const nipField = document.getElementById('nip');
            const subjectField = document.getElementById('subject');
            
            if (role === 'siswa') {
                nisField.required = true;
                classField.required = true;
                nipField.required = false;
                subjectField.required = false;
            } else if (role === 'guru') {
                nisField.required = false;
                classField.required = false;
                nipField.required = true;
                subjectField.required = true;
            } else {
                nisField.required = false;
                classField.required = false;
                nipField.required = false;
                subjectField.required = false;
            }
        }
        
        // Initial check
        if (roleSelect.value) {
            if (roleSelect.value === 'siswa') {
                siswaFields.style.display = 'block';
                guruFields.style.display = 'none';
            } else if (roleSelect.value === 'guru') {
                siswaFields.style.display = 'none';
                guruFields.style.display = 'block';
            }
            setRequiredFields(roleSelect.value);
        }
        
        // On change
        roleSelect.addEventListener('change', function() {
            if (this.value === 'siswa') {
                siswaFields.style.display = 'block';
                guruFields.style.display = 'none';
            } else if (this.value === 'guru') {
                siswaFields.style.display = 'none';
                guruFields.style.display = 'block';
            } else {
                siswaFields.style.display = 'none';
                guruFields.style.display = 'none';
            }
            setRequiredFields(this.value);
        });
    });
</script>
@endsection