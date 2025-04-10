<?php

namespace App\Http\Resources\Image;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeritaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [
            'id' => $this->id,
             'urutan' => $this->urutan,
             'url' => $this->url,
             'public_id' => $this->public_id,
             'jenjang_sekolah' => $this->jenjang_sekolah,
        ];
    }
}
