<?php

namespace App\Http\Requests\User;

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
            'no_telp' => 'sometimes|string|max:15|unique:users,no_telp,'.$this->id.',id',
        ];
    }

    public function messages(): array
    {
        return [
            'no_telp.required' => 'Nomor telepon harus diisi',
            'no_telp.max' => 'Nomor telepon maksimal 15 karakter',
            'no_telp.unique' => 'Nomor telepon sudah di gunakan',
        ];
    }
} 