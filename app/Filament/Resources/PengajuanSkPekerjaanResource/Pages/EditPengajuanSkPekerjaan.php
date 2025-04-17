<?php

namespace App\Filament\Resources\PengajuanSkPekerjaanResource\Pages;

use App\Filament\Resources\PengajuanSkPekerjaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSkPekerjaan extends EditRecord
{
    protected static string $resource = PengajuanSkPekerjaanResource::class;

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
