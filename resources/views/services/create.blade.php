@extends('layouts.dashboardLayout')
@section('title', 'Pendaftaran Servis')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            {{-- Tambahkan ini di atas form blade Anda --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('services.store') }}" method="POST">
                @csrf

                <input type="hidden" name="customer_id" id="customer_id">

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Data Pelanggan</h5>

                        {{-- CARI PLAT --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Polisi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="license_plate" id="license_plate"
                                    class="form-control form-control-lg text-uppercase fw-bold" placeholder="AB 1234 XY"
                                    required autofocus>
                                <button type="button" class="btn btn-dark px-4" id="btnCheck">
                                    <i class="bi bi-search"></i> Cek
                                </button>
                            </div>
                            <small class="text-muted">Tekan cek untuk mengisi otomatis jika pelanggan sudah
                                terdaftar.</small>
                        </div>

                        {{-- DATA --}}
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pelanggan</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor HP (WA)</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kendaraan</label>
                                <input type="text" name="vehicle" id="vehicle" class="form-control"
                                    placeholder="Ex: Vario 150, Avanza">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="year" id="year" class="form-control" placeholder="2020">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SERVICE --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Detail Servis</h5>

                        <div class="mb-3">
                            <label class="form-label">Keluhan / Permintaan Servis <span class="text-danger">*</span></label>
                            <textarea name="keluhan" class="form-control" rows="3" placeholder="Contoh: Ganti Oli, Rem bunyi, service rutin"
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Servis</label>
                            <input type="text" name="jenis_servis" class="form-control"
                                placeholder="Contoh: Servis Ringan, Tune Up">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold mt-2 shadow-sm">
                            <i class="bi bi-tools me-2"></i> Buat Antrian Servis
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/service/create.js') }}?v={{ time() }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initServiceCreate("{{ route('customers.search') }}");
        });
    </script>
@endpush
