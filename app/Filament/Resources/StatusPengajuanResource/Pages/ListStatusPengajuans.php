<?php

namespace App\Filament\Resources\StatusPengajuanResource\Pages;

use App\Filament\Resources\StatusPengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatusPengajuans extends ListRecords
{
    protected static string $resource = StatusPengajuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
