<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'diskon' => 'nullable|numeric|min:0',
            'bayar'  => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'diskon.numeric' => 'Diskon harus berupa angka.',
            'diskon.min'     => 'Diskon tidak boleh negatif.',
            'bayar.required' => 'Jumlah uang yang dibayarkan wajib diisi.',
            'bayar.numeric'  => 'Jumlah uang harus berupa angka.',
            'bayar.min'      => 'Jumlah uang tidak boleh negatif.',
        ];
    }
}
