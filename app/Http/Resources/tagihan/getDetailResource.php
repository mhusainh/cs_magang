<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nama_tagihan' => $this->nama_tagihan,
            'total' => $this->total,
            'status' => $this->status,
            'va_number' => $this->va_number,
            'transaction_qr_id' => $this->transaction_qr_id,
            'created_time' => $this->created_time,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
