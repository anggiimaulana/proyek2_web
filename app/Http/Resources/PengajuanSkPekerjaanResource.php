<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanSkPekerjaanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hubungan' => $this->hubungan,
            'nama' => $this->nama,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jk' => $this->jk,
            'status_perkawinan' => $this->status_perkawinan,
            'agama' => $this->agama,
            'pekerjaan_terdahulu' => $this->pekerjaan_terdahulu,
            'pekerjaan_sekarang' => $this->pekerjaan_sekarang,
            'file_kk' => $this->file_kk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
