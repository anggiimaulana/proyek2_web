<?php

namespace App\Filament\Resources\PengajuanResource\Widgets;

use App\Models\Pengajuan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JenisSuratChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Jenis Surat';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Pengajuan::with('kategoriPengajuan')
            ->select('kategori_pengajuan', DB::raw('count(*) as total'))
            ->groupBy('kategori_pengajuan')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'jenis' => $item->kategoriPengajuan->nama_kategori ?? 'Tidak Diketahui',
                    'total' => $item->total
                ];
            });

        $colors = [
            '#ef4444',
            '#f97316',
            '#eab308',
            '#22c55e',
            '#06b6d4',
            '#3b82f6',
            '#8b5cf6',
            '#ec4899'
        ];

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $data->count()),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ]
            ],
            'labels' => $data->pluck('jenis')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                    ]
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.label + ": " + context.parsed + " pengajuan (" + Math.round((context.parsed / context.dataset.data.reduce((a, b) => a + b, 0)) * 100) + "%)";
                        }'
                    ]
                ]
            ],
            'cutout' => '75%',
        ];
    }
}
