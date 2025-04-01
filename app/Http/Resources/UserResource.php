<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'no_telp' => $this->no_telp,
            'role' => $this->role,
            'peserta' => $this->whenLoaded('peserta', function() {
                return new PesertaResource($this->peserta);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
