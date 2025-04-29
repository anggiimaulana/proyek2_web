<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanSktmListrikResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hubungan' => $this->hubunganPengaju->jenis_hubungan ?? null,
            'nama' => $this->nama,
            'nik' => $this->nik,
            'alamat' => $this->alamat,
            'pekerjaan' => $this->pekerjaanPengaju->nama_pekerjaan ?? null,
            'penghasilan' => $this->penghasilanPengaju->rentang_penghasilan ?? null,
            'nama_pln' => $this->nama_pln,
            'file_kk' => $this->file_kk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
