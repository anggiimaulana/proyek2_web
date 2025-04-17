<?php

namespace App\Filament\Resources\PengajuanSktmListrikResource\Pages;

use App\Filament\Resources\PengajuanSktmListrikResource;
use App\Models\Pengajuan;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth; // <- Tambahkan ini

class CreatePengajuanSktmListrik extends CreateRecord
{
    protected static string $resource = PengajuanSktmListrikResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        Pengajuan::create([
            'id_admin' => Auth::id(), // <-- Pakai Auth::id()
            'kategori_pengajuan' => 1,
            'detail_type' => get_class($record),
            'detail_id' => $record->id,
            'status_pengajuan' => 1,
            'id_admin_updated' => Auth::id(),
            'id_kuwu_updated' => 1,
        ]);
    }
}
