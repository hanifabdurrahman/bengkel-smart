<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- 1. PENTING: CSRF Token untuk AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard - BengkelSmart')</title>

    {{-- 2. Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- 3. Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- 4. Google Fonts (Inter for Modern UI) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- 5. Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard/layout.css') }}?v={{ time() }}">

    {{-- 6. Additional Styles --}}
    @stack('styles')
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="logo-wrapper">
            <a href="{{ route('home') }}"
                class="text-decoration-none d-flex align-items-center justify-content-start gap-2 px-2">
                <div class="bg-primary bg-gradient text-white rounded-3 d-flex align-items-center justify-content-center"
                    style="width: 36px; height: 36px;">
                    <i class="bi bi-wrench-adjustable"></i>
                </div>
                <div class="d-flex flex-column text-start">
                    <span class="fw-bold fs-5 text-body" style="letter-spacing: -0.5px;">Bengkel<span
                            class="text-primary">Smart</span></span>
                    <span class="text-muted" style="font-size: 0.7rem;">Workshop Manager</span>
                </div>
            </a>
        </div>

        <hr class="mx-4 my-2 opacity-10">

        <nav class="nav flex-column gap-1 mt-2 px-2 flex-grow-1 overflow-y-auto overflow-x-hidden">
            <small class="text-uppercase text-muted fw-bold px-3 mb-2 mt-2"
                style="font-size: 0.7rem; letter-spacing: 0.05em;">Menu Utama</small>

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
            </a>

            <a href="{{ route('customers.index') }}"
                class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span>Data Pelanggan</span>
            </a>

            {{-- UPDATE: Link Menu Services --}}
            <a href="{{ route('services.index') }}"
                class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                <i class="bi bi-tools"></i> <span>Antrian Servis</span>
            </a>


            <a href="{{ route('payments.pending') }}"
                class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> <span>Pembayaran</span>
            </a>

            <a href="{{ route('spareparts.index') }}"
                class="nav-link {{ request()->routeIs('spareparts.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> <span>Stok Sparepart</span>
            </a>

            <small class="text-uppercase text-muted fw-bold px-3 mb-2 mt-4"
                style="font-size: 0.7rem; letter-spacing: 0.05em;">Lainnya</small>

            <a href="{{ route('reports.index') }}"
                class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> <span>Laporan Keuangan</span>
            </a>
            <a href="{{ route('settings.profile') }}"
                class="nav-link {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> <span>Pengaturan</span>
            </a>
        </nav>

        <div class="p-3 rounded-3 bg-body-tertiary d-flex align-items-center gap-3">
            {{-- 1. FOTO PROFIL --}}
            <img src="{{ Auth::user()->logo
                ? asset('storage/' . Auth::user()->logo)
                : 'https://ui-avatars.com/api/?name=' .
                    urlencode(Auth::user()->workshop_name ?? 'Admin') .
                    '&background=4f46e5&color=fff&size=64' }}"
                class="rounded-circle shadow-sm" style="object-fit: cover;" width="36" height="36"
                alt="Profile">

            {{-- 2. BAGIAN TEKS --}}
            <div class="overflow-hidden flex-grow-1 lh-1">
                {{-- Nama Bengkel --}}
                <p class="mb-1 fw-semibold text-truncate small text-body">
                    {{ Str::limit(Auth::user()->workshop_name ?? 'Workshop', 15) }}
                </p>

                {{-- Nama Paket (Plan) --}}
                <small class="text-muted" style="font-size: 0.65rem;">
                    {{--
                PENJELASAN LOGIKA:
                1. Auth::user() -> Mengambil model Workshop.
                2. ->activeSubscription -> Mengambil relasi activeSubscription yang ada di model Anda.
                3. ?->plan -> Mengambil relasi 'plan' dari dalam Subscription (Safe operator).
                4. ?->plan_name -> Mengambil nama plan.
                5. ?? 'Free Plan' -> Jika null (tidak ada langganan aktif), tampilkan 'Free Plan'.
            --}}
                    {{ Auth::user()->activeSubscription?->plan?->plan_name ?? 'Free Plan' }}
                </small>
            </div>

            {{-- 3. TOMBOL LOGOUT --}}
            <form action="{{ route('logout.submit') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-danger p-0 d-flex align-items-center"
                    title="Logout">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light border-0 d-lg-none shadow-sm rounded-circle"
                    style="width: 40px; height: 40px;" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="fw-bold m-0 d-none d-sm-block text-body">@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                {{-- ================= BAGIAN BARU: PLAN TOGGLE / STATUS ================= --}}
                @php
                    $isPremium = auth()->user()->is_premium;
                @endphp

                @if ($isPremium)
                    <div class="d-flex align-items-center gap-2">
                        {{-- 1. BADGE STATUS (Paket Saat Ini) --}}
                        <div class="d-flex align-items-center px-3 py-1 rounded-pill bg-success-subtle border border-success border-opacity-25"
                            title="Paket Premium Aktif">
                            <i class="bi bi-star-fill text-success me-2"></i>
                            <div class="d-flex flex-column" style="line-height: 1;">
                                <span class="fw-bold text-success" style="font-size: 0.85rem;">
                                    {{ Auth::user()->activeSubscription?->plan?->plan_name }}
                                </span>
                                <small class="text-muted" style="font-size: 0.65rem;">
                                    Aktif s.d
                                    {{ \Carbon\Carbon::parse(auth()->user()->activeSubscription->date_end)->format('d M Y') }}
                                </small>
                            </div>
                        </div>

                        {{-- 2. TOMBOL UPGRADE (Hanya muncul jika paket BULANAN) --}}
                        @php
                            $currentPlanName = Auth::user()->activeSubscription?->plan?->plan_name ?? '';
                            // Cek apakah nama paket mengandung kata "Bulan" atau "Monthly"
                            $isMonthly = \Illuminate\Support\Str::contains(strtolower($currentPlanName), [
                                'bulan',
                                'monthly',
                            ]);
                        @endphp

                        @if ($isMonthly)
                            <a href="{{ route('plans.page') }}"
                                class="btn btn-sm btn-outline-primary rounded-pill fw-bold shadow-sm d-flex align-items-center gap-1 animate-pulse-soft"
                                style="font-size: 0.75rem; height: 32px;" title="Hemat uang dengan paket Tahunan">
                                <i class="bi bi-arrow-up-circle-fill"></i> Upgrade Hemat
                            </a>
                        @endif
                    </div>
                @else
                    {{-- TAMPILAN JIKA FREE (Kode sebelumnya) --}}
                    <a href="{{ route('plans.page') }}"
                        class="btn btn-warning rounded-pill fw-bold text-dark px-3 shadow-sm btn-upgrade-pulse d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span class="d-none d-sm-inline">Upgrade Plan</span>
                        <span class="d-inline d-sm-none">UP</span>
                    </a>
                @endif
                {{-- ================= END BAGIAN BARU ================= --}}

                {{-- Dark Mode Toggle --}}
                <button class="btn btn-link nav-link p-2" onclick="toggleDarkMode()">
                    <i class="bi bi-moon-stars" id="themeIcon" style="font-size: 1.2rem;"></i>
                </button>
            </div>
        </header>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    @include('chatbot.index')
    {{-- GLOBAL SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- ADDED: SweetAlert2 (Wajib untuk popup delete) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/dashboard/layout.js') }}?v={{ time() }}"></script>

    @stack('scripts')
</body>

</html>
