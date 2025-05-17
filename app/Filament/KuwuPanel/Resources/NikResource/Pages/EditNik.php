<?php

namespace App\Filament\KuwuPanel\Resources\NikResource\Pages;

use App\Filament\KuwuPanel\Resources\NikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNik extends EditRecord
{
    protected static string $resource = NikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
