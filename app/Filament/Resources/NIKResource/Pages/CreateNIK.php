<?php

namespace App\Filament\Resources\NIKResource\Pages;

use App\Filament\Resources\NIKResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateNIK extends CreateRecord
{
    protected static string $resource = NIKResource::class;
    protected function afterSave(): void
    {
        parent::afterSave();

        Cache::forget('nik_list');

        // Jika kamu pakai cache per kk_id dan pagination, clear juga:
        // Contoh (kalau ada kk_id di input):
        $kkId = $this->record->kk_id ?? null;
        if ($kkId) {
            for ($page = 1; $page <= 10; $page++) {
                Cache::forget("nik_by_kk_{$kkId}_page_{$page}");
            }
        }
    }
}
