<?php

namespace App\Filament\Resources\PengajuanSkStatusResource\Pages;

use App\Filament\Resources\PengajuanSkStatusResource;
use App\Models\Pengajuan;
use App\Models\PengajuanSkStatus;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePengajuanSkStatus extends CreateRecord
{
    protected static string $resource = PengajuanSkStatusResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        Pengajuan::create([
            'id_admin' => Auth::id(),
            'kategori_pengajuan' => 4,
            'detail_type' => get_class($record),
            'detail_id' => $record->id,
            'status_pengajuan' => 1,
            'id_admin_updated' => Auth::id(),
            'id_kuwu_updated' => 1,
        ]);

        PengajuanSkStatus::created([
            'status_pengajuan' => 1
        ]);
    }
}
