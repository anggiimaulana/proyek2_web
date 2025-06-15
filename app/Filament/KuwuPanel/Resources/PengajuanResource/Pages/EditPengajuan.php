<?php

namespace App\Filament\KuwuPanel\Resources\PengajuanResource\Pages;

use App\Filament\KuwuPanel\Resources\PengajuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuan extends EditRecord
{
    protected static string $resource = PengajuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
