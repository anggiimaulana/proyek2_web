<?php

namespace App\Filament\Resources\PengajuanSkpPengajuanBantuanResource\Pages;

use App\Filament\Resources\PengajuanSkpPengajuanBantuanResource;
use App\Models\Pengajuan;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePengajuanSkpPengajuanBantuan extends CreateRecord
{
    protected static string $resource = PengajuanSkpPengajuanBantuanResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        Pengajuan::create([
            'id_admin' => Auth::id(), // <-- Pakai Auth::id()
            'kategori_pengajuan' => 9,
            'detail_type' => get_class($record),
            'detail_id' => $record->id,
            'status_pengajuan' => 1,
            'id_admin_updated' => Auth::id(),
            'id_kuwu_updated' => 1,
        ]);
    }
}
