<?php

namespace App\Filament\KuwuPanel\Resources\PengajuanResource\Widgets;

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

        $pengajuanProsesAdmin = Cache::remember('pengajuan_proses_admin', 300, function () {
            return Pengajuan::whereIn('status_pengajuan', [1, 3, 5])->count();
        });

        $pengajuanDiproses = Cache::remember('pengajuan_diproses', 300, function () {
            return Pengajuan::whereIn('status_pengajuan', [2])->count();
        });

        $pengajuanSelesai = Cache::remember('pengajuan_selesai', 300, function () {
            return Pengajuan::where('status_pengajuan', 4)->count();
        });

        // Hitung persentase pengajuan selesai
        $persentaseSelesai = $totalPengajuan > 0 ? round(($pengajuanSelesai / $totalPengajuan) * 100, 1) : 0;

        // Generate simple chart data untuk setiap stat
        $chartTotal = $this->generateChartData($totalPengajuan, 'growth');
        $chartDiproses = $this->generateChartData($pengajuanDiproses, 'process');
        $chartSelesai = $this->generateChartData($pengajuanSelesai, 'success');
        $chartProsesAdmin = $this->generateChartData($pengajuanProsesAdmin, 'warning');

        return [
            Stat::make('Total Pengajuan', number_format($totalPengajuan))
                ->description('Jumlah keseluruhan pengajuan masyarakat')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success')
                ->chart($chartTotal),

            Stat::make('Menunggu Seleksi Berkas', number_format($pengajuanProsesAdmin))
                ->description('Pengajuan yang membutuhkan seleksi berkas oleh Admin')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('warning')
                ->chart($chartProsesAdmin),

            Stat::make('Menunggu Persetujuan', number_format($pengajuanDiproses))
                ->description('Pengajuan yang membutuhkan persetujuan')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('info')
                ->chart($chartDiproses),

            Stat::make('Pengajuan Disetujui', number_format($pengajuanSelesai))
                ->description($persentaseSelesai . '% dari total jumlah pengajuan')
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
