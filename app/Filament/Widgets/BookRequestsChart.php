<?php

namespace App\Filament\Widgets;

use App\Models\BookRequest;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class BookRequestsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Permintaan Buku';
    protected static ?string $pollingInterval = null;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Trend::model(BookRequest::class)
            ->between(
                start: now()->subMonth(),
                end: now(),
            )
            ->perDay()
            ->count();
            
        $statusData = [
            'pending' => Trend::model(BookRequest::class)
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->where('id', 'status', 'pending')
                ->count(),
                
            'approved' => Trend::model(BookRequest::class)
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->whare('id', 'status', 'approved')
                ->count(),
                
            'rejected' => Trend::model(BookRequest::class)
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->whare('id', 'status', 'rejected')
                ->count(),

        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Permintaan',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#3b82f6',
                    'fill' => false,
                ],
                [
                    'label' => 'Pending',
                    'data' => $statusData['pending']->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => '#f59e0b',
                ],
                [
                    'label' => 'Disetujui',
                    'data' => $statusData['approved']->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10b981',
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Ditolak',
                    'data' => $statusData['rejected']->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin']);
    }
}