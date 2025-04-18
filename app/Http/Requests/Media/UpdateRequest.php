<?php

namespace App\Http\Requests\Media;

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
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'jenjang_sekolah' => 'sometimes|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'File harus berupa jpeg, png, jpg, gif, svg',
            'image.max' => 'File tidak boleh lebih dari 5 MB',
            'jenjang_sekolah.string' => 'Jenjang sekolah harus berupa string',
            'jenjang_sekolah.max' => 'Jenjang sekolah tidak boleh lebih dari 255 karakter'
        ];
    }
}
