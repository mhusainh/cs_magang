<?php

namespace App\Http\Requests\Qris;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CheckStatusRequest extends FormRequest
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
            'qr_data' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'qr_data.required' => 'QR data harus diisi',
            'qr_data.string' => 'QR data harus berupa string'
        ];
    }
}
