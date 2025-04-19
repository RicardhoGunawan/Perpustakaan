<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota Tidak Aktif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .card-container {
            max-width: 500px;
            width: 100%;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-header {
            background-color: #dc3545;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
        }
        
        .school-logo {
            width: 50px;
            height: 50px;
            margin-right: 15px;
            object-fit: contain;
        }
        
        .card-title {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .card-content {
            padding: 30px 20px;
            text-align: center;
        }
        
        .icon-warning {
            font-size: 60px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        
        .message-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .message-body {
            color: #555;
            margin-bottom: 20px;
        }
        
        .member-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .info-item {
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .footer {
            text-align: center;
            padding: 15px;
            background-color: #f1f3f5;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="card-container">
    <div class="card-header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" class="school-logo">
        <div>
            <h1 class="card-title">KARTU ANGGOTA PERPUSTAKAAN</h1>
            <p>SMA NEGERI TARUNA KASUARI NUSANTARA</p>
        </div>
    </div>
    
    <div class="card-content">
        <div class="icon-warning">
            <i class="bi bi-exclamation-circle-fill"></i>
        </div>
        
        <h2 class="message-title">Kartu Anggota Tidak Aktif</h2>
        
        <p class="message-body">
            Maaf, kartu anggota perpustakaan ini saat ini tidak aktif. Silakan hubungi petugas perpustakaan untuk informasi lebih lanjut.
        </p>
        
        <div class="member-info">
            <div class="info-item">
                <span class="info-label">No. Anggota:</span>
                <span>{{ $member->member_number ?? 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Nama:</span>
                <span>{{ $student->full_name }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">NIS:</span>
                <span>{{ $student->nis }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Kelas:</span>
                <span>{{ $student->class }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Berlaku Hingga:</span>
                <span>{{ $member->valid_until ? \Carbon\Carbon::parse($member->valid_until)->translatedFormat('d F Y') : 'N/A' }}</span>
            </div>
        </div>
    </div>
    
    <div class="footer">
        Kartu Anggota Perpustakaan Digital • SMA Negeri Taruna Kasuari Nusantara
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>