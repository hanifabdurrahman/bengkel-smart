<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServicePart\StoreServicePartRequest;
use App\Http\Requests\ServicePart\UpdateQtyRequest;
use App\Models\Sparepart;
use App\Services\ServicePartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicePartController extends Controller
{
    public function __construct(
        private ServicePartService $service
    ) {}

    public function store(StoreServicePartRequest $request)
    {
        $this->service->addPart(
            $request->transaction_id,
            $request->sparepart_id,
            $request->qty
        );

        return back()->with('success', 'Sparepart berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $this->service->removePart((int) $id);

        return back()->with('success', 'Item dihapus.');
    }

    public function updateQty(UpdateQtyRequest $request, $id)
    {
        $this->service->updateQty((int) $id, $request->qty);

        return back()->with('success', 'Qty berhasil diupdate.');
    }

    public function searchAjax(Request $request)
    {
        $query = $request->get('q');
        $workshopId = Auth::user()->workshop_id;

        $spareparts = Sparepart::where('workshop_id', $workshopId)
            ->where(function ($q) use ($query) {
                $q->where('sparepart_name', 'LIKE', "%{$query}%")
                    ->orWhere('sparepart_code', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get(['sparepart_id', 'sparepart_code', 'sparepart_name', 'stock_quantity', 'selling_price']);

        return response()->json($spareparts);
    }
}
