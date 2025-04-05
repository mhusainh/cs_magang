<?php

namespace App\Http\Requests\Tagihan;

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
            'nama_tagihan' => 'sometimes|string|max:255',
            'total' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:pending,paid,expired',
            'va_number' => 'sometimes|string|unique:tagihans,va_number,'.$this->route('id'),
            'transaction_qr_id' => 'sometimes|string',
            'created_time' => 'sometimes|date'
        ];
    }
} 