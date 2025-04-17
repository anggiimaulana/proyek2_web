<?php

namespace App\Filament\Resources\PengajuanSkUsahaResource\Pages;

use App\Filament\Resources\PengajuanSkUsahaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSkUsaha extends EditRecord
{
    protected static string $resource = PengajuanSkUsahaResource::class;

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
