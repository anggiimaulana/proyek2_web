<?php

namespace App\Filament\Resources\PengajuanSkpotBeasiswaResource\Pages;

use App\Filament\Resources\PengajuanSkpotBeasiswaResource;
use App\Models\Pengajuan;
use App\Models\PengajuanSkpotBeasiswa;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePengajuanSkpotBeasiswa extends CreateRecord
{
    protected static string $resource = PengajuanSkpotBeasiswaResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        Pengajuan::create([
            'id_admin' => Auth::id(),
            'kategori_pengajuan' => 7,
            'detail_type' => get_class($record),
            'detail_id' => $record->id,
            'status_pengajuan' => 1,
            'id_admin_updated' => Auth::id(),
            'id_kuwu_updated' => 1,
        ]);

        PengajuanSkpotBeasiswa::created([
            'status_pengajuan' => 1
        ]);
    }
}
