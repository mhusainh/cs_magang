<?php

namespace App\Http\Resources;

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
            'user_id' => $this->user_id,
            'tagihan_id' => $this->tagihan_id,
            'status' => $this->status,
            'total' => $this->total,
            'created_time' => $this->created_time,
            'va_number' => $this->va_number,
            'transaction_qr_id' => $this->transaction_qr_id,
            'method' => $this->method,
            'ref_no' => $this->ref_no,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            
            // Optional: Include relationships
            'user' => $this->whenLoaded('user'),
            'tagihan' => $this->whenLoaded('tagihan')
        ];
    }
}