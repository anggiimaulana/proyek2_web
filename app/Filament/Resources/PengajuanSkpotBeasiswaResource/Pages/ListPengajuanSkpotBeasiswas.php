<?php

namespace App\Filament\Resources\PengajuanSkpotBeasiswaResource\Pages;

use App\Filament\Resources\PengajuanSkpotBeasiswaResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSkpotBeasiswas extends ListRecords
{
    protected static string $resource = PengajuanSkpotBeasiswaResource::class;

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
