<?php

namespace App\Http\Controllers;

use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private ServiceRepository $repository
    ) {}

    public function pendingList(Request $request)
    {
        $pendingServices = $this->repository->getPendingPaymentServices($request->search);

        $totalRevenuePending = $pendingServices->getCollection()->sum(
            fn($service) => $service->transaction->total_akhir
        );

        return view('payments.pending', compact('pendingServices', 'totalRevenuePending'));
    }
}
