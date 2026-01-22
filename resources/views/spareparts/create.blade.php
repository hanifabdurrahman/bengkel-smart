@extends('layouts.dashboardLayout')

@section('title', 'Tambah Sparepart - BengkelSmart')
@section('page-title', 'TAMBAH SPAREPART')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10"> {{-- Sedikit diperlebar agar 3 kolom harga muat --}}

            <form action="{{ route('spareparts.store') }}" method="POST">
                @csrf

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- Header --}}
                    <div class="card-header bg-white p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">Form Sparepart Baru</h5>
                                <small class="text-muted">Isi data sparepart sesuai stok aktual.</small>
                            </div>
                            <a href="{{ route('spareparts.index') }}" class="btn btn-light border rounded-pill px-3">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    {{-- Body Form --}}
                    <div class="card-body p-4">

                        {{-- Nama Sparepart --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Nama Sparepart <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="sparepart_name"
                                class="form-control form-control-lg bg-light border-0 @error('sparepart_name') is-invalid @enderror"
                                value="{{ old('sparepart_name') }}" placeholder="Contoh: Kampas Rem Depan Honda">
                            @error('sparepart_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            {{-- Kode Sparepart --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Kode Sparepart</label>
                                <input type="text" name="sparepart_code"
                                    class="form-control bg-light border-0 @error('sparepart_code') is-invalid @enderror"
                                    placeholder="Opsional, contoh: BRK-001" value="{{ old('sparepart_code') }}">
                                {{-- Menambahkan Error Handling untuk Kode Sparepart (Penting untuk Cek Unik) --}}
                                @error('sparepart_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Lokasi Rak --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Lokasi Penyimpanan</label>
                                <input type="text" name="rack_location"
                                    class="form-control bg-light border-0 @error('rack_location') is-invalid @enderror"
                                    placeholder="Contoh: Rak B2" value="{{ old('rack_location') }}">
                                @error('rack_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 border-light">

                        {{-- Section Stok & Harga --}}
                        <div class="row g-3">
                            {{-- Stok Awal --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-uppercase text-muted">Stok Awal <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="stock_quantity" placeholder="0"
                                    class="form-control bg-light border-0 @error('stock_quantity') is-invalid @enderror"
                                    value="{{ old('stock_quantity') }}">
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Harga Beli (Modal) --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-uppercase text-muted">Harga Beli (Modal) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 fw-bold text-muted">Rp</span>
                                    <input type="number" name="buying_price"
                                        class="form-control bg-light border-0 @error('buying_price') is-invalid @enderror"
                                        placeholder="0" value="{{ old('buying_price') }}">
                                    {{-- Invalid feedback ditaruh di dalam input group tidak akan rapi, kita taruh di bawahnya --}}
                                </div>
                                <div class="form-text small text-muted">Harga beli dari supplier per unit.</div>
                                @error('buying_price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Harga Jual --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-uppercase text-muted">Harga Jual <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 fw-bold text-muted">Rp</span>
                                    <input type="number" name="selling_price"
                                        class="form-control bg-light border-0 @error('selling_price') is-invalid @enderror"
                                        placeholder="0" value="{{ old('selling_price') }}">
                                </div>
                                <div class="form-text small text-muted">Harga jual ke pelanggan.</div>
                                @error('selling_price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Entry Date --}}
                        <div class="mt-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tanggal Masuk <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="entry_date"
                                class="form-control bg-light border-0 w-auto @error('entry_date') is-invalid @enderror"
                                value="{{ old('entry_date', date('Y-m-d')) }}">
                            {{-- Menambahkan Error Handling untuk Tanggal --}}
                            @error('entry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="card-footer bg-white p-4 text-end border-top-0">
                        <button type="reset" class="btn btn-light border me-2 fw-medium px-4">Reset</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Data
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection
