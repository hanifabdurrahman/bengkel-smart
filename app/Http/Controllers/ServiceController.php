<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateJasaRequest;
use App\Http\Requests\Service\UpdateStatusRequest;
use App\Models\Sparepart;
use App\Repositories\ServiceRepository;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(
        private ServiceRepository $repository,
        private ServiceService $service
    ) {}

    public function index(Request $request)
    {
        $services = $this->repository->getActiveServices($request->search);

        if ($request->ajax()) {
            return view('services.partials.list', compact('services'))->render();
        }

        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(StoreServiceRequest $request)
    {
        $service = $this->service->createService($request->validated());

        return redirect()->route('services.show', $service->service_id)
            ->with('success', 'Tiket Servis Berhasil Dibuat!');
    }

    public function show(string $id)
    {
        $service = $this->repository->findWithRelations($id);
        $spareparts = Sparepart::where('workshop_id', auth()->user()->workshop_id)->get();

        return view('services.show', compact('service', 'spareparts'));
    }

    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        $this->service->updateStatus((int) $id, $request->status);

        return back()->with('success', 'Status servis diperbarui.');
    }

    public function updateJasa(UpdateJasaRequest $request, $id)
    {
        $this->service->updateJasa((int) $id, $request->biaya_jasa);

        return back()->with('success', 'Biaya jasa berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->service->cancelService((int) $id);

        return redirect()->route('services.index')
            ->with('success', 'Antrian servis berhasil dibatalkan dan dihapus.');
    }
}
