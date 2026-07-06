<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJasaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'biaya_jasa' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'biaya_jasa.required' => 'Biaya jasa wajib diisi.',
            'biaya_jasa.numeric'  => 'Biaya jasa harus berupa angka.',
            'biaya_jasa.min'      => 'Biaya jasa tidak boleh negatif.',
        ];
    }
}
