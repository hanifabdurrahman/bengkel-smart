@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/landing/landingpage.css') }}">
@endpush
@section('content')
    {{-- Hero Section --}}
    <section id="home"
        class="hero-section container d-flex flex-column align-items-center justify-content-center min-vh-100">
        <div class="badge-soft-blue" data-aos="fade-down" data-aos-delay="100">
            <i class="fa-solid fa-circle me-1" style="font-size: 6px; vertical-align: middle;"></i>
            #1 SOFTWARE BISNIS TERBAIK DI INDONESIA
        </div>
        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
            Kelola Servis, Stok Sparepart, dan<br>
            <span class="highlight">Keuangan dalam Satu Dashboard</span>
        </h1>
        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="300">
            Tinggalkan pencatatan manual. <strong>Bengkel Smart</strong> adalah aplikasi pembukuan bengkel berbasis cloud
            yang membantu Anda mengelola servis, stok sparepart, dan laporan keuangan bengkel secara <em>real-time</em>,
            akurat, dan aman.
        </p>
        <div class="feature-pills-container" data-aos="fade-up" data-aos-delay="400">
            <div class="feature-pill"><i class="fa-solid fa-check check-icon"></i> Sistem Cloud Aman</div>
            <div class="feature-pill"><i class="fa-solid fa-check check-icon"></i> Kontrol Stok Sparepart</div>
            <div class="feature-pill"><i class="fa-solid fa-check check-icon"></i> Laporan Otomatis</div>
        </div>
        <div class="d-flex gap-3 flex-wrap justify-content-center" data-aos="fade-up" data-aos-delay="500">
            {{-- Asumsi ID 1 adalah paket gratis --}}
            <a href="{{ route('register', ['plan' => 1]) }}" class="btn btn-custom-primary">
                Coba Gratis Sekarang
            </a>

            <a href="#features" class="btn btn-custom-outline">Lihat Fitur Lengkap</a>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="features-section" id="features">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5" data-aos="fade-right">
                    <span class="section-subtitle">KENAPA MEMILIH BENGKEL SMART?</span>
                    <h2 class="section-title">Software Pembukuan Bengkel <span class="highlight">Modern &
                            Profesional</span></h2>
                    <p class="text-muted mb-5" style="color: var(--text-muted) !important;">
                        Kelola bengkel Anda secara menyeluruh dengan <em>real-time</em>. <strong>Bengkel Smart</strong>
                        dirancang khusus untuk pemilik bengkel agar bisnis otomotif Anda tumbuh lebih cepat, rapi, dan
                        efisien.
                    </p>
                    <div class="row gy-4">
                        <div class="col-md-6 feature-item">
                            <div class="feature-icon-circle"><i class="fa-solid fa-check"></i></div>
                            <div class="feature-text">
                                <h5>Mudah Digunakan</h5>
                                <p>Antarmuka simpel, pemilik bisa langsung mengelola operasional tanpa repot.</p>
                            </div>
                        </div>
                        <div class="col-md-6 feature-item">
                            <div class="feature-icon-circle"><i class="fa-solid fa-check"></i></div>
                            <div class="feature-text">
                                <h5>Administrasi Otomatis</h5>
                                <p>Hemat waktu hingga 50% dalam pencatatan nota dan laporan keuangan.</p>
                            </div>
                        </div>
                        <div class="col-md-6 feature-item">
                            <div class="feature-icon-circle"><i class="fa-solid fa-check"></i></div>
                            <div class="feature-text">
                                <h5>Terjangkau untuk UMKM</h5>
                                <p>Berlangganan bulanan, tanpa perlu server mahal.</p>
                            </div>
                        </div>
                        <div class="col-md-6 feature-item">
                            <div class="feature-icon-circle"><i class="fa-solid fa-check"></i></div>
                            <div class="feature-text">
                                <h5>Pantau dari Mana Saja</h5>
                                <p>Laporan omzet dan stok bisa dicek langsung lewat HP, kapan saja.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="dashboard-wrapper">
                        <div class="dashboard-glow"></div>
                        <div class="dashboard-card">
                            <div class="db-sidebar d-none d-sm-flex">
                                <div class="db-dot active"></div>
                                <div class="db-dot"></div>
                                <div class="db-dot"></div>
                                <div class="db-dot"></div>
                                <div class="db-dot" style="margin-top: auto;"></div>
                            </div>
                            <div class="db-content">
                                <div class="db-header-dots">
                                    <div class="dot dot-red"></div>
                                    <div class="dot dot-yellow"></div>
                                    <div class="dot dot-green"></div>
                                </div>
                                <div class="db-stats-row">
                                    <div class="db-stat-card">
                                        <div class="db-icon-box bg-primary bg-opacity-10 text-primary"><i
                                                class="fa-solid fa-check"></i></div>
                                        <div class="db-label">Total Servis</div>
                                        <div class="db-value">128</div>
                                    </div>
                                    <div class="db-stat-card">
                                        <div class="db-icon-box bg-success bg-opacity-10 text-success"><i
                                                class="fa-solid fa-plus"></i></div>
                                        <div class="db-label">Pemasukan</div>
                                        <div class="db-value">Rp 12.5jt</div>
                                    </div>
                                    <div class="db-stat-card">
                                        <div class="db-icon-box bg-warning bg-opacity-10 text-warning"><i
                                                class="fa-regular fa-square"></i></div>
                                        <div class="db-label">Stok Kritis</div>
                                        <div class="db-value">3 Item</div>
                                    </div>
                                </div>
                                <div class="db-chart-area">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-bold" style="font-size: 0.9rem; color: var(--text-main);">
                                            Statistik Mingguan</h6>
                                        <div class="chart-tooltip">Ramai</div>
                                    </div>
                                    <svg viewBox="0 0 300 120" class="chart-svg">
                                        <line x1="0" y1="100" x2="300" y2="100"
                                            stroke="#94a3b8" stroke-width="1" stroke-opacity="0.3" />
                                        <line x1="0" y1="50" x2="300" y2="50"
                                            stroke="#94a3b8" stroke-width="1" stroke-opacity="0.3" />
                                        <path class="chart-area"
                                            d="M0,100 L10,90 L60,60 L110,80 L160,50 L210,70 L260,40 L290,50 L300,100 Z" />
                                        <path class="chart-line"
                                            d="M10,90 L60,60 L110,80 L160,50 L210,70 L260,40 L290,50" />
                                        <circle cx="10" cy="90" r="3" class="chart-point" />
                                        <circle cx="60" cy="60" r="3" class="chart-point" />
                                        <circle cx="110" cy="80" r="3" class="chart-point" />
                                        <circle cx="160" cy="50" r="3" class="chart-point" />
                                        <circle cx="210" cy="70" r="3" class="chart-point" />
                                        <circle cx="260" cy="40" r="3" class="chart-point" />
                                        <circle cx="290" cy="50" r="3" class="chart-point" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid-features-section">
        <div class="container">
            <div class="text-center mb-5 pb-3" data-aos="fade-up">
                <span class="section-subtitle">FITUR UNGGULAN BENGKEL SMART</span>
                <h2 class="section-title mx-auto" style="max-width: 700px;">Software Manajemen Bengkel</h2>
                <p class="hero-subtitle mx-auto" style="max-width: 700px;">Kelola seluruh operasional bengkel Anda dalam
                    satu platform meliputi servis, stok sparepart, transaksi, dan laporan keuangan secara menyeluruh.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card-grid">
                        <div class="grid-icon-box"><i class="fa-solid fa-gears"></i></div>
                        <h4 class="grid-feature-title">Pencatatan Servis Digital</h4>
                        <p class="grid-feature-desc">Semua riwayat servis dan penggantian sparepart tercatat rapi di
                            satu dashboard.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card-grid">
                        <div class="grid-icon-box"><i class="fa-solid fa-cart-shopping"></i></div>
                        <h4 class="grid-feature-title">Manajemen Transaksi Otomatis</h4>
                        <p class="grid-feature-desc">Hitung biaya servis dan cetak struk secara profesional tanpa repot.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card-grid">
                        <div class="grid-icon-box"><i class="fa-solid fa-box-open"></i></div>
                        <h4 class="grid-feature-title">Manajemen Spare Part Otomatis</h4>
                        <p class="grid-feature-desc">Stok sparepart berkurang otomatis, mencegah selisih dan kehilangan.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card-grid">
                        <div class="grid-icon-box"><i class="fa-solid fa-chart-column"></i></div>
                        <h4 class="grid-feature-title">Laporan Keuangan Real-time</h4>
                        <p class="grid-feature-desc">Pantau omzet, laba rugi, dan arus kas kapan saja tanpa harus ahli
                            akuntansi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card-grid">
                        <div class="grid-icon-box"><i class="fa-solid fa-users"></i></div>
                        <h4 class="grid-feature-title">Database Pelanggan & Riwayat</h4>
                        <p class="grid-feature-desc">Simpan data pelanggan dan kendaraan, kirim pengingat servis untuk
                            meningkatkan retensi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card-grid">
                        <div class="grid-icon-box"><i class="fa-solid fa-shield-halved"></i></div>
                        <h4 class="grid-feature-title">Kontrol Penuh oleh Pemilik</h4>
                        <p class="grid-feature-desc">Semua fitur dirancang agar pemilik bengkel dapat mengelola
                            operasional secara menyeluruh dan aman.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. TESTIMONIALS --}}
    {{-- Mengubah py-5 (besar) menjadi py-4 (sedang) untuk jarak section --}}
    <section class="testimonials-section py-4">
        <div class="container">
            <div class="text-center mb-4" data-aos="fade-up"> {{-- Margin bawah dikurangi mb-5 -> mb-4 --}}
                <span class="section-subtitle">TESTIMONI</span>
                <h2 class="section-title">Apa Kata Mitra Kami?</h2>
                <p class="hero-subtitle mx-auto" style="max-width: 600px;">Ratusan bengkel di Indonesia telah beralih ke
                    cara yang lebih cerdas.</p>
            </div>
            {{-- Mengubah row g-4 menjadi row g-3 agar jarak antar kartu lebih rapat --}}
            <div class="row g-3">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon-large"></i>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Dulu saya sering pusing karena stok oli dan sparepart sering
                            selisih. Sejak pakai Bengkel Smart, stok jadi akurat dan saya bisa pantau omzet bengkel
                            langsung dari HP saat di rumah."</p>
                        <div class="user-profile">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="user-avatar">
                            <div class="user-info">
                                <h6>Pak Budi</h6><span>Pemilik Bengkel Maju Motor</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon-large"></i>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Aplikasinya sangat mudah digunakan, kasir saya cuma butuh 10 menit
                            untuk paham. Fitur riwayat servisnya juara, pelanggan juga senang."</p>
                        <div class="user-profile">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User"
                                class="user-avatar">
                            <div class="user-info">
                                <h6>Ibu Sari</h6><span>Owner Auto Care 88</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon-large"></i>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Laporan keuangan otomatisnya sangat membantu. Akhir bulan tidak
                            perlu lembur lagi buat rekap nota satu per satu. Bengkel Smart benar-benar bengkel cerdas buat
                            bengkel UMKM."</p>
                        <div class="user-profile">
                            <img src="https://randomuser.me/api/portraits/men/85.jpg" alt="User" class="user-avatar">
                            <div class="user-info">
                                <h6>Mas Denny</h6><span>Kepala Mekanik Denny Garage</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon-large"></i>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Fitur pengingat ganti olinya sangat efektif. Banyak pelanggan lama
                            yang kembali lagi servis rutin karena notifikasi otomatis dari sistem ini. Omzet naik
                            signifikan."</p>
                        <div class="user-profile">
                            <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="User" class="user-avatar">
                            <div class="user-info">
                                <h6>Rahmat Hidayat</h6><span>Owner Bengkel Sejahtera</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon-large"></i>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Dulu sering bingung hitung komisi mekanik setiap minggu. Sekarang
                            laporan komisi keluar otomatis berdasarkan pekerjaan yang diselesaikan. Sangat transparan
                            dan adil."</p>
                        <div class="user-profile">
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="User"
                                class="user-avatar">
                            <div class="user-info">
                                <h6>Citra Lestari</h6><span>Admin Utama Citra Motor</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon-large"></i>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Saya sangat terbantu dengan fitur analisa bisnisnya. Saya jadi tahu
                            sparepart mana yang paling laris (fast moving) dan mana yang menumpuk di gudang. Stok jadi
                            lebih efisien."</p>
                        <div class="user-profile">
                            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="User" class="user-avatar">
                            <div class="user-info">
                                <h6>Hendra Wijaya</h6><span>CEO Wijaya Auto Body</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. Pricing Section (COMPACT LAYOUT) --}}
    <section class="pricing-section py-4" id="pricing"> {{-- Ubah padding section py-5 -> py-4 --}}
        <div class="container">
            <div class="text-center mb-4" data-aos="fade-up"> {{-- Margin header mb-5 -> mb-4 --}}
                <span class="section-subtitle">HARGA PAKET</span>
                <h2 class="section-title">Investasi Terbaik untuk Bengkel Anda</h2>
                <p class="hero-subtitle mx-auto" style="max-width: 600px;">
                    Pilih paket langganan software bengkel yang sesuai dengan skala bisnis Anda. Mulai dari Gratis!
                </p>
            </div>

            {{-- Mengubah row g-4 menjadi row g-3 agar jarak antar kartu lebih rapat --}}
            <div class="row g-3 align-items-stretch justify-content-center">
                @foreach ($plans as $plan)
                    <div class="col-lg-4" data-aos="{{ $plan->is_popular ? 'zoom-in' : 'fade-up' }}">
                        <div class="pricing-card h-100 {{ $plan->is_popular ? 'pricing-card-highlighted' : '' }} ">

                            @if ($plan->is_popular)
                                <div class="pricing-badge">{{ $plan->badge ?? 'PALING DIMINATI' }}</div>
                            @elseif($plan->badge)
                                <div class="pricing-badge badge-standard">{{ $plan->badge }}</div>
                            @endif

                            <div class="pricing-header">
                                <span class="pricing-tier-name">{{ $plan->plan_name }}</span>
                                <h3 class="pricing-amount">
                                    @if ($plan->price == 0)
                                        Gratis
                                    @else
                                        Rp {{ number_format($plan->price, 0, ',', '.') }}
                                        <small>/{{ $plan->duration_days == 30 ? 'bulan' : ($plan->duration_days >= 360 ? 'tahun' : 'periode') }}</small>
                                    @endif
                                </h3>
                                <span class="pricing-period">{{ $plan->description }}</span>
                            </div>

                            <div class="pricing-divider"></div>

                            <ul class="pricing-features">
                                @foreach ($plan->features ?? [] as $feature)
                                    <li>
                                        <div class="icon-wrap"><i class="fa-solid fa-check"></i></div>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-auto w-100">
                                @auth
                                    <a href="{{ route('subscription.checkout', ['plan' => $plan->plan_id]) }}"
                                        class="btn {{ $plan->is_popular ? 'btn-primary' : 'btn-outline-primary' }} btn-select w-100">
                                        {{ $plan->price > 0 ? 'Langganan Sekarang' : 'Pilih Paket Gratis' }}
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary btn-select w-100">
                                        Mulai Sekarang
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="faq-section" id="faq">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-subtitle">FAQ</span>
                <h2 class="section-title">Pertanyaan Umum</h2>
                <p class="hero-subtitle mx-auto" style="max-width: 600px;">
                    Jawaban untuk pertanyaan yang sering diajukan mitra kami.
                </p>
            </div>
            <div class="row">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="accordion accordion-modern" id="faqAccordionLeft">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse1">1. Apakah ada biaya tambahan tersembunyi?</button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show"
                                data-bs-parent="#faqAccordionLeft">
                                <div class="accordion-body">Tidak. Semua biaya tercantum jelas sesuai paket langganan.
                                    Fitur update juga kami berikan gratis.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse2">2. Apakah bisa digunakan di HP/Smartphone?</button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
                                <div class="accordion-body">Ya, Bengkel Smart berbasis web cloud yang responsif. Anda bisa
                                    mengaksesnya lewat HP, Tablet, maupun Laptop.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse3">3. Bagaimana jika internet di bengkel mati?</button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordionLeft">
                                <div class="accordion-body">Aplikasi membutuhkan koneksi internet. Namun untuk penggunaan
                                    di HP, koneksi data seluler standar sudah sangat cukup dan ringan.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="accordion accordion-modern" id="faqAccordionRight">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse4">4. Apakah data pelanggan saya aman?</button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
                                <div class="accordion-body">Sangat aman. Kami menggunakan enkripsi SSL standar perbankan
                                    dan backup data harian otomatis.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse5">5. Saya gaptek, apakah ada pelatihan?</button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
                                <div class="accordion-body">Tentu! Kami menyediakan video tutorial lengkap dan tim support
                                    WhatsApp yang siap memandu Anda step-by-step.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse6">6. Printer apa yang kompatibel?</button>
                            </h2>
                            <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordionRight">
                                <div class="accordion-body">Hampir semua printer thermal bluetooth (58mm/80mm) yang ada di
                                    pasaran kompatibel dengan sistem kami.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
