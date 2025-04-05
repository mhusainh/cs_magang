<?php

namespace App\Http\Requests\Tagihan;

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
            'nama_tagihan' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,paid,expired',
            'va_number' => 'required|string|unique:tagihans,va_number',
            'transaction_qr_id' => 'required|string',
            'created_time' => 'required|date'
        ];
    }
} 