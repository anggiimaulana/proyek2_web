<?php

namespace App\Filament\KuwuPanel\Resources\KartuKeluargaResource\Pages;

use App\Filament\KuwuPanel\Resources\KartuKeluargaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKartuKeluarga extends EditRecord
{
    protected static string $resource = KartuKeluargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
