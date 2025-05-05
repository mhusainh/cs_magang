<?php

namespace App\Http\Requests\Angkatan;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class InputRequest extends FormRequest
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
            'angkatan' => 'required|unique:angkatan|min:4|max:4',
        ];
    }

    public function messages(): array
    {
        return [
            'angkatan.required' => 'angkatan harus diisi',
            'angkatan.unique' => 'angkatan sudah ada',
            'angkatan.min' => 'angkatan minimal 4 karakter',
            'angkatan.max' => 'angkatan maksimal 4 karakter',
        ];
    }
}
