<?php

namespace App\Filament\Resources\PengajuanSktmBeasiswaResource\Pages;

use App\Filament\Resources\PengajuanSktmBeasiswaResource;
use App\Models\Pengajuan;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePengajuanSktmBeasiswa extends CreateRecord
{
    protected static string $resource = PengajuanSktmBeasiswaResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        Pengajuan::create([
            'id_admin' => Auth::id(), // <-- Pakai Auth::id()
            'kategori_pengajuan' => 2,
            'detail_type' => get_class($record),
            'detail_id' => $record->id,
            'status_pengajuan' => 1,
            'id_admin_updated' => Auth::id(),
            'id_kuwu_updated' => 1,
        ]);
    }
}
