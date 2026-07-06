<?php

namespace App\Http\Requests\ServicePart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qty' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'qty.required' => 'Jumlah wajib diisi.',
            'qty.integer'  => 'Jumlah harus angka bulat.',
            'qty.min'      => 'Jumlah minimal 1.',
        ];
    }
}
