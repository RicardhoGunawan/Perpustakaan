@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota Saya - {{ $student->full_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .card-container {
            width: 324px;
            height: 214px;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            margin: 20px auto;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .card-header {
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            width: 100%;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
        }

        .logo-cell {
            width: 45px;
            padding-right: 10px;
        }

        .school-logo {
            max-width: 40px;
            max-height: 40px;
            display: block;
        }

        .title-cell {
            text-align: left;
        }

        .title-cell h1 {
            font-size: 14px;
            margin: 0;
            color: #333;
        }

        .title-cell p {
            font-size: 10px;
            margin: 2px 0 0 0;
            color: #555;
        }

        .card-body {
            display: block;
            width: 100%;
            margin-top: 10px;
            flex-grow: 1;
        }

        .photo-section {
            width: 80px;
            text-align: center;
            vertical-align: top;
            padding-right: 10px;
            display: inline-block;
        }

        .details-section {
            width: calc(100% - 100px);
            vertical-align: top;
            display: inline-block;
        }

        .profile-photo {
            width: 70px;
            height: 90px;
            border: 1px solid #ddd;
            object-fit: cover;
            background-color: #eee;
        }

        .info-item {
            margin-bottom: 5px;
            clear: both;
        }

        .info-item label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 80px;
        }

        .info-item span {
            color: #333;
        }

        .qrcode-section {
            position: absolute;
            bottom: 5px;
            right: 10px;
            width: 60px;
            height: 60px;
        }

        .qrcode-section img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .qrcode-placeholder {
            width: 50px;
            height: 50px;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #666;
        }

        .card-footer {
            margin-top: auto;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 8px;
            padding: 3px 6px;
            border-radius: 3px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card-container">
        <div class="card-header">
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" class="school-logo">
                    </td>
                    <td class="title-cell">
                        <h1>KARTU ANGGOTA PERPUSTAKAAN</h1>
                        <p>SMA NEGERI TARUNA KASUARI NUSANTARA</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="card-body">
            <div class="photo-section">
                @if($student->profile_photo && Storage::disk('public')->exists($student->profile_photo))
                    <img src="{{ Storage::url($student->profile_photo) }}" alt="Foto Profil" class="profile-photo">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&color=7F9CF5&background=EBF4FF&size=90" 
                         alt="Foto Profil" class="profile-photo">
                @endif
            </div>

            <div class="details-section">
                <div class="info-item"><label>No. Anggota:</label><span>{{ $member->member_number ?? 'N/A' }}</span></div>
                <div class="info-item"><label>Nama:</label><span>{{ $student->full_name }}</span></div>
                <div class="info-item"><label>NIS:</label><span>{{ $student->nis }}</span></div>
                <div class="info-item"><label>Kelas:</label><span>{{ $student->class }}</span></div>
                <div class="info-item"><label>Tgl Lahir:</label><span>
                    @if($student->date_of_birth)
                        {{ \Carbon\Carbon::parse($student->date_of_birth)->translatedFormat('d F Y') }}
                    @else
                        N/A
                    @endif
                </span></div>
                <div class="info-item"><label>Alamat:</label><span>{{ Str::limit($student->address, 50) }}</span></div>
            </div>
        </div>

        <div class="qrcode-section">
            @if($member && $member->member_number)
                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(80)->margin(1)->generate(url('/member-card/' . $member->id))) }}">
            @else
                <div class="qrcode-placeholder">No QR</div>
            @endif
        </div>

        <div class="status-badge badge {{ now()->gt($member->valid_until) ? 'bg-danger' : 'bg-success' }}">
            {{ now()->gt($member->valid_until) ? 'KADALUARSA' : 'AKTIF' }}
        </div>

        <div class="card-footer">
            Berlaku Hingga: {{ $member->valid_until ? \Carbon\Carbon::parse($member->valid_until)->translatedFormat('d F Y') : 'N/A' }}
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('member.card.download') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-download me-2"></i> Unduh Kartu PDF
        </a>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm ms-2">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>