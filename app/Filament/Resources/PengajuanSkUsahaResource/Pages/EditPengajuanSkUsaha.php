<?php

namespace App\Filament\Resources\PengajuanSkUsahaResource\Pages;

use App\Filament\Resources\PengajuanSkUsahaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengajuanSkUsaha extends EditRecord
{
    protected static string $resource = PengajuanSkUsahaResource::class;

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
        // Ambil data dari field relasi pengajuan
        $catatan = $data['pengajuan']['catatan'] ?? null;
        $statusPengajuan = $data['pengajuan']['status_pengajuan'] ?? null;

        // Hapus agar tidak konflik saat menyimpan model utama
        unset($data['pengajuan']);

        // Simpan manual ke tabel pengajuan
        $this->record->pengajuan->update([
            'catatan' => $catatan,
            'status_pengajuan' => $statusPengajuan,
        ]);

        // if (isset($data['file_ktp']) && is_array($data['file_kk'])) {
        //     $data['file_kk'] = $data['file_kk'][0];
        // }

        return $data;
    }
}
