<?php

namespace App\Filament\Resources\PengajuanSktmListrikResource\Pages;

use App\Filament\Resources\PengajuanSktmListrikResource;
use Filament\Actions;
use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSktmListriks extends ListRecords
{
    protected static string $resource = PengajuanSktmListrikResource::class;

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
