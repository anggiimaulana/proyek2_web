<?php

namespace App\Filament\Resources\PengajuanSkPekerjaanResource\Pages;

use App\Filament\Resources\PengajuanSkPekerjaanResource;
use App\Models\Pengajuan;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePengajuanSkPekerjaan extends CreateRecord
{
    protected static string $resource = PengajuanSkPekerjaanResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        Pengajuan::create([
            'id_admin' => Auth::id(),
            'kategori_pengajuan' => 6,
            'detail_type' => get_class($record),
            'detail_id' => $record->id,
            'status_pengajuan' => 1,
            'id_admin_updated' => Auth::id(),
            'id_kuwu_updated' => 1,
        ]);
    }
}
