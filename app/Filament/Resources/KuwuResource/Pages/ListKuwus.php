<?php

namespace App\Filament\Resources\KuwuResource\Pages;

use App\Filament\Resources\KuwuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKuwus extends ListRecords
{
    protected static string $resource = KuwuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
