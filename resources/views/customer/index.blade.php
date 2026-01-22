@extends('layouts.dashboardLayout')

@section('title', 'Data Pelanggan - BengkelSmart')
@section('page-title', 'DATA PELANGGAN')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/index.css') }}?v={{ time() }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-12">

            <!-- Card Container -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                <!-- Card Header (Toolbar) -->
                <div class="card-header bg-white p-4 border-bottom-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                        <a href="{{ route('customers.create') }}"
                            class="btn btn-primary rounded-pill px-4 fw-semibold d-flex align-items-center gap-2 shadow-sm">
                            <i class="bi bi-person-plus-fill fs-5"></i>
                            Tambah Pelanggan
                        </a>


                        <!-- Pencarian -->
                        <div class="input-group w-auto" style="min-width: 300px;">
                            <span class="input-group-text bg-transparent border-end-0 ps-3" id="search-icon">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-2 rounded-end-pill"
                                placeholder="Cari nama, plat nomor..." aria-label="Search">
                        </div>

                    </div>
                </div>

                <!-- Table Content -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold border-bottom-0">Pelanggan
                                </th>
                                <th class="py-3 text-uppercase text-secondary small fw-bold border-bottom-0">Kontak</th>
                                <th class="py-3 text-uppercase text-secondary small fw-bold border-bottom-0">Alamat</th>
                                <th class="py-3 text-uppercase text-secondary small fw-bold border-bottom-0">Kendaraan</th>
                                <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold border-bottom-0 text-end">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <!-- Kolom Nama & ID -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Avatar Inisial -->
                                            <div class="avatar-initial flex-shrink-0">
                                                {{ substr($customer->customer_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $customer->customer_name }}</h6>

                                            </div>
                                        </div>
                                    </td>

                                    <!-- Kolom Kontak -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-medium mb-1 phone">
                                                <i class="bi bi-telephone me-1 text-primary small"></i>
                                                {{ $customer->phone_number }}
                                            </span>
                                            <span class="text-muted small">
                                                {{ $customer->email }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Kolom Alamat -->
                                    <td>
                                        <span class="text-secondary address">{{ Str::limit($customer->address, 30) }}</span>
                                    </td>

                                    <!-- Kolom Kendaraan -->
                                    <td>
                                        <div class="d-flex flex-column align-items-start gap-1">
                                            <span class="badge-car shadow-sm">
                                                {{ $customer->vehicle }}
                                            </span>
                                            <small class="text-muted fw-bold" style="font-size: 0.75rem;">
                                                {{ $customer->license_plate }} • <span
                                                    class="text-primary">{{ $customer->year }}</span>
                                            </small>
                                        </div>
                                    </td>

                                    <!-- Kolom Aksi -->
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('customers.edit', $customer->customer_id) }}"
                                                class="btn btn-sm btn-light text-primary border rounded-3" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer->customer_id) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-light text-danger border rounded-3 delete-btn"
                                                    title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data pelanggan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="card-footer bg-white p-3 border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Menampilkan {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} dari
                            {{ $customers->total() }} data
                        </small>
                        <div>
                            {{-- Render pagination Laravel --}}
                            {{ $customers->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}"
            });
        </script>
    @endif
@endpush
