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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $catatan = $data['pengajuan']['catatan'] ?? null;
        $statusPengajuan = $data['pengajuan']['status_pengajuan'] ?? null;

        unset($data['pengajuan']);

        $this->record->pengajuan->update([
            'catatan' => $catatan,
            'status_pengajuan' => $statusPengajuan,
        ]);

        // if (isset($data['file_kk']) && is_array($data['file_kk'])) {
        //     $data['file_kk'] = $data['file_kk'][0];
        // }


        return $data;
    }
}
