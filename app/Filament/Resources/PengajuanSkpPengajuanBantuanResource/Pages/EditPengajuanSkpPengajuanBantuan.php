<?php

namespace App\Filament\Resources\PengajuanSkpPengajuanBantuanResource\Pages;

use App\Filament\Resources\PengajuanSkpPengajuanBantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSkpPengajuanBantuan extends EditRecord
{
    protected static string $resource = PengajuanSkpPengajuanBantuanResource::class;

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
