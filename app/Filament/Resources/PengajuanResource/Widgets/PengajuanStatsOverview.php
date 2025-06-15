<?php

namespace App\Filament\Resources\PengajuanResource\Widgets;

use App\Models\Pengajuan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class PengajuanStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        // Cache untuk optimasi performa
        $totalPengajuan = Cache::remember('total_pengajuan', 300, function () {
            return Pengajuan::count();
        });

        $pengajuanBulanIni = Cache::remember('pengajuan_bulan_ini_' . Carbon::now()->format('Y_m'), 300, function () {
            return Pengajuan::whereHas('detail', function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            })->count();
        });

        $pengajuanDiserahkan = Cache::remember('pengajuan_diserahkan', 300, function () {
            return Pengajuan::whereIn('status_pengajuan', [1])->count();
        });

        $pengajuanDiproses = Cache::remember('pengajuan_diproses', 300, function () {
            return Pengajuan::whereIn('status_pengajuan', [2])->count();
        });

        $pengajuanDitolak = Cache::remember('pengajuan_ditolak', 300, function () {
            return Pengajuan::whereIn('status_pengajuan', [3])->count();
        });

        $pengajuanSelesai = Cache::remember('pengajuan_selesai', 300, function () {
            return Pengajuan::where('status_pengajuan', 4)->count();
        });

        $pengajuanDirevisi = Cache::remember('pengajuan_direvisi', 300, function () {
            return Pengajuan::whereIn('status_pengajuan', [5])->count();
        });

        // Hitung persentase untuk bulan ini
        $persentaseBulanIni = $totalPengajuan > 0 ? round(($pengajuanBulanIni / $totalPengajuan) * 100, 1) : 0;

        // Hitung persentase pengajuan selesai
        $persentaseSelesai = $totalPengajuan > 0 ? round(($pengajuanSelesai / $totalPengajuan) * 100, 1) : 0;

        // Generate simple chart data untuk setiap stat
        $chartTotal = $this->generateChartData($totalPengajuan, 'growth');
        $chartBulanIni = $this->generateChartData($pengajuanBulanIni, 'steady');
        $chartDiserahkan = $this->generateChartData($pengajuanDiserahkan, 'pending');
        $chartDiproses = $this->generateChartData($pengajuanDiproses, 'process');
        $chartDitolak = $this->generateChartData($pengajuanDitolak, 'decline');
        $chartDirevisi = $this->generateChartData($pengajuanDirevisi, 'revision');
        $chartSelesai = $this->generateChartData($pengajuanSelesai, 'success');

        return [
            Stat::make('Total Pengajuan', number_format($totalPengajuan))
                ->description('Jumlah pengajuan masyarakat')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success')
                ->chart($chartTotal),

            Stat::make('Pengajuan Bulan Ini', number_format($pengajuanBulanIni))
                ->description($persentaseBulanIni . '% dari total • ' . Carbon::now()->format('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('gray')
                ->chart($chartBulanIni),

            Stat::make('Menunggu Pengecekan', number_format($pengajuanDiserahkan))
                ->description('Perlu pengecekan berkas')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart($chartDiserahkan),

            Stat::make('Sedang Diproses', number_format($pengajuanDiproses))
                ->description('Pengajuan diserahkan ke Kuwu')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('info')
                ->chart($chartDiproses),

            Stat::make('Pengajuan Ditolak', number_format($pengajuanDitolak))
                ->description('Perlu tindak lanjut pengguna')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->chart($chartDitolak),

            Stat::make('Perlu Revisi', number_format($pengajuanDirevisi))
                ->description('Perlu pengecekan berkas ulang')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('primary')
                ->chart($chartDirevisi),

            Stat::make('Pengajuan Selesai', number_format($pengajuanSelesai))
                ->description($persentaseSelesai . '% dari total • Disetujui Kuwu')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart($chartSelesai),
        ];
    }

    /**
     * Generate chart data berdasarkan tipe dan nilai
     */
    private function generateChartData(int $value, string $type): array
    {
        $baseValue = max(1, $value); // Minimal 1 untuk menghindari chart kosong

        switch ($type) {
            case 'growth':
                return [
                    $baseValue * 0.3,
                    $baseValue * 0.5,
                    $baseValue * 0.7,
                    $baseValue * 0.8,
                    $baseValue * 0.9,
                    $baseValue * 0.95,
                    $baseValue
                ];

            case 'steady':
                return [
                    $baseValue * 0.8,
                    $baseValue * 0.9,
                    $baseValue * 0.85,
                    $baseValue * 0.95,
                    $baseValue * 0.9,
                    $baseValue * 0.88,
                    $baseValue
                ];

            case 'pending':
                return [
                    $baseValue * 0.6,
                    $baseValue * 0.8,
                    $baseValue * 0.7,
                    $baseValue * 0.9,
                    $baseValue * 0.75,
                    $baseValue * 0.85,
                    $baseValue
                ];

            case 'process':
                return [
                    $baseValue * 0.4,
                    $baseValue * 0.6,
                    $baseValue * 0.8,
                    $baseValue * 0.7,
                    $baseValue * 0.9,
                    $baseValue * 0.85,
                    $baseValue
                ];

            case 'decline':
                return [
                    $baseValue * 1.2,
                    $baseValue * 1.1,
                    $baseValue * 1.0,
                    $baseValue * 0.9,
                    $baseValue * 0.8,
                    $baseValue * 0.9,
                    $baseValue
                ];

            case 'revision':
                return [
                    $baseValue * 0.9,
                    $baseValue * 1.1,
                    $baseValue * 0.8,
                    $baseValue * 1.0,
                    $baseValue * 0.9,
                    $baseValue * 1.05,
                    $baseValue
                ];

            case 'success':
                return [
                    $baseValue * 0.2,
                    $baseValue * 0.4,
                    $baseValue * 0.6,
                    $baseValue * 0.7,
                    $baseValue * 0.85,
                    $baseValue * 0.92,
                    $baseValue
                ];

            default:
                return [$baseValue, $baseValue, $baseValue, $baseValue, $baseValue, $baseValue, $baseValue];
        }
    }

    protected function getColumns(): int
    {
        return 4; // Tampilkan 4 kolom untuk layout yang lebih baik
    }

    // Auto-refresh setiap 60 detik untuk data pengajuan
    protected static ?string $pollingInterval = '60s';
}
