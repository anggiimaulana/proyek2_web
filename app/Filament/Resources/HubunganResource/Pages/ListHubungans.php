<?php

namespace App\Filament\Resources\HubunganResource\Pages;

use App\Filament\Resources\HubunganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHubungans extends ListRecords
{
    protected static string $resource = HubunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
