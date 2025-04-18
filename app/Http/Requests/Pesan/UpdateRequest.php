<?php

namespace App\Http\Requests\Pesan;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    use FormRequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => 'sometimes|string|max:255',
            'deskripsi' => 'sometimes|string'
        ];
    }

    public function messages(): array
    {
        return [
            'judul.string' => 'Judul harus berupa string',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.string' => 'Deskripsi harus berupa string'
        ];
    }
}
