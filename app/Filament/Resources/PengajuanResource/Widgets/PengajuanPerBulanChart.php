<?php

namespace App\Filament\Resources\PengajuanResource\Widgets;

use App\Models\Pengajuan;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PengajuanPerBulanChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Pengajuan Surat per Bulan';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    // Property untuk menentukan range default
    protected string $rangeType = 'auto_yearly';

    // Method untuk mendapatkan filter yang aktif
    protected function getRangeType(): string
    {
        // Ambil dari page filter jika ada, kalau tidak pakai default
        return $this->filters['range_type'] ?? $this->rangeType;
    }

    protected function getData(): array
    {
        $currentRangeType = $this->getRangeType();
        $months = $this->getMonthsRange($currentRangeType);

        $data = $months->map(function ($month) {
            $count = Pengajuan::whereHas('detail', function ($query) use ($month) {
                $query->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month);
            })->count();

            return [
                'month' => $month->format('M Y'),
                'count' => $count,
                'is_current' => $month->isSameMonth(Carbon::now())
            ];
        });

        // Highlight bulan current dengan warna berbeda
        $backgroundColors = $data->map(function ($item) {
            return $item['is_current'] ? 'rgba(34, 197, 94, 0.2)' : 'rgba(59, 130, 246, 0.1)';
        });

        $borderColors = $data->map(function ($item) {
            return $item['is_current'] ? 'rgb(34, 197, 94)' : 'rgb(59, 130, 246)';
        });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengajuan',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => $backgroundColors->toArray(),
                    'borderColor' => $borderColors->toArray(),
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => $borderColors->toArray(),
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ]
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getMonthsRange(string $rangeType = null)
    {
        $type = $rangeType ?? $this->getRangeType();

        switch ($type) {
            case 'last_12_months':
                // 12 bulan terakhir dari sekarang
                $months = collect();
                for ($i = 11; $i >= 0; $i--) {
                    $months->push(Carbon::now()->subMonths($i));
                }
                return $months;

            case 'current_year':
                // Tahun sekarang (Jan - Des)
                $currentYear = Carbon::now()->year;
                $months = collect();
                for ($i = 1; $i <= 12; $i++) {
                    $months->push(Carbon::create($currentYear, $i, 1));
                }
                return $months;

            case 'last_year':
                // Tahun lalu (Jan - Des)
                $lastYear = Carbon::now()->year - 1;
                $months = collect();
                for ($i = 1; $i <= 12; $i++) {
                    $months->push(Carbon::create($lastYear, $i, 1));
                }
                return $months;

            case 'auto_yearly':
                // Otomatis berdasarkan tahun sekarang (Mei ke Mei)
                $currentYear = Carbon::now()->year;
                $startDate = Carbon::create($currentYear, 5, 1);
                $endDate = Carbon::create($currentYear + 1, 5, 31);

                $months = collect();
                $currentMonth = $startDate->copy();

                while ($currentMonth->lte($endDate)) {
                    $months->push($currentMonth->copy());
                    $currentMonth->addMonth();
                }
                return $months;

            case 'academic_year':
                // Berdasarkan tahun akademik (Juli - Juni)
                $now = Carbon::now();
                $startMonth = 7; // Juli

                if ($now->month >= $startMonth) {
                    $startYear = $now->year;
                    $endYear = $now->year + 1;
                } else {
                    $startYear = $now->year - 1;
                    $endYear = $now->year;
                }

                $startDate = Carbon::create($startYear, $startMonth, 1);
                $endDate = Carbon::create($endYear, $startMonth - 1, 1)->endOfMonth();

                $months = collect();
                $currentMonth = $startDate->copy();

                while ($currentMonth->lte($endDate)) {
                    $months->push($currentMonth->copy());
                    $currentMonth->addMonth();
                }
                return $months;

            case 'fiscal_year':
                // Berdasarkan tahun fiskal (April - Maret)
                $now = Carbon::now();
                $fiscalStartMonth = 4; // April

                if ($now->month >= $fiscalStartMonth) {
                    $startDate = Carbon::create($now->year, $fiscalStartMonth, 1);
                    $endDate = Carbon::create($now->year + 1, $fiscalStartMonth - 1, 1)->endOfMonth();
                } else {
                    $startDate = Carbon::create($now->year - 1, $fiscalStartMonth, 1);
                    $endDate = Carbon::create($now->year, $fiscalStartMonth - 1, 1)->endOfMonth();
                }

                $months = collect();
                $currentMonth = $startDate->copy();

                while ($currentMonth->lte($endDate)) {
                    $months->push($currentMonth->copy());
                    $currentMonth->addMonth();
                }
                return $months;

            case 'smart_range':
                // Smart range - otomatis menyesuaikan
                $now = Carbon::now();

                if ($now->month <= 4) {
                    $startDate = Carbon::create($now->year - 1, 5, 1);
                    $endDate = Carbon::create($now->year, 5, 31);
                } else {
                    $startDate = Carbon::create($now->year, 5, 1);
                    $endDate = Carbon::create($now->year + 1, 5, 31);
                }

                $months = collect();
                $currentMonth = $startDate->copy();

                while ($currentMonth->lte($endDate)) {
                    $months->push($currentMonth->copy());
                    $currentMonth->addMonth();
                }
                return $months;

            case 'last_6_months':
                // 6 bulan terakhir
                $months = collect();
                for ($i = 5; $i >= 0; $i--) {
                    $months->push(Carbon::now()->subMonths($i));
                }
                return $months;

            case 'year_to_date':
                // Dari Januari tahun ini sampai sekarang
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now();

                $months = collect();
                $currentMonth = $startDate->copy();

                while ($currentMonth->lte($endDate)) {
                    $months->push($currentMonth->copy());
                    $currentMonth->addMonth();
                }
                return $months;

            default:
                // Default ke auto_yearly
                return $this->getMonthsRange('auto_yearly');
        }
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        $rangeType = $this->getRangeType();

        switch ($rangeType) {
            case 'last_12_months':
                return 'Pengajuan Surat per Bulan (12 Bulan Terakhir)';
            case 'current_year':
                $currentYear = Carbon::now()->year;
                return "Pengajuan Surat per Bulan (Tahun {$currentYear})";
            case 'last_year':
                $lastYear = Carbon::now()->year - 1;
                return "Pengajuan Surat per Bulan (Tahun {$lastYear})";
            case 'auto_yearly':
                $currentYear = Carbon::now()->year;
                return "Pengajuan Surat per Bulan (Mei {$currentYear} - Mei " . ($currentYear + 1) . ")";
            case 'academic_year':
                $now = Carbon::now();
                $startMonth = 7;
                if ($now->month >= $startMonth) {
                    $startYear = $now->year;
                    $endYear = $now->year + 1;
                } else {
                    $startYear = $now->year - 1;
                    $endYear = $now->year;
                }
                return "Pengajuan Surat per Bulan (Tahun Akademik {$startYear}/{$endYear})";
            case 'fiscal_year':
                $now = Carbon::now();
                $fiscalStartMonth = 4;
                if ($now->month >= $fiscalStartMonth) {
                    $startYear = $now->year;
                    $endYear = $now->year + 1;
                } else {
                    $startYear = $now->year - 1;
                    $endYear = $now->year;
                }
                return "Pengajuan Surat per Bulan (Tahun Fiskal {$startYear}/{$endYear})";
            case 'smart_range':
                $now = Carbon::now();
                if ($now->month <= 4) {
                    $startYear = $now->year - 1;
                    $endYear = $now->year;
                } else {
                    $startYear = $now->year;
                    $endYear = $now->year + 1;
                }
                return "Pengajuan Surat per Bulan (Mei {$startYear} - Mei {$endYear})";
            case 'last_6_months':
                return 'Pengajuan Surat per Bulan (6 Bulan Terakhir)';
            case 'year_to_date':
                $currentYear = Carbon::now()->year;
                return "Pengajuan Surat per Bulan (Jan - " . Carbon::now()->format('M') . " {$currentYear})";
            default:
                return 'Pengajuan Surat per Bulan';
        }
    }

    // Method helper untuk mendapatkan info periode saat ini
    public function getCurrentPeriodInfo(): array
    {
        $months = $this->getMonthsRange();
        return [
            'start' => $months->first(),
            'end' => $months->last(),
            'total_months' => $months->count(),
            'current_month' => Carbon::now(),
            'range_type' => $this->getRangeType()
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'elements' => [
                'point' => [
                    'hoverBackgroundColor' => '#ffffff'
                ]
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'color' => '#6b7280',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Pengajuan',
                        'color' => '#374151',
                        'font' => [
                            'size' => 12,
                            'weight' => 'bold'
                        ]
                    ],
                    'grid' => [
                        'color' => 'rgba(107, 114, 128, 0.1)'
                    ]
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Periode',
                        'color' => '#374151',
                        'font' => [
                            'size' => 12,
                            'weight' => 'bold'
                        ]
                    ],
                    'ticks' => [
                        'color' => '#6b7280',
                    ],
                    'grid' => [
                        'display' => false
                    ]
                ]
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
                        'color' => '#374151'
                    ]
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => '#ffffff',
                    'bodyColor' => '#ffffff',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 1,
                    'callbacks' => [
                        'title' => 'function(context) {
                            return "Periode: " + context[0].label;
                        }',
                        'label' => 'function(context) {
                            return context.dataset.label + ": " + context.parsed.y + " pengajuan";
                        }',
                        'afterLabel' => 'function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed.y / total) * 100).toFixed(1);
                            return "Persentase: " + percentage + "%";
                        }'
                    ]
                ],
            ],
            'interaction' => [
                'mode' => 'nearest',
                'axis' => 'x',
                'intersect' => false,
            ],
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeInOutQuart'
            ]
        ];
    }

    // Static method untuk mendapatkan pilihan filter
    public static function getRangeTypeOptions(): array
    {
        return [
            'auto_yearly' => 'Mei ke Mei (Otomatis)',
            'last_12_months' => '12 Bulan Terakhir',
            'current_year' => 'Tahun Sekarang',
            'last_year' => 'Tahun Lalu',
            'academic_year' => 'Tahun Akademik (Jul-Jun)',
            'fiscal_year' => 'Tahun Fiskal (Apr-Mar)',
            'smart_range' => 'Smart Range',
            'last_6_months' => '6 Bulan Terakhir',
            'year_to_date' => 'Year to Date'
        ];
    }
}
