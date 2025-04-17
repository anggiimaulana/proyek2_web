<?php

namespace App\Filament\Resources\PengajuanSktmBeasiswaResource\Pages;

use App\Filament\Resources\PengajuanSktmBeasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSktmBeasiswa extends EditRecord
{
    protected static string $resource = PengajuanSktmBeasiswaResource::class;

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
