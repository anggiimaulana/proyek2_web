<?php

namespace App\Filament\KuwuPanel\Resources\NikResource\Pages;

use App\Filament\KuwuPanel\Resources\NikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNiks extends ListRecords
{
    protected static string $resource = NikResource::class;

    protected function canCreate(): bool
    {
        return false;
    }
}
