<?php

namespace App\Http\Requests\PengajuanBiaya;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class BookVeeRequest extends FormRequest
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
            'book_vee' =>'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'book_vee.required' => 'Book Vee tidak boleh kosong',
            'book_vee.numeric' => 'Book Vee harus berupa angka',
        ];
    }
}
