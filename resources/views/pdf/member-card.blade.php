{{-- resources/views/pdf/member-card.blade.php --}}
@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota - {{ $student->full_name }}</title>
    <style>
        /* ... CSS Styles Header, Body, Container, etc (sama seperti sebelumnya)... */
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .card-container {
            width: 324px;
            /* Sekitar 85.6mm */
            /* Tingkatkan tinggi kartu sedikit jika perlu untuk QR Code */
            height: 214px;
            /* Contoh: ditambah 10px */
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

        /* ... CSS Header Table, Logo, Title ... */
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

        /* ... CSS Body, Photo, Details ... */
        .card-body {
            display: block;
            width: 100%;
            margin-top: 10px;
            flex-grow: 1;
            /* Agar body mengisi ruang sebelum footer */
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

        /* --- START CSS BARU UNTUK QR CODE --- */
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

        /* --- END CSS BARU UNTUK QR CODE --- */

        .card-footer {
            margin-top: auto;
            /* Mendorong footer ke bawah jika card-body flex-grow */
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 5px;
            /* Beri sedikit padding bawah jika perlu */
            /* padding-bottom: 5px; */
        }
    </style>
</head>

<body>

    <div class="card-container">
        {{-- Header with Logo and Title --}}
        <div class="card-header">
            {{-- ... isi header table ... --}}
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        <img src="{{ public_path('images/logo.png') }}" alt="Logo Sekolah" class="school-logo">
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
                {{-- ... img photo ... --}}
                @if($student->profile_photo && Storage::disk('public')->exists($student->profile_photo))
                    <img src="{{ public_path(Storage::url($student->profile_photo)) }}" alt="Foto Profil"
                        class="profile-photo">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&color=7F9CF5&background=EBF4FF&size=90"
                        alt="Foto Profil" class="profile-photo">
                @endif
            </div>

            <div class="details-section">
                {{-- ... Info Items ... --}}
                <div class="info-item"><label>No. Anggota:</label><span>{{ $member->member_number ?? 'N/A' }}</span>
                </div>
                <div class="info-item"><label>Nama:</label><span>{{ $student->full_name }}</span></div>
                <div class="info-item"><label>NIS:</label><span>{{ $student->nis }}</span></div>
                <div class="info-item"><label>Kelas:</label><span>{{ $student->class }}</span></div>
                <div class="info-item"><label>Tgl
                        Lahir:</label><span>@if($student->date_of_birth){{ \Carbon\Carbon::parse($student->date_of_birth)->translatedFormat('d F Y') }}@else
                        N/A @endif</span></div>
                <div class="info-item"><label>Alamat:</label><span>{{ Str::limit($student->address, 50) }}</span></div>



            </div>
        </div>
        <div class="qrcode-section">
            @if($member && $member->member_number)
                <img
                    src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(80)->margin(1)->generate($member->member_number)) }}">
            @else
                <div class="qrcode-placeholder">No QR</div>
            @endif
        </div>

        <div class="card-footer">
            Berlaku Hingga:
            {{ $member->valid_until ? \Carbon\Carbon::parse($member->valid_until)->translatedFormat('d F Y') : 'N/A' }}
        </div>
    </div>

</body>

</html>