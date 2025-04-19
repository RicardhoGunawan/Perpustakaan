@extends('layouts.main')

@section('title', 'Daftar Permintaan Buku')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">Daftar Permintaan Buku</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('book-requests.create') }}" class="btn btn-primary">Ajukan Permintaan Buku Baru</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookRequests as $bookRequest)
                <tr>
                    <td>{{ $bookRequest->title }}</td>
                    <td>{{ $bookRequest->author }}</td>
                    <td>{{ $bookRequest->publisher }}</td>
                    <td>
                        @if($bookRequest->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($bookRequest->status == 'approved')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>{{ $bookRequest->description ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
