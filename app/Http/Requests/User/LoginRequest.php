<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestTrait;

class LoginRequest extends FormRequest
{
    use FormRequestTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'no_telp' => 'required|string|max:15',
        ];
    }

    public function messages(): array
    {
        return [
            'no_telp.required' => 'Nomor telepon harus diisi',
            'no_telp.max' => 'Nomor telepon maksimal 15 karakter',
            'no_telp.string' => 'Nomor telepon harus berupa string'
        ];
    }
} 