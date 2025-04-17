<?php

namespace App\Filament\Resources\PengajuanSkBelumMenikahResource\Pages;

use App\Filament\Resources\PengajuanSkBelumMenikahResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSkBelumMenikahs extends ListRecords
{
    protected static string $resource = PengajuanSkBelumMenikahResource::class;

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
