@extends('layouts.dashboardLayout')

@section('title', 'Laporan Keuangan - BengkelSmart')
@section('page-title', 'LAPORAN KEUANGAN')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/report/style.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card border-0 shadow-sm rounded-4 mb-4 no-print filter-section">
                <div class="card-body p-4">
                    <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4 d-flex gap-2 filter-buttons">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="bi bi-filter me-2"></i> Tampilkan
                            </button>

                            {{-- TOMBOL EXCEL --}}
                            @if ($isPremium)
                                <a href="{{ route('reports.export', request()->query()) }}"
                                    class="btn btn-success w-100 text-white shadow-sm">
                                    <i class="bi bi-file-earmark-excel me-2"></i> <span
                                        class="d-none d-lg-inline">Excel</span>
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary w-100 shadow-sm"
                                    onclick="alert('Upgrade ke Premium untuk download laporan Excel!')">
                                    <i class="bi bi-lock-fill me-2"></i> Excel
                                </button>
                            @endif

                            <button type="button" onclick="window.print()"
                                class="btn btn-outline-secondary w-100 shadow-sm">
                                <i class="bi bi-printer me-2"></i> Cetak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div
                        class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100 position-relative overflow-hidden">
                        <div class="card-body p-4">
                            <p class="mb-1 opacity-75 fw-bold text-uppercase small">Total Omset</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            <small class="opacity-75">{{ $totalTransactions }} Transaksi Selesai</small>
                        </div>
                        <i class="bi bi-wallet2 position-absolute"
                            style="font-size: 6rem; right: -20px; bottom: -20px; opacity: 0.1;"></i>
                    </div>
                </div>

                <div class="col-md-3 position-relative">
                    @if (!$isPremium)
                        <div class="premium-lock-overlay border border-secondary border-opacity-10">
                            <i class="bi bi-lock-fill fs-2 text-secondary mb-2"></i>
                            <span class="badge bg-warning text-dark fw-bold shadow-sm">Fitur Premium</span>
                        </div>
                    @endif
                    <div
                        class="card border-0 shadow-sm rounded-4 bg-danger text-white h-100 position-relative overflow-hidden {{ !$isPremium ? 'premium-blur' : '' }}">
                        <div class="card-body p-4">
                            <p class="mb-1 opacity-75 fw-bold text-uppercase small">Modal Barang (HPP)</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($totalModal, 0, ',', '.') }}</h3>
                            <small class="opacity-75">Estimasi modal sparepart</small>
                        </div>
                        <i class="bi bi-box-seam position-absolute"
                            style="font-size: 6rem; right: -20px; bottom: -20px; opacity: 0.1;"></i>
                    </div>
                </div>

                <div class="col-md-3 position-relative">
                    @if (!$isPremium)
                        <div class="premium-lock-overlay border border-secondary border-opacity-10">
                            <i class="bi bi-lock-fill fs-2 text-secondary mb-2"></i>
                            <a href="{{ route('plans.page') }}"
                                class="btn btn-sm btn-dark rounded-pill px-3 mt-1 shadow-sm">Upgrade Plan</a>
                        </div>
                    @endif
                    <div
                        class="card border-0 shadow-sm rounded-4 bg-success text-white h-100 position-relative overflow-hidden {{ !$isPremium ? 'premium-blur' : '' }}">
                        <div class="card-body p-4">
                            <p class="mb-1 opacity-75 fw-bold text-uppercase small">Laba Bersih</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                            <small class="opacity-75">Omset - Modal</small>
                        </div>
                        <i class="bi bi-graph-up-arrow position-absolute"
                            style="font-size: 6rem; right: -20px; bottom: -20px; opacity: 0.1;"></i>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-body p-4">
                            <p class="mb-1 text-muted fw-bold text-uppercase small">Rata-rata / Nota</p>
                            <h3 class="fw-bold text-dark">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</h3>
                            <small class="text-success fw-bold"><i class="bi bi-check-circle me-1"></i> Sehat</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Analisa Arus Kas</h5>
                    @if ($isPremium)
                        <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                            <i class="bi bi-star-fill me-1"></i> Pro Analytics Active
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border px-3 py-2 rounded-pill">
                            Basic View (Omset Only)
                        </span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <div style="height: 350px;">
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold text-dark mb-0">Rincian Transaksi</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Tanggal</th>
                                <th class="py-3">Info Servis</th>
                                <th class="py-3 text-end">Total Omset</th>
                                <th class="py-3 text-center">Detail</th> {{-- Kolom Baru --}}
                                @if ($isPremium)
                                    <th class="py-3 text-end text-danger">Total Modal</th>
                                    <th class="py-3 text-end text-success">Laba</th>
                                    <th class="py-3 text-center pe-4">Detail HPP</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr>
                                    <td class="ps-4 text-secondary">
                                        {{ $trx->created_at->format('d/m/Y') }} <br>
                                        <small>{{ $trx->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $trx->customer->customer_name ?? 'Guest' }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $trx->services->kode_servis ?? '-' }}</small>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Rp {{ number_format($trx->total_akhir, 0, ',', '.') }}
                                    </td>

                                    {{-- TOMBOL DETAIL TRANSAKSI (SEMUA USER) --}}
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#transactionModal{{ $trx->transaction_id }}">
                                            <i class="bi bi-file-text me-1"></i> Detail
                                        </button>
                                    </td>

                                    @if ($isPremium)
                                        <td class="text-end text-danger fw-medium">
                                            Rp {{ number_format($trx->modal_transaksi, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            Rp {{ number_format($trx->profit_transaksi, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center pe-4">
                                            <button type="button"
                                                class="btn btn-sm btn-light border text-secondary rounded-pill px-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $trx->transaction_id }}">
                                                <i class="bi bi-eye-fill me-1"></i> Rincian HPP
                                            </button>
                                        </td>
                                    @endif
                                </tr>

                                {{-- MODAL 1: DETAIL TRANSAKSI (INVOICE) --}}
                                <div class="modal fade" id="transactionModal{{ $trx->transaction_id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                            <div class="modal-header bg-primary text-white border-0">
                                                <h6 class="modal-title fw-bold">
                                                    <i class="bi bi-receipt me-2"></i> Rincian Transaksi
                                                </h6>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-0">
                                                <div class="bg-light p-4 border-bottom">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted small text-uppercase">Kode Servis</span>
                                                        <span
                                                            class="fw-bold text-dark">{{ $trx->services->kode_servis ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted small text-uppercase">Pelanggan</span>
                                                        <span
                                                            class="fw-bold text-dark">{{ $trx->customer->customer_name ?? 'Guest' }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-muted small text-uppercase">Kendaraan</span>
                                                        <span
                                                            class="fw-bold text-dark">{{ $trx->customer->vehicle ?? '-' }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted small text-uppercase">Waktu</span>
                                                        <span
                                                            class="fw-bold text-dark">{{ $trx->created_at->format('d/m/Y H:i') }}
                                                            WIB</span>
                                                    </div>
                                                </div>
                                                <div class="p-0" style="max-height: 300px; overflow-y: auto;">
                                                    @foreach ($trx->salesDetails as $detail)
                                                        <div
                                                            class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="mb-0 fw-bold text-dark"
                                                                    style="font-size: 0.9rem;">
                                                                    {{ $detail->sparepart->sparepart_name ?? 'Item Terhapus' }}
                                                                </h6>
                                                                <small class="text-muted">
                                                                    {{ $detail->jumlah }} x Rp
                                                                    {{ number_format($detail->harga_satuan, 0) }}
                                                                </small>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="fw-bold text-dark">Rp
                                                                    {{ number_format($detail->sub_total, 0) }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    @if ($trx->services && $trx->services->biaya_jasa > 0)
                                                        <div
                                                            class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                                                            <div>
                                                                <h6 class="mb-0 fw-bold text-dark"
                                                                    style="font-size: 0.9rem;">Biaya Jasa</h6>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="fw-bold text-dark">Rp
                                                                    {{ number_format($trx->services->biaya_jasa, 0) }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="p-3 bg-primary text-white">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-bold small text-uppercase">Total Tagihan</span>
                                                        <span class="fs-5 fw-bold">Rp
                                                            {{ number_format($trx->total_akhir, 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- MODAL 2: DETAIL HPP (PREMIUM) --}}
                                @if ($isPremium)
                                    <div class="modal fade" id="detailModal{{ $trx->transaction_id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                                <div class="modal-header bg-danger text-white border-0">
                                                    <h6 class="modal-title fw-bold">
                                                        <i class="bi bi-wallet2 me-2"></i> Laporan Modal (HPP)
                                                    </h6>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <div class="p-4 bg-white border-bottom">
                                                        <h6 class="text-uppercase fw-bold text-dark small mb-3"
                                                            style="letter-spacing: 1px;">
                                                            <i class="bi bi-info-circle me-1"></i> Informasi Transaksi
                                                        </h6>
                                                        <div class="row g-3">
                                                            <div class="col-6">
                                                                <small class="text-muted d-block text-uppercase"
                                                                    style="font-size: 0.7rem;">Kode Servis</small>
                                                                <span
                                                                    class="fw-bold text-dark">{{ $trx->services->kode_servis ?? '-' }}</span>
                                                            </div>
                                                            <div class="col-6 text-end">
                                                                <small class="text-muted d-block text-uppercase"
                                                                    style="font-size: 0.7rem;">Pelanggan</small>
                                                                <span
                                                                    class="fw-bold text-dark">{{ $trx->customer->customer_name ?? 'Guest' }}</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted d-block text-uppercase"
                                                                    style="font-size: 0.7rem;">Kendaraan</small>
                                                                <span
                                                                    class="fw-bold text-dark">{{ $trx->customer->vehicle ?? '-' }}</span>
                                                            </div>
                                                            <div class="col-6 text-end">
                                                                <small class="text-muted d-block text-uppercase"
                                                                    style="font-size: 0.7rem;">Waktu</small>
                                                                <span
                                                                    class="fw-bold text-dark">{{ $trx->created_at->format('d/m/Y H:i') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="p-4 bg-light">
                                                        <h6 class="text-uppercase fw-bold text-dark small mb-3"
                                                            style="letter-spacing: 1px;">
                                                            <i class="bi bi-box-seam me-1"></i> Rincian Pengeluaran Modal
                                                        </h6>
                                                        <div class="d-flex flex-column gap-2"
                                                            style="max-height: 300px; overflow-y: auto;">
                                                            @foreach ($trx->salesDetails as $detail)
                                                                @php
                                                                    $modalSatuan =
                                                                        $detail->current_buying_price > 0
                                                                            ? $detail->current_buying_price
                                                                            : $detail->sparepart->buying_price ?? 0;
                                                                    $subtotalModal = $modalSatuan * $detail->jumlah;
                                                                @endphp
                                                                <div class="card border-0 shadow-sm">
                                                                    <div
                                                                        class="card-body p-3 d-flex justify-content-between align-items-center">
                                                                        <div class="d-flex align-items-center gap-3">
                                                                            <div class="bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center"
                                                                                style="width: 36px; height: 36px;">
                                                                                <i class="bi bi-box"></i>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 fw-bold text-dark"
                                                                                    style="font-size: 0.9rem;">
                                                                                    {{ $detail->sparepart->sparepart_name ?? 'Item Terhapus' }}
                                                                                </h6>
                                                                                <small class="text-muted"
                                                                                    style="font-size: 0.75rem;">
                                                                                    {{ $detail->jumlah }} x Rp
                                                                                    {{ number_format($modalSatuan, 0) }}
                                                                                    (Modal)
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-end">
                                                                            <span class="fw-bold text-danger small">Rp
                                                                                {{ number_format($subtotalModal, 0) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @if ($trx->services && $trx->services->biaya_jasa_modal > 0)
                                                                <div
                                                                    class="card border-0 shadow-sm bg-secondary bg-opacity-10">
                                                                    <div
                                                                        class="card-body p-3 d-flex justify-content-between align-items-center">
                                                                        <div class="d-flex align-items-center gap-3">
                                                                            <div class="bg-white text-secondary rounded-3 d-flex align-items-center justify-content-center"
                                                                                style="width: 36px; height: 36px;">
                                                                                <i class="bi bi-wrench-adjustable"></i>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 fw-bold text-secondary"
                                                                                    style="font-size: 0.9rem;">
                                                                                    Jasa Mekanik</h6>
                                                                                <small class="text-muted"
                                                                                    style="font-size: 0.75rem;">Biaya Jasa
                                                                                    (Non-Fisik)</small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-end">
                                                                            <span class="fw-bold text-secondary small">
                                                                                Rp
                                                                                {{ number_format($trx->services->biaya_jasa_modal, 0) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="p-3 bg-danger text-white">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="fw-bold small text-uppercase">Total Modal</span>
                                                            <span class="fs-5 fw-bold">Rp
                                                                {{ number_format($trx->modal_transaksi, 0, ',', '.') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @empty
                                <tr>
                                    <td colspan="{{ $isPremium ? 7 : 4 }}" class="text-center py-5 text-muted">
                                        Tidak ada data transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($transactions->isNotEmpty())
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="2" class="text-end fw-bold py-3">TOTAL PERIODE</td>
                                    <td class="text-end fw-bold text-dark">Rp
                                        {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                                    <td></td>
                                    @if ($isPremium)
                                        <td class="text-end fw-bold text-danger">Rp
                                            {{ number_format($totalModal, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold text-success pe-4" colspan="2">Rp
                                            {{ number_format($netProfit, 0, ',', '.') }}</td>
                                    @endif
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset('js/finance/chart.js') }}?v={{ time() }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                initFinanceChart({
                    labels: @json($chartLabels),
                    revenueValues: @json($revenueValues),
                    profitValues: @json($profitValues),
                    isPremium: @json($isPremium)
                });
            });
        </script>
    @endpush
@endpush
