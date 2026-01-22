<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Query Dasar (Eager Load & Security)
        $query = Service::with('customer')
            ->where('workshop_id', Auth::user()->workshop_id)
            ->whereIn('status', ['antri', 'proses']);

        // 2. Logika Pencarian
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('license_plate', 'LIKE', "%{$search}%");
            });
        }

        // 3. Sorting & Pagination
        $services = $query->orderBy('created_at', 'asc')->paginate(10);

        // 4. Handle Request AJAX
        if ($request->ajax()) {
            return view('services.partials.list', compact('services'))->render();
        }

        // 5. Handle Request Biasa
        return view('services.index', compact('services'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'license_plate' => 'required|string',
            'customer_name' => 'required|string',
            'keluhan'       => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // A. Cek atau Buat Customer Baru
            $customer = Customer::firstOrCreate(
                [
                    'license_plate' => strtoupper($request->license_plate),
                    'workshop_id'   => Auth::user()->workshop_id
                ],
                [
                    'customer_name' => $request->customer_name,
                    'phone_number'  => $request->phone_number,
                    'address'       => $request->address,
                    'vehicle'       => $request->vehicle,
                    'year'          => $request->year,
                ]
            );

            // B. Buat Transaksi (Keranjang Kosong)
            $transaction = Transaction::create([
                'workshop_id'       => Auth::user()->workshop_id,
                'customer_id'       => $customer->customer_id,
                'tanggal'           => now(),
                'jenis_transaksi'   => 'servis',
                'status_pembayaran' => 'pending',
                'total_akhir'       => 0
            ]);

            // C. Buat Data Service
            $service = Service::create([
                'workshop_id'    => Auth::user()->workshop_id,
                'customer_id'    => $customer->customer_id,
                'transaction_id' => $transaction->transaction_id,
                'kode_servis'    => 'SRV-' . date('YmdHis') . '-' . rand(100, 999),
                'tanggal_masuk'  => now(),
                'keluhan'        => $request->keluhan,
                'jenis_servis'   => $request->jenis_servis,
                'status'         => 'antri',
                'biaya_jasa'     => 0
            ]);

            DB::commit();

            // Redirect ke halaman pengerjaan (Workbench)
            return redirect()->route('services.show', $service->service_id)
                ->with('success', 'Tiket Servis Berhasil Dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat servis: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = Service::with(['customer', 'transaction.salesDetails.sparepart'])
            ->where('workshop_id', Auth::user()->workshop_id)
            ->findOrFail($id);

        // Ambil semua sparepart untuk dropdown tambah part
        $spareparts = Sparepart::where('workshop_id', Auth::user()->workshop_id)->get();

        return view('services.show', compact('service', 'spareparts'));
    }


    /**
     * Update the specified resource in storage.
     */
    // 5. UPDATE STATUS (MULAI / SELESAI / BATAL)
    public function updateStatus(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->status = $request->status;

        if ($request->status == 'selesai') {
            $service->waktu_selesai = now();
        }

        $service->save();

        return back()->with('success', 'Status servis diperbarui.');
    }

    // 6. UPDATE BIAYA JASA
    public function updateJasa(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        // Update biaya jasa di service
        $service->biaya_jasa = $request->biaya_jasa;
        $service->save();

        // Hitung ulang harga transaksi setelah update jasa
        $transaction = Transaction::find($service->transaction_id);
        $totalSparepart = $transaction->salesDetails()->sum('sub_total');

        $transaction->total_jasa = $service->biaya_jasa;
        $transaction->total_akhir = ($totalSparepart + $service->biaya_jasa) - ($transaction->diskon ?? 0);
        $transaction->save();

        return back()->with('success', 'Biaya jasa berhasil diperbarui.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            // 1. Cari Data Servis berdasarkan ID dan Workshop User (Keamanan)
            $service = Service::where('workshop_id', Auth::user()->workshop_id)
                ->findOrFail($id);

            // 2. Ambil Transaksi terkait beserta detail sparepart yang sudah dipasang
            $transaction = Transaction::with('salesDetails')->find($service->transaction_id);

            if ($transaction) {
                // 3. LOGIKA PENGEMBALIAN STOK (RESTOCK)
                // Loop setiap sparepart yang sudah masuk ke transaksi ini
                foreach ($transaction->salesDetails as $detail) {
                    $sparepart = Sparepart::where('workshop_id', Auth::user()->workshop_id)
                        ->find($detail->sparepart_id);

                    if ($sparepart) {
                        // Kembalikan stok
                        $sparepart->increment('stock', $detail->quantity);
                    }
                }

                // 4. Hapus Detail Transaksi (Item penjualan)
                $transaction->salesDetails()->delete();

                // 5. Hapus Transaksi Utama
                $transaction->delete();
            }

            // 6. Hapus Data Servis
            $service->delete();

            DB::commit();

            return redirect()->route('services.index')
                ->with('success', 'Antrian servis berhasil dibatalkan dan dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan servis: ' . $e->getMessage());
        }
    }
}