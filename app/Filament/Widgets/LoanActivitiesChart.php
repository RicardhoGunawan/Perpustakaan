<?php

namespace App\Filament\Widgets;

use App\Models\BookLoan;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class LoanActivitiesChart extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Peminjaman Buku';
    protected static ?string $pollingInterval = null;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Trend::model(BookLoan::class)
            ->between(
                start: now()->subMonth(),
                end: now(),
            )
            ->perDay()
            ->count();
            
        $statusData = [
            'dipinjam' => Trend::model(BookLoan::class)
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->count('id', 'status', 'dipinjam'),
                
            'dikembalikan' => Trend::model(BookLoan::class)
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->count('id', 'status', 'dikembalikan'),
                
            'terlambat' => Trend::model(BookLoan::class)
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->count('id', 'status', 'terlambat'),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Peminjaman',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#3b82f6',
                    'fill' => false,
                ],
                [
                    'label' => 'Dipinjam',
                    'data' => $statusData['dipinjam']->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => '#f59e0b',
                ],
                [
                    'label' => 'Dikembalikan',
                    'data' => $statusData['dikembalikan']->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10b981',
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $statusData['terlambat']->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin']);
    }
}