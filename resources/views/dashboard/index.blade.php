@extends('layouts.dashboardLayout')

@section('title', 'Dashboard - BengkelSmart')
@section('page-title', 'Dashboard Bengkel')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/index.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="modern-card d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-label mb-2">Pendapatan Harian</h6>
                    <h3 class="text-value">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</h3>
                    <small class="text-success fw-bold d-flex align-items-center mt-1" style="font-size: 0.8rem;">
                        <i class="bi bi-arrow-up-short fs-6"></i> Update Terkini
                    </small>
                </div>
                <div class="icon-box icon-box-success"><i class="bi bi-cash-stack"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="modern-card d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-label mb-2">Total Pelanggan</h6>
                    <h3 class="text-value">{{ $totalCustomers }}</h3>
                    <small class="text-muted d-flex align-items-center mt-1" style="font-size: 0.8rem;">Total Semua
                        Pelanggan</small>
                </div>
                <div class="icon-box icon-box-primary"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="modern-card d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-label mb-2">Servis Selesai</h6>
                    <h3 class="text-value">{{ $servicesToday }}</h3>
                    <small class="text-muted d-flex align-items-center mt-1" style="font-size: 0.8rem;">Hari Ini</small>
                </div>
                <div class="icon-box icon-box-warning"><i class="bi bi-wrench-adjustable-circle-fill"></i></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold m-0" style="color: var(--text-main)">Trafik Servis</h5>
                        <small id="trafficSubtext" class="text-muted">Overview minggu ini</small>
                    </div>
                    <select id="trafficFilter" class="form-select form-select-clean w-auto">
                        <option value="weekly">Mingguan</option>
                        <option value="monthly">Bulanan</option>
                    </select>
                </div>
                <div style="position: relative; height: 250px;">
                    <canvas id="weeklyServiceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="modern-card d-flex flex-column">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1" style="color: var(--text-main)">Top Sparepart</h5>
                    <small class="text-muted">Distribusi penjualan item</small>
                </div>
                <div class="flex-grow-1 position-relative d-flex justify-content-center align-items-center"
                    style="min-height: 250px;">
                    <canvas id="sparepartChart"></canvas>
                    <div class="position-absolute top-50 start-50 translate-middle text-center"
                        style="pointer-events: none;">
                        <small class="text-muted d-block text-uppercase fw-semibold"
                            style="font-size: 0.65rem; letter-spacing: 1.5px;">Total Unit</small>
                        <h2 id="sparepartTotal" class="fw-bolder m-0" style="font-size: 2rem;">0</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h5 class="fw-bold mb-1" style="color: var(--text-main)">Analytics Pendapatan</h5>
                        <div class="d-flex align-items-baseline gap-2">
                            <h3 class="fw-bold mb-0 text-primary">Rp {{ number_format($totalYearlyRevenue, 0, ',', '.') }}
                            </h3>
                            <span class="badge bg-success-subtle text-success rounded-pill px-2">
                                <i class="bi bi-arrow-up-short"></i> {{ $growthPercentage }}
                            </span>
                        </div>
                    </div>
                    <div class="btn-group shadow-sm" role="group">
                        <button type="button"
                            class="btn btn-outline-light text-dark border hover-bg-light btn-sm px-3">Harian</button>
                        <button type="button"
                            class="btn btn-outline-light text-dark border hover-bg-light btn-sm px-3">Mingguan</button>
                        <button type="button" class="btn btn-dark btn-sm px-3">Tahunan</button>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="annualIncomeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.DASHBOARD_DATA = {
            weeklyLabels: @json($weeklyLabels),
            weeklyData: @json($weeklyData),

            sparepartLabels: @json($sparepartLabels),
            sparepartData: @json($sparepartData),

            months: @json($months),
            monthlyRevenue: @json($monthlyRevenue),

            trafficUrl: "{{ route('dashboard.service-traffic') }}",
            theme: document.documentElement.getAttribute('data-bs-theme')
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/dashboard/index.js') }}?v={{ time() }}"></script>
@endpush
