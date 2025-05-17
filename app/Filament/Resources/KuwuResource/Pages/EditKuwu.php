<?php

namespace App\Filament\Resources\KuwuResource\Pages;

use App\Filament\Resources\KuwuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditKuwu extends EditRecord
{
    protected static string $resource = KuwuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // supaya tidak mengosongkan password lama
        }
        return $data;
    }
}
