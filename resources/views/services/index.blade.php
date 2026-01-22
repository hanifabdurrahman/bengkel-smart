@extends('layouts.dashboardLayout')
@section('title', 'Antrian Servis')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services/index.css') }}">
@endpush

@section('content')
    <div class="card-modern position-relative">

        <div class="p-4 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

            <h4 class="fw-bold text-body m-0">Antrian Servis</h4>

            <div class="modern-search-wrapper">
                <i class="bi bi-search text-muted"></i>
                <input type="text" id="searchInput" class="modern-search-input" placeholder="Cari nama / plat..."
                    autocomplete="off">
            </div>

            <a href="{{ route('services.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-lg me-1"></i> Servis Baru
            </a>
        </div>

        <div id="tableContainer" class="position-relative" style="min-height:430px">
            <div id="loadingSpinner" class="loading-overlay d-none">
                <div class="text-center">
                    <div class="spinner-border text-primary" style="width:2.5rem;height:2.5rem"></div>
                    <p class="small text-muted fw-bold mt-2">Memuat data...</p>
                </div>
            </div>

            <div id="innerTableContent" class="fade-in">
                @include('services.partials.list')
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/service/index.js') }}?v={{ time() }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            initServicePage("{{ route('services.index') }}");
        });
    </script>
@endpush
