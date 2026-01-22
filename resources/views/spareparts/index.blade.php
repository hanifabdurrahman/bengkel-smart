@extends('layouts.dashboardLayout')

@section('title', 'Data Sparepart - BengkelSmart')
@section('page-title', 'DATA SPAREPART')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sparepart/style.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">

            {{-- Flash Message Success --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Flash Message Error --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-3"
                    role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card-modern position-relative">
                <div class="p-4 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-3"
                    style="border-color: rgba(0,0,0,0.05) !important;">

                    <div class="d-flex align-items-center gap-2 w-100 w-md-auto">
                        <a href="{{ route('spareparts.create') }}"
                            class="btn btn-primary rounded-pill px-4 fw-medium d-flex align-items-center gap-2 shadow-sm">
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Barang</span>
                        </a>

                        @if ($lowStockExists)
                            <a href="{{ route('spareparts.index', ['filter' => 'low_stock']) }}"
                                class="btn btn-danger bg-opacity-10 text-danger border-0 rounded-pill px-3 d-flex align-items-center gap-2 hover-bg-danger"
                                title="Filter Stok Menipis">
                                <i class="bi bi-exclamation-octagon-fill"></i>
                                <span class="d-none d-sm-inline fw-semibold">Low Stock</span>
                            </a>
                        @endif
                    </div>

                    <div class="modern-search-wrapper">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" id="searchInput" class="modern-search-input"
                            placeholder="Cari nama, kode part..." autocomplete="off">
                    </div>
                </div>

                <div id="tableContainer" class="position-relative" style="min-height: 400px;"
                    data-route="{{ route('spareparts.index') }}">
                    <div id="loadingSpinner" class="loading-overlay d-none">
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-2" role="status"
                                style="width: 2.5rem; height: 2.5rem;"></div>
                            <p class="small text-muted fw-bold m-0">Memuat Data...</p>
                        </div>
                    </div>

                    <div id="innerTableContent" class="fade-in">
                        {{-- INCLUDE PARTIAL DISINI --}}
                        @include('spareparts.partials.list')
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/sparepart/script.js') }}?v={{ time() }}"></script>
@endpush
