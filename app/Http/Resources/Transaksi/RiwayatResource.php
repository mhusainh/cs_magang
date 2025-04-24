<?php

namespace App\Http\Resources\Transaksi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiwayatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ref_no' => $this->ref_no,
            'total' => $this->total,
            'method' => $this->method,
            'created_at' => $this->created_at,
        ];
    }
}
