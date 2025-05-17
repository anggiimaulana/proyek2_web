<?php

namespace App\Filament\Resources\PengajuanSkBelumMenikahResource\Pages;

use App\Filament\Resources\PengajuanSkBelumMenikahResource;
use App\Models\Pengajuan;
use App\Models\PengajuanSkBelumMenikah;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePengajuanSkBelumMenikah extends CreateRecord
{
    protected static string $resource = PengajuanSkBelumMenikahResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        Pengajuan::create([
            'id_admin' => Auth::id(),
            'kategori_pengajuan' => 5,
            'detail_type' => get_class($record),
            'detail_id' => $record->id,
            'status_pengajuan' => 1,
            'id_admin_updated' => Auth::id(),
            'id_kuwu_updated' => 1,
        ]);

        PengajuanSkBelumMenikah::created([
            'status_pengajuan' => 1
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pastikan file_kk disimpan sebagai string
        if (is_array($data['file_kk'])) {
            $data['file_kk'] = $data['file_kk'][0];
        }

        return $data;
    }
}
