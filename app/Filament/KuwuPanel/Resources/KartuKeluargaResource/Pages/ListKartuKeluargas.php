<?php

namespace App\Filament\KuwuPanel\Resources\KartuKeluargaResource\Pages;

use App\Filament\KuwuPanel\Resources\KartuKeluargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKartuKeluargas extends ListRecords
{
    protected static string $resource = KartuKeluargaResource::class;

    protected function canCreate(): bool
    {
        return false;
    }
}
