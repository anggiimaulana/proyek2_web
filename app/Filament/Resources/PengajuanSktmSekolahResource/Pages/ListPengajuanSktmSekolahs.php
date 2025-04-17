<?php

namespace App\Filament\Resources\PengajuanSktmSekolahResource\Pages;

use App\Filament\Resources\PengajuanSktmSekolahResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSktmSekolahs extends ListRecords
{
    protected static string $resource = PengajuanSktmSekolahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['pengajuan.statusPengajuan']);
    }
}
