<?php

namespace App\Http\Resources\BiodataOrtu;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'peserta_id' => $this->peserta_id,
            'nama_ayah' => $this->nama_ayah,
            'nama_ibu' => $this->nama_ibu,
            'no_telp' => $this->no_telp,
            'pekerjaan_ayah_id' => $this->pekerjaan_ayah_id,
            'pekerjaan_ibu_id' => $this->pekerjaan_ibu_id,
            'penghasilan_ortu_id' => $this->penghasilan_ortu_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
