@extends('layouts.dashboardLayout')

@section('title', 'Edit Profil - BengkelSmart')
@section('page-title', 'PENGATURAN PROFIL')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile/style.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- KOLOM KIRI: IDENTITAS BENGKEL --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4 text-center">
                                <div class="mb-3 position-relative d-inline-block">

                                    {{-- Avatar Image --}}
                                    <img id="preview-logo"
                                        src="{{ $workshop->logo
                                            ? asset('storage/' . $workshop->logo)
                                            : 'https://ui-avatars.com/api/?name=' .
                                                urlencode($workshop->workshop_name) .
                                                '&background=4f46e5&color=fff&size=200' }}"
                                        class="rounded-circle profile-avatar shadow-sm" alt="Logo Bengkel">


                                    {{-- Upload Button --}}
                                    <label for="logo_upload"
                                        class="upload-label position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow cursor-pointer border"
                                        style="cursor: pointer;" title="Ganti Logo">

                                        <i class="bi bi-camera-fill text-primary"></i>

                                        <input type="file" id="logo_upload" name="logo" class="d-none"
                                            accept="image/*" onchange="previewImage(this)">
                                    </label>
                                </div>


                                <h5 class="fw-bold text-dark mb-1">{{ $workshop->workshop_name }}</h5>
                                <div class="d-grid">
                                    <div class="p-3 bg-light rounded-3 text-start mb-2">
                                        <small class="text-muted d-block mb-1">Status Langganan</small>
                                        @if ($workshop->hasActiveSubscription())
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                                <i class="bi bi-check-circle me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Tidak
                                                Aktif</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: FORM EDIT --}}
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">

                                {{-- Bagian 1: Info Dasar --}}
                                <div class="section-header">
                                    <h5 class="fw-bold text-dark mb-0">Informasi Bengkel</h5>
                                    <small class="text-muted">Perbarui detail kontak dan alamat bengkel.</small>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <label class="form-label">Nama Bengkel</label>
                                        <input type="text" name="workshop_name"
                                            class="form-control @error('workshop_name') is-invalid @enderror"
                                            value="{{ old('workshop_name', $workshop->workshop_name) }}" required>
                                        @error('workshop_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email Resmi</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $workshop->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nomor Telepon / WA</label>
                                        <input type="text" name="phone_number"
                                            class="form-control @error('phone_number') is-invalid @enderror"
                                            value="{{ old('phone_number', $workshop->phone_number) }}" required>
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $workshop->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4 opacity-10">

                                {{-- Bagian 2: Ganti Password --}}
                                <div class="section-header border-warning">
                                    <h5 class="fw-bold text-dark mb-0">Keamanan Akun</h5>
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Password Baru</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Minimal 8 karakter">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Ulangi password baru">
                                    </div>
                                </div>

                                <div class="mt-5 text-end">
                                    <button type="reset" class="btn btn-light border fw-semibold me-2 px-4">Reset</button>
                                    <button type="submit" class="btn btn-primary rounded-pill fw-bold px-5 shadow-sm">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('preview-logo').src = e.target.result;
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
