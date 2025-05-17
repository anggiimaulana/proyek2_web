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
            'id_admin' => $this->admin->id ?? null,
            'kategori_pengajuan' => $this->kategoriPengajuan->nama_kategori ?? null,
            'detail_id' => $this->detail->id ?? null,
            'detail_type' => class_basename($this->detail_type),
            'detail' => $this->whenLoaded('detail', function () {
                return match (get_class($this->detail)) {
                    \App\Models\PengajuanSkBelumMenikah::class => new \App\Http\Resources\PengajuanSkBelumMenikahResource($this->detail),
                    \App\Models\PengajuanSkPekerjaan::class => new \App\Http\Resources\PengajuanSkPekerjaanResource($this->detail),
                    \App\Models\PengajuanSkpotBeasiswa::class => new \App\Http\Resources\PengajuanSkpotBeasiswaResource($this->detail),
                    \App\Models\PengajuanSkStatus::class => new \App\Http\Resources\PengajuanSkStatusResource($this->detail),
                    \App\Models\PengajuanSktmBeasiswa::class => new \App\Http\Resources\PengajuanSktmBeasiswaResource($this->detail),
                    \App\Models\PengajuanSktmListrik::class => new \App\Http\Resources\PengajuanSktmListrikResource($this->detail),
                    \App\Models\PengajuanSktmSekolah::class => new \App\Http\Resources\PengajuanSktmSekolahResource($this->detail),
                    \App\Models\PengajuanSkUsaha::class => new \App\Http\Resources\PengajuanSkUsahaResource($this->detail),
                    default => null,
                };
            }),
            'status_pengajuan' => $this->statusPengajuan->status ?? null,
            'catatan' => $this->catatan,
            'id_admin_updated' => $this->adminUpdated->id ?? null,
            'id_kuwu_updated' => $this->kuwuUpdated->id ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
