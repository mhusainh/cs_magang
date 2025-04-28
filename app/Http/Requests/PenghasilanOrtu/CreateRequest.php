<?php

namespace App\Http\Requests\PenghasilanOrtu;

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
            'penghasilan_ortu' => 'required|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'penghasilan_ortu.required' => 'Penghasilan Ortu harus diisi',
            'penghasilan_ortu.string' => 'Penghasilan Ortu harus berupa string',
            'penghasilan_ortu.max' => 'Penghasilan Ortu maksimal 255 karakter'
        ];
    }
}
