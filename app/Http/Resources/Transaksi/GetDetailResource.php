<?php

namespace App\Http\Resources\Transaksi;

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
            'user' => $this->user ? [
                'id' => $this->user->id,
                'no_telp' => $this->user->no_telp,
                'peserta' => $this->user->peserta ? [
                    'id' => $this->user->peserta->id,
                    'nama' => $this->user->peserta->nama,
                ] : null,
            ] : null,   
            'tagihan' => $this->tagihan ? [
                'id' => $this->tagihan->id,
                'nama_tagihan' => $this->tagihan->nama_tagihan,
                'total' => $this->tagihan->total,
                'status' => $this->tagihan->status,
            ] : null,
            'status' => $this->status,
            'total' => $this->total,
            'created_time' => $this->created_time,
            'va_number' => $this->va_number,
            'transaction_qr_id' => $this->transaction_qr_id,
            'method' => $this->method,
            'ref_no' => $this->ref_no,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),

        ];
    }
}
