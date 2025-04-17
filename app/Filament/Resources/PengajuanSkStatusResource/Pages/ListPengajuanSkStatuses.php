<?php

namespace App\Filament\Resources\PengajuanSkStatusResource\Pages;

use App\Filament\Resources\PengajuanSkStatusResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSkStatuses extends ListRecords
{
    protected static string $resource = PengajuanSkStatusResource::class;

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
