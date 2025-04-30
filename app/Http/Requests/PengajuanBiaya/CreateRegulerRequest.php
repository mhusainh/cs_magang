<?php

namespace App\Http\Requests\PengajuanBiaya;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreateRegulerRequest extends FormRequest
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
            'jenjang_sekolah' => 'required|string',  
            'nominal' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'jenjang_sekolah.required' => 'Jenjang Sekolah harus diisi',
            'jenjang_sekolah.string' => 'Jenjang Sekolah harus berupa string',
            'nominal.required' => 'Nominal harus diisi',
            'nominal.integer' => 'Nominal harus berupa integer',
        ];
    }
}
