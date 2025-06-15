<?php

namespace App\Filament\KuwuPanel\Resources\PengaduanResource\Pages;

use App\Filament\KuwuPanel\Resources\PengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaduan extends EditRecord
{
    protected static string $resource = PengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
