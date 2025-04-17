<?php

namespace App\Filament\Resources\PengajuanSkUsahaResource\Pages;

use App\Filament\Resources\PengajuanSkUsahaResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSkUsahas extends ListRecords
{
    protected static string $resource = PengajuanSkUsahaResource::class;

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
