<?php

namespace App\Filament\KuwuPanel\Resources\PengajuanResource\Pages;

use App\Filament\KuwuPanel\Resources\PengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuans extends ListRecords
{
    protected static string $resource = PengajuanResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }

    protected function canCreate(): bool
    {
        return false;
    }
}
