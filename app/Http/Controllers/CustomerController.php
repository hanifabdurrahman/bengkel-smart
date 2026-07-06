<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerRepository $repository
    ) {}

    public function index(Request $request)
    {
        $search = $request->search;
        $customers = $this->repository->search($search);

        return view('customer.index', compact('customers', 'search'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $this->repository->create($request->validated());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $customer = $this->repository->findById($id);

        return view('customer.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, string $id)
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $this->repository->delete($id);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus!');
    }

    public function show(string $id)
    {
        return redirect()->route('customers.index');
    }

    public function search(Request $request)
    {
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

        $customer = $this->repository->searchAjax($query);

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
    }
}
