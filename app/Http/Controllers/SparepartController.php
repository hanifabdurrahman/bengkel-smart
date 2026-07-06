<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sparepart\AddStockRequest;
use App\Http\Requests\Sparepart\StoreSparepartRequest;
use App\Http\Requests\Sparepart\UpdateSparepartRequest;
use App\Repositories\SparepartRepository;
use App\Services\SparepartService;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    public function __construct(
        private SparepartRepository $repository,
        private SparepartService $service
    ) {}

    public function index(Request $request)
    {
        $spareparts = $this->repository->search($request->search, $request->filter);
        $lowStockExists = $this->repository->lowStockExists();

        if ($request->ajax()) {
            return view('spareparts.partials.list', compact('spareparts'))->render();
        }

        return view('spareparts.index', compact('spareparts', 'lowStockExists'));
    }

    public function create()
    {
        return view('spareparts.create');
    }

    public function store(StoreSparepartRequest $request)
    {
        $this->repository->create($request->validated());

        return redirect()->route('spareparts.index')->with('success', 'Sparepart berhasil ditambahkan!');
    }

    public function addStock(AddStockRequest $request, $id)
    {
        $result = $this->service->addStock((int) $id, $request->qty_masuk, $request->harga_beli_baru);

        return back()->with('success', $result['message']);
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        $sparepart = $this->repository->findById($id);

        return view('spareparts.edit', compact('sparepart'));
    }

    public function update(UpdateSparepartRequest $request, string $id)
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('spareparts.index')->with('success', 'Data sparepart berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $this->repository->delete($id);

        return redirect()->route('spareparts.index')->with('success', 'Data sparepart berhasil dihapus!');
    }
}
