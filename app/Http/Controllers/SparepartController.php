<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SparepartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        // 1. Query Dasar
        $query = Sparepart::where('workshop_id', $workshop->workshop_id)
            ->latest();

        // Pencarian
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('sparepart_name', 'like', "%{$keyword}%")
                    ->orWhere('sparepart_code', 'like', "%{$keyword}%")
                    ->orWhere('rack_location', 'like', "%{$keyword}%");
            });
        }

        $lowStockExists = Sparepart::where('stock_quantity', '<=', 5)->exists();


        // Filter stok menipis (<=5)
        if ($request->filter === 'low_stock') {
            $query->where('stock_quantity', '<=', 5);
        }

        $spareparts = $query->orderBy('sparepart_name', 'asc')->paginate(10);

        // 4. Return Partial jika AJAX
        if ($request->ajax()) {
            return view('spareparts.partials.list', compact('spareparts'))->render();
        }

        return view('spareparts.index', compact('spareparts', 'lowStockExists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('spareparts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi dengan Custom Messages
        $request->validate([
            'sparepart_name' => 'required|string|max:255',
            'sparepart_code' => [
                'nullable',
                'string',
                'max:100',
                // Validasi unik berdasarkan workshop_id (agar kode sama bisa dipakai bengkel lain)
                Rule::unique('spareparts')->where(function ($query) {
                    return $query->where('workshop_id', Auth::user()->workshop_id);
                })
            ],
            'stock_quantity' => 'required|integer|min:0',
            'buying_price'   => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'rack_location'  => 'nullable|string|max:100',
            'entry_date'     => 'required|date',
        ], [
            // Pesan Error Kustom
            'sparepart_name.required' => 'Nama sparepart wajib diisi.',
            'sparepart_name.max'      => 'Nama sparepart terlalu panjang (maksimal 255 karakter).',

            'sparepart_code.unique'   => 'Kode sparepart ini sudah terdaftar di sistem stok Anda.',

            'stock_quantity.required' => 'Stok awal wajib diisi.',
            'stock_quantity.integer'  => 'Jumlah stok harus berupa angka bulat.',
            'stock_quantity.min'      => 'Stok tidak boleh kurang dari 0.',

            'buying_price.required'   => 'Harga beli (modal) wajib diisi.',
            'buying_price.numeric'    => 'Harga beli harus berupa angka.',
            'buying_price.min'        => 'Harga beli tidak boleh negatif.',

            'selling_price.required'  => 'Harga jual wajib diisi.',
            'selling_price.numeric'   => 'Harga jual harus berupa angka.',
            'selling_price.min'       => 'Harga jual tidak boleh negatif.',

            'entry_date.required'     => 'Tanggal masuk barang wajib dipilih.',
            'entry_date.date'         => 'Format tanggal tidak valid.',
        ]);

        // 2. Simpan Data
        Sparepart::create([
            'workshop_id'    => Auth::user()->workshop_id,
            'sparepart_code' => $request->sparepart_code,
            'sparepart_name' => $request->sparepart_name,
            'stock_quantity' => $request->stock_quantity,
            'buying_price'   => $request->buying_price,
            'selling_price'  => $request->selling_price,
            'rack_location'  => $request->rack_location,
            'entry_date'     => $request->entry_date,
        ]);

        return redirect()->route('spareparts.index')->with('success', 'Sparepart berhasil ditambahkan!');
    }

    // Method khusus untuk Restock / Tambah Stok
    public function addStock(Request $request, $id)
    {
        $sparepart = Sparepart::where('workshop_id', Auth::user()->workshop_id)->findOrFail($id);

        $request->validate([
            'qty_masuk'       => 'required|integer|min:1',
            'harga_beli_baru' => 'required|numeric|min:0', 
        ]);

        $stokLama = $sparepart->stock_quantity;
        $hppLama  = $sparepart->buying_price;

        $qtyMasuk      = $request->qty_masuk;
        $hargaBeliBaru = $request->harga_beli_baru;

        // --- RUMUS AVERAGE COST ---
        // Total Nilai Aset Lama = 10 * 90.000 = 900.000
        // Total Nilai Aset Baru = 5 * 100.000 = 500.000
        // HPP Baru = (900.000 + 500.000) / (10 + 5) = 93.333

        if ($stokLama <= 0) {
            // Jika stok sebelumnya 0 atau minus, harga baru langsung jadi patokan
            $hppBaru = $hargaBeliBaru;
        } else {
            $totalNilai = ($stokLama * $hppLama) + ($qtyMasuk * $hargaBeliBaru);
            $totalQty   = $stokLama + $qtyMasuk;
            $hppBaru    = $totalNilai / $totalQty;
        }

        // Update Database
        $sparepart->update([
            'stock_quantity' => $stokLama + $qtyMasuk,
            'buying_price'   => $hppBaru, // Harga modal terupdate otomatis
            'entry_date'     => now(),    // Update tanggal masuk terakhir
        ]);

        return back()->with('success', "Stok bertambah! Harga modal disesuaikan menjadi Rp " . number_format($hppBaru, 0, ',', '.'));
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sparepart = Sparepart::findOrFail($id);
        return view('spareparts.edit', compact('sparepart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sparepart = Sparepart::findOrFail($id);

        $request->validate([
            'sparepart_name' => 'required|string|max:255',
            'sparepart_code' => "nullable|string|max:100|unique:spareparts,sparepart_code,{$id},sparepart_id",
            'stock_quantity' => 'required|integer|min:0',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'rack_location' => 'nullable|string|max:100',
            'entry_date' => 'required|date',
        ]);

        $sparepart->update($request->all());

        return redirect()->route('spareparts.index')->with('success', 'Data sparepart berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->delete();

        return redirect()->route('spareparts.index')->with('success', 'Data sparepart berhasil dihapus!');
    }
}
