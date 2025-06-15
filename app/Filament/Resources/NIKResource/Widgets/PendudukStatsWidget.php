<?php

namespace App\Filament\Resources\NIKResource\Widgets;

use App\Models\NIK;
use App\Models\JenisKelamin;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class PendudukStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Cache untuk optimasi performa
        $totalPenduduk = Cache::remember('total_penduduk', 3600, function () {
            return NIK::count();
        });

        // Dapatkan ID jenis kelamin dari database
        $jenisKelaminLakiLaki = JenisKelamin::where('jenis_kelamin', 'LIKE', '%laki%')
            ->orWhere('jenis_kelamin', 'LIKE', '%Laki%')
            ->orWhere('jenis_kelamin', 'LIKE', '%LAKI%')
            ->first();

        $jenisKelaminPerempuan = JenisKelamin::where('jenis_kelamin', 'LIKE', '%perempuan%')
            ->orWhere('jenis_kelamin', 'LIKE', '%Perempuan%')
            ->orWhere('jenis_kelamin', 'LIKE', '%PEREMPUAN%')
            ->first();

        $totalLakiLaki = Cache::remember('total_laki_laki', 3600, function () use ($jenisKelaminLakiLaki) {
            if ($jenisKelaminLakiLaki) {
                return NIK::where('jk', $jenisKelaminLakiLaki->id)->count();
            }
            return 0;
        });

        $totalPerempuan = Cache::remember('total_perempuan', 3600, function () use ($jenisKelaminPerempuan) {
            if ($jenisKelaminPerempuan) {
                return NIK::where('jk', $jenisKelaminPerempuan->id)->count();
            }
            return 0;
        });

        // Hitung persentase
        $persentaseLakiLaki = $totalPenduduk > 0 ? round(($totalLakiLaki / $totalPenduduk) * 100, 1) : 0;
        $persentasePerempuan = $totalPenduduk > 0 ? round(($totalPerempuan / $totalPenduduk) * 100, 1) : 0;

        return [
            Stat::make('Total Penduduk', number_format($totalPenduduk))
                ->description('Jumlah seluruh penduduk terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Laki-laki', number_format($totalLakiLaki))
                ->description("{$persentaseLakiLaki}% dari total penduduk")
                ->descriptionIcon('heroicon-m-user')
                ->color('success')
                ->chart([15, 4, 10, 2, 12, 4, 12]),

            Stat::make('Perempuan', number_format($totalPerempuan))
                ->description("{$persentasePerempuan}% dari total penduduk")
                ->descriptionIcon('heroicon-m-user')
                ->color('warning')
                ->chart([7, 3, 8, 5, 9, 3, 11]),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }

    // Refresh data setiap 30 detik
    protected static ?string $pollingInterval = '30s';
}
