<?php

namespace App\Filament\Resources\PengajuanSktmBeasiswaResource\Pages;

use App\Filament\Resources\PengajuanSktmBeasiswaResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSktmBeasiswas extends ListRecords
{
    protected static string $resource = PengajuanSktmBeasiswaResource::class;

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
