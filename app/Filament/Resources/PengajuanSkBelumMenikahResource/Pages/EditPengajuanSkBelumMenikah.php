<?php

namespace App\Filament\Resources\PengajuanSkBelumMenikahResource\Pages;

use App\Filament\Resources\PengajuanSkBelumMenikahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSkBelumMenikah extends EditRecord
{
    protected static string $resource = PengajuanSkBelumMenikahResource::class;

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
