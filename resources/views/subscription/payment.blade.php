@extends('layouts.app')

@section('title', 'Pembayaran - BengkelSmart')

@push('styles')
    <style>
        [data-theme="dark"] .card {
            background: linear-gradient(180deg, #1e293b, #0f172a);
            color: #e5e7eb;
            box-shadow:
                0 0 0 1px rgba(59, 130, 246, .35),
                0 20px 40px rgba(0, 0, 0, .6);
        }

        [data-theme="dark"] .card .card-body h3 {
            color: #f8fafc;
        }

        [data-theme="dark"] .card p,
        [data-theme="dark"] .card small {
            color: #cbd5e1;
        }

        [data-theme="dark"] .card .text-muted {
            color: #94a3b8 !important;
        }

        /* Icon */
        [data-theme="dark"] .bi-credit-card-2-front {
            color: #60a5fa;
        }

        /* Button */
        [data-theme="dark"] .btn-primary {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            border: none;
            box-shadow: 0 6px 20px rgba(59, 130, 246, .45);
        }

        [data-theme="dark"] .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, .6);
        }

        /* Cancel link */
        [data-theme="dark"] a.text-muted {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] a.text-muted:hover {
            color: #e5e7eb !important;
        }
    </style>
    {{-- Script Midtrans (Wajib) --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    {{-- Catatan: Ganti URL src ke app.midtrans.com jika production --}}
@endpush

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-5 col-lg-4">

            <div class="card border-0 shadow-lg rounded-4 text-center">
                <div class="card-body p-5">

                    <div class="mb-4 text-primary">
                        <i class="bi bi-credit-card-2-front" style="font-size: 4rem;"></i>
                    </div>

                    <h3 class="fw-bold text-dark mb-2">Selesaikan Pembayaran</h3>
                    <p class="text-muted mb-4">
                        Anda memilih paket <strong>{{ $plan->plan_name }}</strong><br>
                        Total Tagihan: <strong class="text-primary fs-5">Rp
                            {{ number_format($plan->price, 0, ',', '.') }}</strong>
                    </p>

                    <button id="pay-button" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold shadow-sm">
                        Bayar Sekarang
                    </button>

                    <div class="mt-3">
                        <a href="#" {{-- <a href="{{ route('settings.subscription') }}" --}} class="text-muted small text-decoration-none">Batalkan
                            Pesanan</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        // Trigger saat tombol ditekan
        var payButton = document.getElementById('pay-button');

        payButton.addEventListener('click', function() {
            // Trigger snap popup
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    /* Proses sukses, arahkan ke dashboard dengan pesan sukses */
                    // Idealnya: Kirim ke endpoint backend untuk update status jadi 'active'
                    // Tapi Midtrans biasanya butuh Webhook/Notification Handler untuk update real di server.
                    // Untuk UX sementara, kita redirect saja.
                    window.location.href = "{{ route('dashboard') }}?payment=success";
                },
                onPending: function(result) {
                    /* Menunggu pembayaran */
                    alert("Menunggu pembayaran Anda!");
                },
                onError: function(result) {
                    /* Pembayaran gagal */
                    alert("Pembayaran gagal!");
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        });

        // Opsional: Klik otomatis saat halaman dimuat
        // document.addEventListener("DOMContentLoaded", function() {
        //    payButton.click();
        // });
    </script>
@endpush
