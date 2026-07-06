@extends('layouts.dashboardLayout')

@section('title', 'Edit Pelanggan - BengkelSmart')
@section('page-title', 'EDIT PELANGGAN')

@push('styles')
    {{-- Kita bisa menggunakan CSS yang sama dengan create --}}
    <link rel="stylesheet" href="{{ asset('css/customer/create.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Perhatikan route mengarah ke update dan mengirim parameter ID --}}
            <form action="{{ route('customers.update', $customer->customer_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- Header Card --}}
                    <div class="card-header bg-white p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">Edit Data Pelanggan</h5>
                                <p class="text-muted small mb-0">Perbarui informasi pelanggan di bawah ini.</p>
                            </div>
                            <a href="{{ route('customers.index') }}"
                                class="btn btn-light border rounded-pill px-3 text-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        {{-- BAGIAN 1: DATA PRIBADI --}}
                        <div class="mb-4">
                            <h6 class="section-title">Informasi Pemilik</h6>

                            <div class="row g-3">
                                {{-- Nama Lengkap --}}
                                <div class="col-md-12">
                                    <label for="customer_name" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                        id="customer_name" name="customer_name"
                                        value="{{ old('customer_name', $customer->customer_name) }}"
                                        placeholder="Contoh: Budi Santoso">
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- No HP --}}
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Nomor Telepon (WA) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                        id="phone_number" name="phone_number"
                                        value="{{ old('phone_number', $customer->phone_number) }}"
                                        placeholder="Contoh: 081234567890">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span
                                            class="text-muted fw-normal">(Opsional)</span></label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $customer->email) }}"
                                        placeholder="nama@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Alamat --}}
                                <div class="col-12">
                                    <label for="address" class="form-label">Alamat Lengkap</label>
                                    {{-- Khusus Textarea, value diletakkan di antara tag pembuka dan penutup --}}
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                        placeholder="Alamat domisili pelanggan">{{ old('address', $customer->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}></div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- BAGIAN 2: DATA KENDARAAN --}}
                        <div class="mb-3">
                            <h6 class="section-title mt-4">Informasi Kendaraan</h6>

                            <div class="row g-3">
                                {{-- Jenis Mobil --}}
                                <div class="col-md-6">
                                    <label for="vehicle" class="form-label">Jenis Kendaraan <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('vehicle') is-invalid @enderror"
                                        id="vehicle" name="vehicle" value="{{ old('vehicle', $customer->vehicle) }}"
                                        placeholder="Contoh: Toyota Avanza">
                                    @error('vehicle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Plat Nomor --}}
                                <div class="col-md-3">
                                    <label for="license_plate" class="form-label">Nomor Polisi <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control text-uppercase @error('license_plate') is-invalid @enderror"
                                        id="license_plate" name="license_plate"
                                        value="{{ old('license_plate', $customer->license_plate) }}"
                                        placeholder="AB 1234 XX">
                                    @error('license_plate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tahun --}}
                                <div class="col-md-3">
                                    <label for="year" class="form-label">Tahun Pembuatan</label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror"
                                        id="year" name="year" value="{{ old('year', $customer->year) }}"
                                        placeholder="2020">
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Footer Tombol --}}
                    <div class="card-footer bg-light p-4 border-top text-end">

                        <button type="submit" class="btn btn-primary rounded-pill fw-bold px-5 shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection
