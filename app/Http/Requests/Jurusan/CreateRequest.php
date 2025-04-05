<?php

namespace App\Http\Requests\Jurusan;

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
            'jurusan' => 'required|string|max:255|unique:jurusan,jurusan',
            'jenjang_sekolah' => 'nullable|string|max:50'
        ];
    }
} 