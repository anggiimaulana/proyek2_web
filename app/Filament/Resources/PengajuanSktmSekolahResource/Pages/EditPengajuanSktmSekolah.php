<?php

namespace App\Filament\Resources\PengajuanSktmSekolahResource\Pages;

use App\Filament\Resources\PengajuanSktmSekolahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSktmSekolah extends EditRecord
{
    protected static string $resource = PengajuanSktmSekolahResource::class;

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
