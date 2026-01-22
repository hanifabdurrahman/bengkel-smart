<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $customers = Customer::where('workshop_id', $request->user()->workshop_id)
            ->when($search, function ($query) use ($search) {
                $query->where('customer_name', 'LIKE', "%$search%")
                    ->orWhere('license_plate', 'LIKE', "%$search%");
            })
            ->orderBy('customer_id', 'DESC')
            ->paginate(10);

        return view('customer.index', compact('customers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi dengan custom messages
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'address'       => 'nullable|string|max:255',
            'phone_number'  => 'required|numeric|digits_between:10,20',
            'email'         => 'nullable|email|max:255',
            'vehicle'       => 'required|string|max:100',
            'license_plate' => ['required', 'string', 'max:50', 'regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{0,3}$/i'],
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),

        ], [
            // Pesan kustom untuk field Required
            'customer_name.required' => 'Nama lengkap pelanggan harus diisi.',
            'phone_number.required'  => 'Nomor telepon (WA) wajib diisi.',
            'vehicle.required'       => 'Jenis kendaraan wajib diisi.',
            'license_plate.required' => 'Nomor polisi wajib diisi.',

            // Pesan kustom untuk validasi format
            'email.email'            => 'Format alamat email tidak valid.',
            'phone_number.numeric'   => 'Nomor telepon harus berupa angka.',
            'phone_number.digits_between'    => 'Nomor telepon harus terdiri dari 10 hingga 20 digit.',
            'license_plate.regex' => 'Format nomor polisi tidak valid (Contoh: B 1234 CD).',
            'year.integer'           => 'Tahun pembuatan harus diisi dengan angka yang valid.',
            'year.min'               => 'Tahun pembuatan tidak valid.',
            'year.max'               => 'Tahun pembuatan tidak valid.',
            'max'                    => 'Inputan terlalu panjang (maksimal :max karakter).',
        ]);

        $validated['workshop_id'] = Auth::user()->workshop_id;

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->workshop_id !== Auth::user()->workshop_id) {
            return redirect()->route('customers.index')->with('error', 'Pelanggan tidak ditemukan.');
        }

        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Validasi dengan Rules & Custom Messages yang sama dengan Store
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'address'       => 'nullable|string|max:255',
            'phone_number'  => 'required|numeric|digits_between:10,20', // Disamakan dengan store
            'email'         => 'nullable|email|max:255',
            'vehicle'       => 'required|string|max:100',
            'license_plate' => ['required', 'string', 'max:50', 'regex:/^[A-Z]{1,2}\s?\d{1,4}\s?[A-Z]{0,3}$/i'],
            'year'          => 'nullable|integer|min:1900|max:' . (date('Y') + 1), // Disamakan dengan store
        ], [
            // Pesan kustom untuk field Required
            'customer_name.required' => 'Nama lengkap pelanggan harus diisi.',
            'phone_number.required'  => 'Nomor telepon (WA) wajib diisi.',
            'vehicle.required'       => 'Jenis kendaraan wajib diisi.',
            'license_plate.required' => 'Nomor polisi wajib diisi.',

            // Pesan kustom untuk validasi format
            'email.email'                   => 'Format alamat email tidak valid.',
            'phone_number.numeric'          => 'Nomor telepon harus berupa angka.',
            'phone_number.digits_between'   => 'Nomor telepon harus terdiri dari 10 hingga 20 digit.',
            'license_plate.regex' => 'Format nomor polisi tidak valid (Contoh: B 1234 CD).',
            'year.integer'                  => 'Tahun pembuatan harus diisi dengan angka yang valid.',
            'year.min'                      => 'Tahun pembuatan tidak valid.',
            'year.max'                      => 'Tahun pembuatan tidak valid.',
            'max'                           => 'Inputan terlalu panjang (maksimal :max karakter).',
        ]);

        // 2. Cari data pelanggan
        $customer = Customer::findOrFail($id);

        // 3. Cek otorisasi (pastikan punya bengkel user yang sedang login)
        if ($customer->workshop_id !== Auth::user()->workshop_id) {
            return redirect()->route('customers.index')->with('error', 'Pelanggan tidak ditemukan.');
        }

        // 4. Update data (tanpa perlu mengisi workshop_id lagi karena tidak berubah)
        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->workshop_id !== Auth::user()->workshop_id) {
            return redirect()->route('customers.index')->with('error', 'Pelanggan tidak ditemukan.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus!');
    }

    // --- AJAX SEARCH (API untuk Form Servis) ---
    public function search(Request $request)
    {
        try {
            if (!$request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid request'
                ], 400);
            }

            $query = trim($request->get('q'));

            if (!$query) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor plat harus diisi.'
                ], 422);
            }

            $cleanQuery = strtoupper(str_replace(' ', '', $query));

            $customer = Customer::where('workshop_id', Auth::user()->workshop_id)
                ->where(function ($q) use ($query, $cleanQuery) {
                    $q->where('license_plate', 'LIKE', "%{$query}%")
                        ->orWhereRaw("REPLACE(license_plate, ' ', '') = ?", [$cleanQuery]);
                })
                ->first();

            if ($customer) {
                return response()->json([
                    'status' => 'found',
                    'data'   => $customer
                ], 200);
            }

            return response()->json([
                'status' => 'not_found',
                'message' => 'Data tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
