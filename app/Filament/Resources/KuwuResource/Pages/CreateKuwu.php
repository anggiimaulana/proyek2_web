<?php

namespace App\Filament\Resources\KuwuResource\Pages;

use App\Filament\Resources\KuwuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateKuwu extends CreateRecord
{
    protected static string $resource = KuwuResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        return $data;
    }
}
