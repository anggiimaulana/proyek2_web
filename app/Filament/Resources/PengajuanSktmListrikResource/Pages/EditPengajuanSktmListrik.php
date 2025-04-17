<?php

namespace App\Filament\Resources\PengajuanSktmListrikResource\Pages;

use App\Filament\Resources\PengajuanSktmListrikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSktmListrik extends EditRecord
{
    protected static string $resource = PengajuanSktmListrikResource::class;

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
