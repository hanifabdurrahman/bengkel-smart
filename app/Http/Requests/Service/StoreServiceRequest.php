<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_plate' => 'required|string',
            'customer_name' => 'required|string',
            'phone_number'  => 'nullable|string',
            'address'       => 'nullable|string',
            'vehicle'       => 'nullable|string',
            'year'          => 'nullable|integer',
            'keluhan'       => 'required|string',
            'jenis_servis'  => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'license_plate.required' => 'Nomor plat kendaraan wajib diisi.',
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'keluhan.required'       => 'Keluhan wajib diisi.',
        ];
    }
}
