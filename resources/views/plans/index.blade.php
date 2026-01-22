@extends('layouts.app')
@section('title', 'Pilih Paket - BengkelSmart')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/plan/plan.css') }}">
@endpush

@section('content')
    <div class="container py-5 py-lg-6 ">
        <div class="text-center mb-5 mt-5 plans-header" data-aos="fade-up">
            <h2 class="fw-bold text-primary mb-2 display-6">Pilih Paket Langganan</h2>
            <p class="text-muted lead">Investasi terbaik untuk kemajuan manajemen bengkel Anda</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach ($plans as $plan)
                {{-- LOGIKA: Cek apakah paket ini adalah paket yang sedang aktif --}}
                @php
                    $isActivePlan = false;
                    if (auth()->check() && auth()->user()->activeSubscription) {
                        $isActivePlan = auth()->user()->activeSubscription->plan_id == $plan->plan_id;
                    }
                @endphp

                <div class="col-md-6 col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div
                        class="plan-card w-100 {{ $plan->is_popular ? 'plan-popular' : '' }} {{ $isActivePlan ? 'border-success border-2 shadow' : '' }}">

                        {{-- 1. Badge Section --}}
                        <div class="mb-2" style="min-height: 32px;">
                            @if ($isActivePlan)
                                <span class="badge bg-success shadow-sm px-3 py-2 rounded-pill">Sedang Aktif ✅</span>
                            @elseif ($plan->badge)
                                <span class="plan-badge shadow-sm">{{ $plan->badge }}</span>
                            @elseif($plan->is_popular)
                                <span class="plan-badge shadow-sm">Populer 🔥</span>
                            @endif
                        </div>

                        {{-- 2. Header Section --}}
                        <h4 class="fw-bold text-dark mb-1">{{ $plan->plan_name }}</h4>

                        <div class="plan-price">
                            @if ($plan->price > 0)
                                Rp {{ number_format($plan->price, 0, ',', '.') }}<small
                                    class="ms-1">/{{ $plan->duration_days == 30 ? 'bulan' : 'tahun' }}</small>
                            @else
                                Gratis
                            @endif
                        </div>

                        <p class="text-muted small plan-description">{{ $plan->description }}</p>

                        {{-- 3. Features Section --}}
                        <div class="plan-features-wrapper">
                            <ul class="plan-features">
                                @foreach ($plan->features ?? [] as $feature)
                                    <li>
                                        <i class="fa-solid fa-check text-success me-2"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- 4. Action Button Section --}}
                        <div class="mt-auto pt-3">
                            @auth
                                {{-- JIKA INI PAKET YANG SEDANG AKTIF --}}
                                @if ($isActivePlan)
                                    <button type="button" class="btn btn-outline-success rounded-pill w-100 fw-bold" disabled
                                        style="cursor: default; opacity: 1;">
                                        <i class="fa-solid fa-check-circle me-1"></i> Paket Saat Ini
                                    </button>
                                    <div class="text-center mt-2">
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            Berakhir:
                                            {{ \Carbon\Carbon::parse(auth()->user()->activeSubscription->date_end)->format('d M Y') }}
                                        </small>
                                    </div>

                                    {{-- JIKA BUKAN PAKET AKTIF (Bisa Upgrade/Downgrade/Free Trial) --}}
                                @else
                                    {{-- Cek apakah paket ini Gratis --}}
                                    @if ($plan->price <= 0)
                                        {{-- Jika Gratis DAN User sudah pernah pakai Free Plan --}}
                                        @if ($hasUsedFreePlan)
                                            <button type="button" class="btn btn-secondary w-100 shadow-sm" disabled
                                                style="cursor: not-allowed; opacity: 0.7;">
                                                Sudah Digunakan
                                            </button>
                                            <div class="text-center mt-2">
                                                <small class="text-danger" style="font-size: 0.75rem;">
                                                    <i class="fa-solid fa-circle-info me-1"></i>Masa percobaan hanya 1x
                                                </small>
                                            </div>

                                            {{-- Jika Gratis tapi User BELUM pernah pakai --}}
                                        @else
                                            <a href="{{ route('subscription.checkout', $plan->plan_id) }}"
                                                class="btn btn-success btn-select w-100 shadow-sm">
                                                Ambil Paket Gratis
                                            </a>
                                        @endif

                                        {{-- Jika Paket BERBAYAR (Bukan Paket Aktif) --}}
                                    @else
                                        <a href="{{ route('subscription.checkout', $plan->plan_id) }}"
                                            class="btn btn-primary btn-select w-100 shadow-sm">
                                            Pindah ke Paket Ini
                                        </a>
                                    @endif
                                @endif
                            @else
                                {{-- Jika Belum Login --}}
                                <a href="{{ route('register', ['plan' => $plan->plan_id]) }}"
                                    class="btn btn-outline-primary btn-select w-100">
                                    Mulai Sekarang
                                </a>
                            @endauth
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
