<?php

namespace App\Http\Resources\Transaksi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeringkatResource extends JsonResource
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
            'progress_user'=>[
                'updated_at' => $this->updated_at,
            ],
            'peserta' => [
                'id' => $this->peserta_id,
                'nama' => $this->peserta_nama,
                'wakaf' => $this->wakaf,
            ],
        ];
    }
}
