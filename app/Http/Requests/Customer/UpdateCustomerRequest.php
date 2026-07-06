<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'address'       => 'nullable|string|max:255',
            'phone_number'  => 'required|numeric|digits_between:10,20',
            'email'         => 'nullable|email|max:255',
            'vehicle'       => 'required|string|max:100',
            'license_plate' => ['required', 'string', 'max:50', 'regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{0,3}$/i'],
            'year'          => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama lengkap pelanggan harus diisi.',
            'phone_number.required'  => 'Nomor telepon (WA) wajib diisi.',
            'vehicle.required'       => 'Jenis kendaraan wajib diisi.',
            'license_plate.required' => 'Nomor polisi wajib diisi.',
            'email.email'            => 'Format alamat email tidak valid.',
            'phone_number.numeric'   => 'Nomor telepon harus berupa angka.',
            'phone_number.digits_between' => 'Nomor telepon harus terdiri dari 10 hingga 20 digit.',
            'license_plate.regex'    => 'Format nomor polisi tidak valid (Contoh: B 1234 CD).',
            'year.integer'           => 'Tahun pembuatan harus diisi dengan angka yang valid.',
            'year.min'               => 'Tahun pembuatan tidak valid.',
            'year.max'               => 'Tahun pembuatan tidak valid.',
            'max'                    => 'Inputan terlalu panjang (maksimal :max karakter).',
        ];
    }
}
