<?php

namespace App\Filament\Resources\PengajuanSktmSekolahResource\Pages;

use App\Filament\Resources\PengajuanSktmSekolahResource;
use App\Models\Pengajuan;
use App\Models\PengajuanSktmSekolah;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePengajuanSktmSekolah extends CreateRecord
{
    protected static string $resource = PengajuanSktmSekolahResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        Pengajuan::create([
            'id_admin' => Auth::id(),
            'kategori_pengajuan' => 3,
            'detail_type' => get_class($record),
            'detail_id' => $record->id,
            'status_pengajuan' => 1,
            'id_admin_updated' => Auth::id(),
            'id_kuwu_updated' => 1,
        ]);

        PengajuanSktmSekolah::created([
            'status_pengajuan' => 1
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Kalau kamu ada logic khusus pengajuan nanti di afterCreate
        // pastikan field `file_kk` disiapkan di sini dulu

        return $data;
    }
}
