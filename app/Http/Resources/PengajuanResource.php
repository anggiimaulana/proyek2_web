<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_user_pengajuan' => $this->userPengajuan->id ?? null,
            'kategori_pengajuan' => $this->kategoriPengajuan->nama_kategori ?? null,
            'detail_id' => $this->detail->id ?? null,
            'url_file' => $this->url_file ?? null,
            'detail_type' => class_basename($this->detail_type),
            'status_pengajuan' => $this->statusPengajuan->status ?? null,
            'catatan' => $this->catatan,
        ];
    }
}
