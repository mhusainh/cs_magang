<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jumlah' => 'sometimes|numeric|min:0',
            'metode_pembayaran' => 'sometimes|string|in:transfer,bank,ewallet',
            'status' => 'sometimes|string|in:pending,success,failed',
            'waktu_transaksi' => 'sometimes|date'
        ];
    }
} 