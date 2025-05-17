<?php

namespace App\Filament\Resources\PengajuanSkUsahaResource\Pages;

use App\Filament\Resources\PengajuanSkUsahaResource;
use App\Models\Pengajuan;
use App\Models\PengajuanSkUsaha;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePengajuanSkUsaha extends CreateRecord
{
    protected static string $resource = PengajuanSkUsahaResource::class;
    protected function afterCreate(): void
    {
        $record = $this->record;

        Pengajuan::create([
            'id_admin' => Auth::id(),
            'kategori_pengajuan' => 8,
            'detail_type' => get_class($record),
            'detail_id' => $record->id,
            'status_pengajuan' => 1,
            'id_admin_updated' => Auth::id(),
            'id_kuwu_updated' => 1,
        ]);

        PengajuanSkUsaha::created([
            'status_pengajuan' => 1
        ]);
    }
}
