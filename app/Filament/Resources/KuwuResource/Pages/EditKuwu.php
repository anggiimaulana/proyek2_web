<?php

namespace App\Filament\Resources\KuwuResource\Pages;

use App\Filament\Resources\KuwuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKuwu extends EditRecord
{
    protected static string $resource = KuwuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
