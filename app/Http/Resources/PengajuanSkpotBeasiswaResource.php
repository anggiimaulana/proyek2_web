<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanSkpotBeasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hubungan' => $this->hubunganPengaju->jenis_hubungan ?? null,
            'nama' => $this->nama,
            'kk_id' => $this->idKkPengaju->nomor_kk ?? null,
            'nik_id' => $this->idNikPengaju->nomor_nik ?? null,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jk' => $this->jkPengaju->jenis_kelamin ?? null,
            'agama' => $this->agamaPengaju->nama_agama ?? null,
            'nama_ortu' => $this->nama_ortu,
            'pekerjaan' => $this->pekerjaanPengaju->nama_pekerjaan ?? null,
            'penghasilan' => $this->penghasilanPengaju->rentang_penghasilan ?? null,
            'file_kk' => $this->file_kk,
            'alamat' => $this->alamat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
