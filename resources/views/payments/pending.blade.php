@extends('layouts.dashboardLayout')
@section('title', 'Kasir - Menunggu Pembayaran')

@section('content')
    <div class="container-fluid">

        {{-- Header Ringkasan --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <h4 class="fw-bold text-dark mb-1">Menunggu Pembayaran</h4>
                <p class="text-muted small">Daftar kendaraan yang telah selesai diservis dan menunggu pelunasan.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="card bg-primary text-white border-0 shadow-sm rounded-4">
                    <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                        <span class="small opacity-75">Total Semua Tagihan:</span>
                        <span class="fw-bold fs-5">Rp {{ number_format($totalRevenuePending, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Tabel --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

            {{-- Toolbar Pencarian --}}
            <div class="card-header bg-white py-3 border-bottom-0">
                <form action="{{ route('payments.pending') }}" method="GET">
                    <div class="input-group shadow-sm" style="max-width: 300px;">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-pill"
                            placeholder="Cari Plat / Nama..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 w-100">
                        <thead class="bg-light border-bottom">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small fw-bold">Kode Servis</th>
                                <th class="py-3 text-secondary small fw-bold">Pelanggan</th>
                                <th class="py-3 text-secondary small fw-bold">Waktu Selesai</th>
                                <th class="py-3 text-secondary small fw-bold text-end">Total Tagihan</th>
                                <th class="pe-4 py-3 text-secondary small fw-bold text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingServices as $service)
                                <tr>
                                    {{-- Kolom Nota --}}
                                    <td class="ps-4">
                                        <span class="badge bg-light text-dark border fw-bold font-monospace">
                                            {{ $service->kode_servis }}
                                        </span>
                                    </td>

                                    {{-- Kolom Pelanggan --}}
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">{{ $service->customer->license_plate }}</span>
                                            <small class="text-muted">{{ $service->customer->customer_name }} -
                                                {{ $service->customer->vehicle }}</small>
                                        </div>
                                    </td>

                                    {{-- Kolom Waktu Selesai --}}
                                    <td>
                                        <div class="d-flex align-items-center text-success">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">Selesai</span>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($service->waktu_selesai)->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Total Tagihan (Highlight Besar) --}}
                                    <td class="text-end">
                                        <h6 class="fw-bold text-primary mb-0">
                                            Rp {{ number_format($service->transaction->total_akhir, 0, ',', '.') }}
                                        </h6>
                                        @if ($service->transaction->diskon > 0)
                                            <small class="text-danger text-decoration-line-through"
                                                style="font-size: 0.75rem">
                                                Disc: {{ number_format($service->transaction->diskon, 0, ',', '.') }}
                                            </small>
                                        @endif
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('transactions.payment', $service->transaction_id) }}"
                                            class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                            <i class="bi bi-cash-stack me-2"></i>Bayar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="bg-light rounded-circle p-3 mb-3">
                                                <i class="bi bi-receipt text-secondary fs-1"></i>
                                            </div>
                                            <h6 class="text-muted fw-bold">Semua Tagihan Lunas</h6>
                                            <small class="text-muted">Tidak ada kendaraan yang menunggu pembayaran saat
                                                ini.</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $pendingServices->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
