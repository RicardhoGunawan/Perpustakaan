<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\BookLoan;
use App\Models\BookRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Buku', Book::count())
                ->description('Jumlah buku di perpustakaan')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('primary'),
                
            Stat::make('Total Anggota', User::role(['guru', 'siswa'])->count())
                ->description('Guru dan siswa yang terdaftar')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
                
            Stat::make('Peminjaman Aktif', BookLoan::where('status', 'dipinjam')->count())
                ->description('Buku yang sedang dipinjam')
                ->descriptionIcon('heroicon-o-arrow-up-tray')
                ->color('warning'),
                
            Stat::make('Permintaan Buku', BookRequest::where('status', 'pending')->count())
                ->description('Permintaan menunggu persetujuan')
                ->descriptionIcon('heroicon-o-inbox')
                ->color('danger'),
        ];
    }
    
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin']);
    }
}