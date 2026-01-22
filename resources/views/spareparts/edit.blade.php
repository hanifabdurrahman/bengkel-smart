@extends('layouts.dashboardLayout')

@section('title', 'Edit Sparepart - BengkelSmart')
@section('page-title', 'EDIT SPAREPART')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sparepart/edit.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <form action="{{ route('spareparts.edit', $sparepart->sparepart_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    {{-- Header --}}
                    <div class="card-header bg-white p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">Edit Data Barang</h5>
                                <p class="text-muted small mb-0">
                                    Perbarui data sparepart: <strong>{{ $sparepart->sparepart_name }}</strong>
                                </p>
                            </div>
                            <a href="{{ route('spareparts.index') }}"
                                class="btn btn-light border rounded-pill px-3 text-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        {{-- Detail Barang --}}
                        <div class="mb-4">
                            <h6 class="section-title text-primary">Detail Identitas</h6>

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label class="form-label">Nama Sparepart <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg bg-light border-0"
                                    name="sparepart_name" value="{{ old('sparepart_name', $sparepart->sparepart_name) }}"
                                    required>
                            </div>

                            <div class="row g-3">
                                {{-- Kode --}}
                                <div class="col-md-6">
                                    <label class="form-label">Kode Barang</label>
                                    <input type="text" class="form-control bg-light border-0" name="sparepart_code"
                                        value="{{ old('sparepart_code', $sparepart->sparepart_code) }}">
                                </div>

                                {{-- Lokasi --}}
                                <div class="col-md-6">
                                    <label class="form-label">Lokasi Rak</label>
                                    <input type="text" class="form-control bg-light border-0" name="rack_location"
                                        value="{{ old('rack_location', $sparepart->rack_location) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Stock & Harga (Area Koreksi) --}}
                        <div class="mb-3">
                            <h6 class="section-title mt-4 text-warning">Koreksi Inventaris & Harga</h6>

                            <div class="alert alert-warning border-0 d-flex align-items-start gap-3 mb-3">
                                <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
                                <div>
                                    <span class="fw-bold d-block">Area Koreksi Data</span>
                                    <small>Mengubah <b>Stok</b> atau <b>Harga Modal</b> di sini akan menimpa perhitungan
                                        otomatis sistem. Lakukan hanya jika terdapat kesalahan input data
                                        sebelumnya.</small>
                                </div>
                            </div>

                            <div class="row g-3">
                                {{-- Stock (Koreksi) --}}
                                <div class="col-md-4">
                                    <label class="form-label">Stok Aktual</label>
                                    <input type="number" class="form-control fw-bold" name="stock_quantity"
                                        value="{{ old('stock_quantity', $sparepart->stock_quantity) }}" min="0"
                                        required>
                                </div>

                                {{-- Harga Beli / Modal (Koreksi) --}}
                                <div class="col-md-4">
                                    <label class="form-label">Harga Modal (Avg)</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0">Rp</span>
                                        <input type="number" class="form-control fw-bold text-danger" name="buying_price"
                                            value="{{ old('buying_price', (int) $sparepart->buying_price) }}" min="0"
                                            required>
                                    </div>
                                    <div class="form-text small">Harga beli dari supplier.</div>
                                </div>

                                {{-- Harga Jual --}}
                                <div class="col-md-4">
                                    <label class="form-label">Harga Jual</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white border-primary">Rp</span>
                                        <input type="number" class="form-control fw-bold text-primary" name="selling_price"
                                            value="{{ old('selling_price', (int) $sparepart->selling_price) }}"
                                            min="0" required>
                                    </div>
                                    <div class="form-text small">Harga jual ke pelanggan.</div>
                                </div>
                            </div>

                            {{-- Tanggal Masuk --}}
                            <div class="mt-3">
                                <label class="form-label">Tanggal Masuk (Data)</label>
                                <input type="date" class="form-control w-auto" name="entry_date"
                                    value="{{ old('entry_date', optional($sparepart->entry_date)->format('Y-m-d')) }}">
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="card-footer bg-light p-4 text-end">
                        <button type="submit" class="btn btn-primary rounded-pill fw-bold px-5 shadow-sm">
                            <i class="bi bi-check-circle-fill me-1"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection
