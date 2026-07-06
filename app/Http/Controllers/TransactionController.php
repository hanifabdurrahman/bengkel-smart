<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\ProcessPaymentRequest;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionRepository $repository,
        private TransactionService $service
    ) {}

    public function payment($id)
    {
        $transaction = $this->repository->findWithRelations($id);

        return view('transactions.payment', compact('transaction'));
    }

    public function processPayment(ProcessPaymentRequest $request, $id)
    {
        $transaction = $this->service->processPayment(
            (int) $id,
            $request->bayar,
            $request->diskon
        );

        return redirect()->route('transactions.invoice', $transaction->transaction_id)
            ->with('success', 'Pembayaran Berhasil!');
    }

    public function invoice($id)
    {
        $transaction = $this->repository->findWithRelations($id);

        return view('transactions.invoice', compact('transaction'));
    }
}
