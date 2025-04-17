<?php

namespace App\Filament\Resources\PengajuanSkpPengajuanBantuanResource\Pages;

use App\Filament\Resources\PengajuanSkpPengajuanBantuanResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSkpPengajuanBantuans extends ListRecords
{
    protected static string $resource = PengajuanSkpPengajuanBantuanResource::class;

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
