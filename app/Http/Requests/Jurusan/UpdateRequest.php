<?php

namespace App\Http\Requests\Jurusan;

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
            'jurusan' => 'sometimes|string|max:255|unique:jurusan,jurusan,'.$this->route('id'),
            'jenjang_sekolah' => 'nullable|string|max:50'
        ];
    }
} 