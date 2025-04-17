<?php

namespace App\Filament\Resources\PengajuanSkStatusResource\Pages;

use App\Filament\Resources\PengajuanSkStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSkStatus extends EditRecord
{
    protected static string $resource = PengajuanSkStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveRecord($key): Model
    {
        return parent::resolveRecord($key)->load('pengajuan');
    }
}
