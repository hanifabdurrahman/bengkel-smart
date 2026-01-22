@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sparepart/list.css') }}?v={{ time() }}">
@endpush

<div class="table-responsive">
    <table class="table table-modern mb-0 align-middle w-100">
        <thead>
            <tr>
                <th class="ps-4">Nama Sparepart</th>
                <th>Kode / Lokasi</th>
                <th class="text-center">Stok</th>
                <th class="text-end">Modal (Avg)</th>
                <th class="text-end">Harga Jual</th>
                <th class="pe-4 text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($spareparts as $item)
                <tr class="table-row-hover position-relative">
                    {{-- Nama Sparepart --}}
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-box me-3">
                                <i class="bi bi-box-seam fs-5"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-body">{{ $item->sparepart_name }}</span>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    Updated: {{ $item->updated_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </td>

                    {{-- Kode & Lokasi (Digabung agar hemat tempat) --}}
                    <td>
                        <div class="d-flex flex-column align-items-start gap-1">
                            <span class="font-monospace text-muted small bg-light px-2 py-0 rounded border code"
                                style="font-size: 0.75rem;">
                                {{ $item->sparepart_code ?? '-' }}
                            </span>
                            <div class="d-flex align-items-center text-muted small" style="font-size: 0.8rem;">
                                <i class="bi bi-geo-alt me-1 text-primary opacity-50"></i>
                                {{ $item->rack_location ?? '-' }}
                            </div>
                        </div>
                    </td>

                    {{-- Stok --}}
                    <td class="text-center">
                        @php
                            $stock = $item->stock_quantity;
                            $badgeClass = 'bg-success-subtle text-success';
                            if ($stock <= 0) {
                                $badgeClass = 'bg-secondary-subtle text-secondary';
                            } elseif ($stock <= 5) {
                                $badgeClass = 'bg-danger-subtle text-danger';
                            } elseif ($stock <= 10) {
                                $badgeClass = 'bg-warning-subtle text-warning';
                            }
                        @endphp
                        <div class="badge {{ $badgeClass }} rounded-pill px-3 py-2 fw-medium border border-0">
                            <span>{{ $stock }} pcs</span>
                        </div>
                    </td>

                    {{-- Harga Modal (Avg) --}}
                    <td class="text-end">
                        <span class="text-muted fw-medium small">
                            Rp {{ number_format($item->buying_price, 0, ',', '.') }}
                        </span>
                    </td>

                    {{-- Harga Jual --}}
                    <td class="text-end">
                        <span class="fw-bold text-body">
                            Rp {{ number_format($item->selling_price, 0, ',', '.') }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td class="pe-4 text-end">
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <button type="button" class="btn-restock d-flex align-items-center gap-1"
                                data-bs-toggle="modal" data-bs-target="#restockModal{{ $item->sparepart_id }}">
                                <i class="bi bi-plus-circle-fill"></i> Restock
                            </button>

                            <div class="vr mx-1 opacity-25"></div>

                            <a href="{{ route('spareparts.edit', $item->sparepart_id) }}" class="btn-action"
                                data-bs-toggle="tooltip" title="Edit Data">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('spareparts.destroy', $item->sparepart_id) }}" method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action delete" data-bs-toggle="tooltip"
                                    title="Hapus Data">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <div class="modal fade" id="restockModal{{ $item->sparepart_id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                            <form action="{{ route('spareparts.addStock', $item->sparepart_id) }}" method="POST">
                                @csrf
                                <div class="modal-header bg-success text-white border-0">
                                    <h6 class="modal-title fw-bold">
                                        <i class="bi bi-box-seam me-1"></i> Restock: {{ $item->sparepart_name }}
                                    </h6>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4 text-start">

                                    <div
                                        class="alert alert-light border border-success border-opacity-25 rounded-3 d-flex align-items-center gap-3 mb-4">
                                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                                            <i class="bi bi-info-circle fs-5"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Stok Saat Ini</small>
                                            <span class="fw-bold text-dark fs-5">{{ $item->stock_quantity }} Pcs</span>
                                            <span class="text-muted mx-1">|</span>
                                            <small class="text-muted">Modal Avg: Rp
                                                {{ number_format($item->buying_price, 0) }}</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Jumlah
                                            Masuk</label>
                                        <input type="number" name="qty_masuk"
                                            class="form-control form-control-lg bg-light border-0" placeholder="0"
                                            required min="1">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Harga Beli
                                            Baru (Per Pcs)</label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text bg-light border-0 fw-bold text-muted">Rp</span>
                                            <input type="number" name="harga_beli_baru"
                                                class="form-control form-control-lg bg-light border-0" placeholder="0"
                                                required min="0">
                                        </div>
                                        <div class="form-text small"><i class="bi bi-info-circle-fill me-1"></i> Harga
                                            beli dari supplier hari ini.</div>
                                    </div>

                                </div>
                                <div class="modal-footer border-top-0 bg-light p-3">
                                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                                        <i class="bi bi-save me-1"></i> Simpan Stok
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center py-4">
                            <div class="bg-light rounded-circle p-4 mb-3 d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-inbox text-muted opacity-50" style="font-size: 2.5rem;"></i>
                            </div>
                            <h6 class="fw-bold text-muted mb-1">Tidak ada data ditemukan</h6>
                            <p class="text-muted small mb-3">Silakan tambahkan data sparepart baru.</p>

                            <a href="{{ route('spareparts.create') }}"
                                class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Data
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modern Pagination Footer --}}
@if ($spareparts->hasPages())
    <div class="px-4 py-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <small class="text-muted fw-medium">
            Menampilkan <span class="fw-bold text-dark">{{ $spareparts->firstItem() }}</span> - <span
                class="fw-bold text-dark">{{ $spareparts->lastItem() }}</span>
            dari <span class="fw-bold text-dark">{{ $spareparts->total() }}</span> data
        </small>

        <div>
            {{ $spareparts->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
