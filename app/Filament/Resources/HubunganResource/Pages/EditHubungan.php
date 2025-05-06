<?php

namespace App\Filament\Resources\HubunganResource\Pages;

use App\Filament\Resources\HubunganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHubungan extends EditRecord
{
    protected static string $resource = HubunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
