<?php

namespace App\Http\Requests\PengajuanBiaya;

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
            'jurusan' => 'sometimes|string',
            'jenjang_sekolah' =>'sometimes|string',
            'nominal' => 'sometimes|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'jurusan.string' => 'Jurusan harus berupa string',
            'jenjang_sekolah.string' => 'Jenjang Sekolah harus berupa string',
            'nominal.integer' => 'Nominal harus berupa integer',
        ];
    }
}
