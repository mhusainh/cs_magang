<?php

namespace App\Http\Resources\PenghasilanOrtu;

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
            'penghasilan_ortu' => $this->penghasilan_ortu
        ];
    }
}
