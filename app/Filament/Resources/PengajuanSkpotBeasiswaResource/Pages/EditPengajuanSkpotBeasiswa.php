<?php

namespace App\Filament\Resources\PengajuanSkpotBeasiswaResource\Pages;

use App\Filament\Resources\PengajuanSkpotBeasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSkpotBeasiswa extends EditRecord
{
    protected static string $resource = PengajuanSkpotBeasiswaResource::class;

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
