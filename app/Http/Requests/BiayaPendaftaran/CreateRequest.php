<?php

namespace App\Http\Requests\BiayaPendaftaran;

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
            'nominal' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'nominal.required' => 'Nominal harus diisi',
            'nominal.numeric' => 'Nominal harus berupa angka',
        ];
    }
}
