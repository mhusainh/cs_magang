<?php

namespace App\Http\Requests\Pesan;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'user_id' => 'required|integer',
            'judul' => 'required|string|max:255',
            'deskripsi' =>'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User id harus diisi',
            'user_id.integer' => 'User id harus berupa angka',
            'judul.required' => 'Judul harus diisi',
            'judul.string' => 'Judul harus berupa string',
            'judul.max' => 'Judul maksimal 255 karakter',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'deskripsi.string' => 'Deskripsi harus berupa string',
        ];
    }
}
