{{-- Tambahkan CSS kecil ini di bagian atas file partial atau di layout utama untuk Avatar --}}
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: #e0e7ff;
        color: #4338ca;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .btn-icon:hover {
        background-color: #f3f4f6;
        transform: scale(1.1);
    }
</style>

<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 w-100">
            <thead class="bg-light border-bottom">
                <tr>
                    <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Pelanggan</th>
                    <th class="py-3 text-uppercase text-secondary small fw-bold">Kendaraan</th>
                    <th class="py-3 text-uppercase text-secondary small fw-bold">Tanggal Masuk</th>
                    <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Status</th>
                    <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        {{-- Kolom Pelanggan (Modern dengan Avatar) --}}
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                {{-- Avatar Inisial --}}
                                <div class="avatar-circle me-3 flex-shrink-0">
                                    {{ substr($service->customer->customer_name, 0, 1) }}
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $service->customer->customer_name }}</span>
                                    <small class="text-muted d-flex align-items-center gap-1">
                                        <i class="bi bi-person-vcard"></i>
                                        {{ $service->kode_servis }}
                                    </small>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Kendaraan --}}
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-dark">{{ $service->customer->license_plate }}</span>
                                <small class="text-muted">{{ $service->customer->vehicle ?? '-' }}</small>
                            </div>
                        </td>

                        {{-- Kolom Waktu Tunggu (BARU) --}}
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">
                                    <i class="bi bi-calendar-week me-1"></i>
                                    {{ \Carbon\Carbon::parse($service->tanggal_masuk)->translatedFormat('d F Y') }}
                                </span>
                            </div>

                        </td>

                        {{-- Kolom Status --}}
                        <td class="text-center">
                            @if ($service->status == 'antri')
                                <span
                                    class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                </span>
                            @else
                                <span
                                    class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                    <i class="bi bi-tools me-1"></i> Dikerjakan
                                </span>
                            @endif
                        </td>

                        {{-- Kolom Aksi (Ada Tombol Batal) --}}
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('services.show', $service->service_id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Detail
                                </a>


                                {{-- Tombol Batal (Hapus) --}}
                                <form action="{{ route('services.destroy', $service->service_id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-icon text-danger delete-btn"
                                        data-bs-toggle="tooltip" title="Batalkan Servis">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60"
                                    class="mb-3 opacity-25">
                                <h6 class="text-muted fw-bold mb-0">Tidak ada antrian saat ini</h6>
                                <small class="text-muted">Data akan muncul setelah pelanggan mendaftar.</small>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-between align-items-center p-3 border-top bg-light-subtle">
    <small class="text-muted">
        Menampilkan data <b>{{ $services->firstItem() ?? 0 }}</b> sampai <b>{{ $services->lastItem() ?? 0 }}</b>
        dari <b>{{ $services->total() }}</b> antrian.
    </small>
    <div>
        {{ $services->withQueryString()->links() }}
    </div>
</div>

{{-- Script Khusus Konfirmasi Delete --}}
<script>
    // Menggunakan Event Delegation karena elemen ini dimuat via AJAX
    // Jika tidak pakai cara ini, tombol delete di halaman ke-2 tidak akan jalan
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin membatalkan dan menghapus antrian servis ini?')) {
                this.closest('form').submit();
            }
        });
    });

    // Inisialisasi Tooltip Bootstrap (Opsional, agar tooltip muncul)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
