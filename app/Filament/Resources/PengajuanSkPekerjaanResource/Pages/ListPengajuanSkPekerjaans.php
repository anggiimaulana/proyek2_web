<?php

namespace App\Filament\Resources\PengajuanSkPekerjaanResource\Pages;

use App\Filament\Resources\PengajuanSkPekerjaanResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSkPekerjaans extends ListRecords
{
    protected static string $resource = PengajuanSkPekerjaanResource::class;

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
