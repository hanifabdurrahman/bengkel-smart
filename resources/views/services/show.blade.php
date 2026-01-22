@extends('layouts.dashboardLayout')
@section('title', 'Detail Pengerjaan')

@push('styles')
    <style>
        /* Kustom Scrollbar agar lebih tipis & modern */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Efek Hover pada Item Pencarian */
        .search-item {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            cursor: pointer;
        }

        .search-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
            border-left: 3px solid #0d6efd;
            /* Warna Primary Bootstrap */
        }

        /* Input Search Ala Spotlight */
        .search-input-modern {
            border: none;
            border-bottom: 2px solid #eee;
            border-radius: 0;
            padding-left: 0;
            box-shadow: none !important;
        }

        .search-input-modern:focus {
            border-bottom-color: #0d6efd;
        }

        /* Letter Spacing untuk label agar terlihat premium */
        .ls-1 {
            letter-spacing: 1px;
        }

        /* Membuat font weight extra tebal untuk harga */
        .fw-black {
            font-weight: 900;
        }

        /* Menghilangkan border default input saat focus agar lebih clean */
        .input-group-lg>.form-control:focus {
            box-shadow: none;
            background-color: #fff !important;
            /* Jadi putih saat diketik */
            border: 1px solid #dee2e6 !important;
        }
    </style>
@endpush

@section('content')
    {{-- === TAMBAHKAN BAGIAN INI UNTUK MENAMPILKAN ERROR/SUKSES === --}}
    <div class="mb-4">
        {{-- 1. Menampilkan Pesan Error dari Controller (with('error')) --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Terjadi Kesalahan!</strong><br>
                        {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 2. Menampilkan Pesan Sukses (with('success')) --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Berhasil!</strong><br>
                        {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 3. Menampilkan Error Validasi Form ($request->validate) --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-x-octagon-fill fs-4 me-3"></i>
                    <div>
                        <strong>Periksa Inputan Anda:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    {{-- === BATAS AKHIR ALERT === --}}
    <div class="row g-4">

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    {{-- Plat Nomor & Kode --}}
                    <h2 class="fw-black mb-0 text-uppercase display-6">{{ $service->customer->license_plate }}</h2>
                    <span
                        class="badge bg-light text-dark border mt-2 px-3 py-2 rounded-pill">{{ $service->kode_servis }}</span>

                    <hr class="my-4" style="border-style: dashed;">

                    {{-- Detail Pelanggan --}}
                    <div class="text-start">
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Pelanggan</small>
                            <div class="fw-bold fs-5">{{ $service->customer->customer_name }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Kendaraan</small>
                            <div class="text-dark">{{ $service->customer->vehicle }} ({{ $service->customer->year }})</div>
                        </div>

                        <div class="alert alert-warning mt-3 mb-4 border-0 small d-flex align-items-start">
                            <i class="bi bi-exclamation-circle-fill me-2 mt-1"></i>
                            <div>
                                <strong>Keluhan:</strong> <br> {{ $service->keluhan }}
                            </div>
                        </div>
                    </div>

                    {{-- === PINDAHAN ESTIMASI TOTAL (TAMPILAN STRUK) === --}}
                    <div class="bg-success-subtle rounded-3 p-3 text-start">
                        <h6 class="fw-bold text-success mb-3 small text-uppercase ls-1">
                            <i class="bi bi-receipt me-1"></i> Estimasi Biaya
                        </h6>

                        {{-- Rincian --}}
                        <div class="d-flex justify-content-between small mb-1 text-success-emphasis">
                            <span>Sparepart:</span>
                            <span class="fw-bold">Rp
                                {{ number_format($service->transaction->total_sparepart, 0, ',', '.') }}</span>
                        </div>
                        <div
                            class="d-flex justify-content-between small mb-3 text-success-emphasis border-bottom border-success border-opacity-25 pb-2">
                            <span>Jasa Mekanik:</span>
                            <span class="fw-bold">Rp {{ number_format($service->biaya_jasa, 0, ',', '.') }}</span>
                        </div>

                        {{-- Total Akhir --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-success">TOTAL</span>
                            <span class="fw-black text-success fs-4">
                                Rp {{ number_format($service->transaction->total_akhir, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    {{-- === AKHIR ESTIMASI === --}}

                </div>

                {{-- Tombol Aksi Status --}}
                <div class="card-footer bg-white p-3 border-0 rounded-bottom-4">
                    <form action="{{ route('services.updateStatus', $service->service_id) }}" method="POST">
                        @csrf @method('PUT')

                        @if ($service->status == 'antri')
                            <button name="status" value="proses"
                                class="btn btn-primary w-100 py-2 rounded-3 fw-bold shadow-sm">
                                <i class="bi bi-wrench-adjustable me-2"></i> Mulai Kerjakan
                            </button>
                        @elseif($service->status == 'proses')
                            <button name="status" value="selesai"
                                class="btn btn-success w-100 py-2 rounded-3 fw-bold shadow-sm"
                                onclick="return confirm('Yakin servis sudah selesai? Stok & Biaya akan dikunci.')">
                                <i class="bi bi-check-circle-fill me-2"></i> Selesai Servis
                            </button>
                        @else
                            <div class="alert alert-success text-center mb-0 fw-bold">
                                <i class="bi bi-check-all"></i> Servis Selesai
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Penggunaan Sparepart</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Nama Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($service->transaction->salesDetails as $detail)
                                    <tr>
                                        <td class="ps-4">{{ $detail->sparepart->sparepart_name }}</td>
                                        <td class="text-center" style="width: 140px;">
                                            @if ($service->status != 'selesai')
                                                <form action="{{ route('service-parts.updateQty', $detail->id_detail) }}"
                                                    method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="input-group input-group-sm">
                                                        {{-- Input Angka --}}
                                                        <input type="number" name="qty"
                                                            class="form-control text-center fw-bold"
                                                            value="{{ $detail->jumlah }}" min="1"
                                                            onchange="this.form.submit()" {{-- Opsi 1: Otomatis submit saat diganti/klik luar --}}>

                                                        {{-- Tombol Update (Opsional visual saja jika pakai onchange) --}}
                                                        <button class="btn btn-outline-secondary" type="submit"
                                                            title="Update Qty">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            @else
                                                {{-- Kalau sudah selesai, tampilkan angka biasa --}}
                                                <span class="fw-bold">{{ $detail->jumlah }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-end pe-4 fw-bold">
                                            {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                        <td class="text-end pe-3">
                                            @if ($service->status != 'selesai')
                                                <form action="{{ route('service-parts.destroy', $detail->id_detail) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-link text-danger p-0"><i
                                                            class="bi bi-x-circle-fill"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada sparepart
                                            digunakan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total Part</td>
                                    <td class="text-end pe-4 fw-bold text-primary">
                                        Rp {{ number_format($service->transaction->total_sparepart, 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- BAGIAN 1: FORM INPUT YANG LEBIH CLEAN --}}
                    @if ($service->status == 'proses')
                        <div class="card border-0 shadow-sm mb-4 bg-white">
                            <div class="card-body p-4">
                                <h6 class="card-title fw-bold mb-3"><i class="bi bi-cart-plus me-2"></i>Tambah Sparepart
                                </h6>

                                <form action="{{ route('service-parts.store') }}" method="POST"
                                    class="row g-3 align-items-end">
                                    @csrf
                                    <input type="hidden" name="transaction_id" value="{{ $service->transaction_id }}">
                                    <input type="hidden" name="sparepart_id" id="selected_sparepart_id" required>

                                    {{-- Kolom Pencarian (Dibuat seolah-olah input text biasa tapi clickable) --}}
                                    <div class="col-md-7">
                                        <label class="form-label text-muted small">Cari Barang</label>
                                        <div class="input-group" style="cursor: pointer;" data-bs-toggle="modal"
                                            data-bs-target="#searchPartModal">
                                            <span class="input-group-text bg-light border-end-0"><i
                                                    class="bi bi-search text-primary"></i></span>
                                            <input type="text"
                                                class="form-control bg-light border-start-0 text-dark fw-bold"
                                                id="selected_sparepart_name" placeholder="Klik untuk cari sparepart..."
                                                readonly style="cursor: pointer;">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label text-muted small">Jumlah</label>
                                        <input type="number" name="qty" class="form-control text-center fw-bold"
                                            value="1" min="1" required>
                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                                            <i class="bi bi-plus-circle me-1"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- BAGIAN 2: MODAL MODERN --}}
                    <div class="modal fade" id="searchPartModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Pilih Sparepart</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Search Bar Besar --}}
                                    <div class="position-relative mb-4 mt-2">
                                        <i
                                            class="bi bi-search position-absolute top-50 start-0 translate-middle-y fs-5 text-muted ms-2"></i>
                                        <input type="text"
                                            class="form-control form-control-lg ps-5 search-input-modern fs-5"
                                            id="searchInput" placeholder="Ketik nama barang" autocomplete="off"
                                            autofocus>
                                    </div>

                                    {{-- Hasil Pencarian --}}
                                    <div id="searchResults" class="list-group custom-scrollbar pe-2"
                                        style="max-height: 400px; overflow-y: auto;">
                                        {{-- Default State (Kosong) --}}
                                        <div class="text-center text-muted py-5">
                                            <i class="bi bi-box-seam fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                            <small>Mulai ketik minimal 2 huruf untuk mencari...</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('services.updateJasa', $service->service_id) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="fw-bold text-muted small text-uppercase ls-1">
                                <i class="bi bi-tools me-1"></i> Input Biaya Jasa
                            </label>
                            @if ($service->status == 'selesai')
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                    <i class="bi bi-lock-fill me-1"></i>Terkunci
                                </span>
                            @endif
                        </div>

                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0 text-muted fw-bold">Rp</span>

                            {{-- 1. INPUT VISUAL (Untuk dilihat user, ada titiknya) --}}
                            <input type="text" id="display_jasa"
                                class="form-control border-0 bg-light fs-4 fw-bold text-dark" placeholder="0"
                                {{-- Format nilai awal pakai PHP number_format --}} value="{{ number_format($service->biaya_jasa, 0, ',', '.') }}"
                                {{ $service->status == 'selesai' ? 'readonly disabled' : '' }}>

                            {{-- 2. INPUT ASLI (Hidden, ini yang dikirim ke Controller) --}}
                            <input type="hidden" name="biaya_jasa" id="real_jasa" value="{{ $service->biaya_jasa }}">

                            @if ($service->status != 'selesai')
                                <button class="btn btn-dark px-4" type="submit" title="Simpan Perubahan">
                                    Simpan <i class="bi bi-check-lg ms-1"></i>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // 1. Event Listener saat mengetik di kolom pencarian
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let query = this.value;
            let resultsContainer = document.getElementById('searchResults');

            if (query.length < 2) { // Minimal 2 huruf baru mencari
                resultsContainer.innerHTML =
                    '<div class="text-center text-muted py-3">Ketik minimal 2 karakter...</div>';
                return;
            }

            // Tampilkan loading
            resultsContainer.innerHTML =
                '<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>';

            // 2. Panggil AJAX ke Server
            // Pastikan Anda membuat route ini nanti di langkah ke-3
            fetch(`/service-part/search?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';

                    if (data.length > 0) {
                        data.forEach(part => {
                            // Format Rupiah
                            let price = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(part.selling_price);

                            // Render Item List
                            html += `
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                               onclick="selectSparepart(${part.sparepart_id}, '${part.sparepart_name}', ${part.selling_price})">
                                <div>
                                    <h6 class="mb-0 fw-bold">${part.sparepart_name}</h6>
                                    <small class="text-muted">Stok: ${part.stock_quantity}</small>
                                </div>
                                <span class="fw-bold text-primary">${price}</span>
                            </a>
                        `;
                        });
                    } else {
                        html = '<div class="text-center text-muted py-3">Sparepart tidak ditemukan.</div>';
                    }

                    resultsContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsContainer.innerHTML =
                        '<div class="text-center text-danger py-3">Terjadi kesalahan server.</div>';
                });
        });
        const displayInput = document.getElementById('display_jasa');
        const realInput = document.getElementById('real_jasa');

        if (displayInput) {
            displayInput.addEventListener('keyup', function(e) {
                // 1. Ambil value dan buang semua karakter selain angka
                let rawValue = this.value.replace(/[^0-9]/g, '');

                // 2. Jika kosong, set jadi 0 atau kosong
                if (rawValue === '') {
                    this.value = '';
                    realInput.value = '';
                    return;
                }

                // 3. Konversi ke integer agar nol di depan hilang (0100 -> 100)
                let integerValue = parseInt(rawValue, 10);

                // 4. Format ke Rupiah (tambah titik)
                let formattedValue = new Intl.NumberFormat('id-ID').format(integerValue);

                // 5. Update tampilan dan input asli
                this.value = formattedValue; // Tampilan: 1.000.000
                realInput.value = integerValue; // Asli: 1000000
            });
        }
        // 3. Fungsi saat item dipilih dari list
        function selectSparepart(id, name, price) {
            // Isi input hidden dan input tampilan di form utama
            document.getElementById('selected_sparepart_id').value = id;
            document.getElementById('selected_sparepart_name').value = name;

            // Tutup Modal (Menggunakan Bootstrap 5 Instance)
            const modalEl = document.getElementById('searchPartModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            // Bersihkan pencarian (opsional)
            document.getElementById('searchInput').value = '';
            document.getElementById('searchResults').innerHTML =
                '<div class="text-center text-muted py-3">Mulai ketik untuk mencari...</div>';
        }
    </script>
@endpush
