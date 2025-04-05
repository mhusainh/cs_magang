<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'tagihan_id' => 'required|exists:tagihans,id',
            'jumlah' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|in:transfer,bank,ewallet',
            'status' => 'required|string|in:pending,success,failed',
            'waktu_transaksi' => 'required|date'
        ];
    }
} 