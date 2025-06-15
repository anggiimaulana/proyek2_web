<?php

namespace App\Filament\KuwuPanel\Resources\PengajuanResource\Pages;

use App\Filament\KuwuPanel\Resources\PengajuanResource;
use App\Filament\KuwuPanel\Resources\PengajuanResource\Widgets\PengajuanStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuans extends ListRecords
{
    protected static string $resource = PengajuanResource::class;

    // Tambahkan method ini untuk menampilkan widget
    protected function getHeaderWidgets(): array
    {
        return [
            PengajuanStatsOverview::class,
        ];
    }

    // Tambahkan method ini untuk filter
    public function getHeaderWidgetsColumns(): int
    {
        return 1; // Full width
    }

    protected function canCreate(): bool
    {
        return false;
    }
}
