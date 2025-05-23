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
            'kk_id' => $this->idKkPengaju->nomor_kk ?? null,
            'nik_id' => $this->idNikPengaju->nomor_nik ?? null,
            'alamat' => $this->alamat,
            'pekerjaan' => $this->pekerjaanPengaju->nama_pekerjaan ?? null,
            'penghasilan' => $this->penghasilanPengaju->rentang_penghasilan ?? null,
            'nama_pln' => $this->nama_pln,
            'agama' => $this->agamaPengaju->nama_agama ?? null,
            'jk' => $this->jenisKelaminPengaju->jenis_kelamin ?? null,
            'umur' => $this->umur,
            'file_kk' => $this->file_kk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
