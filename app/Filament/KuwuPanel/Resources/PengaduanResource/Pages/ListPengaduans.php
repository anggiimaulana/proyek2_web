<?php

namespace App\Filament\KuwuPanel\Resources\PengaduanResource\Pages;

use App\Filament\KuwuPanel\Resources\PengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengaduans extends ListRecords
{
    protected static string $resource = PengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
