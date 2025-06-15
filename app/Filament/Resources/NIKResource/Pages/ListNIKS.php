<?php

namespace App\Filament\Resources\NIKResource\Pages;

use App\Filament\Resources\NIKResource;
use App\Filament\Resources\NIKResource\Widgets\PendudukStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNIKS extends ListRecords
{
    protected static string $resource = NIKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data Penduduk')
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PendudukStatsWidget::class,
        ];
    }

    // Optional: Menambahkan title khusus untuk halaman
    public function getTitle(): string
    {
        return 'Dashboard Data Penduduk';
    }

    // Optional: Menambahkan subtitle
    public function getSubheading(): ?string
    {
        return 'Kelola dan pantau data penduduk desa';
    }
}
