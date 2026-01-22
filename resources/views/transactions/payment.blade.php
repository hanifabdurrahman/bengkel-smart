@extends('layouts.dashboardLayout')
@section('title', 'Pembayaran')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-5">

            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-dark text-white p-4 text-center">
                    <h5 class="mb-0">KASIR PEMBAYARAN</h5>
                    <small>Invoice #{{ $transaction->transaction_id }}</small>
                </div>

                <div class="card-body p-4">
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Total Sparepart</span>
                            <span class="fw-bold">Rp {{ number_format($transaction->total_sparepart, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Biaya Jasa</span>
                            <span class="fw-bold">Rp {{ number_format($transaction->total_jasa, 0, ',', '.') }}</span>
                        </li>
                    </ul>

                    <form action="{{ route('transactions.process', $transaction->transaction_id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small text-muted">Diskon (Rp)</label>
                            <input type="number" name="diskon" id="diskon" class="form-control" value="0"
                                oninput="hitungKembalian()">
                        </div>

                        <div class="bg-light p-3 rounded text-center mb-3">
                            <small class="text-uppercase fw-bold text-muted">Total Tagihan</small>
                            <h2 class="fw-black text-dark mb-0" id="displayTotal">
                                Rp {{ number_format($transaction->total_akhir, 0, ',', '.') }}
                            </h2>
                            <input type="hidden" id="rawTotal"
                                value="{{ $transaction->total_sparepart + $transaction->total_jasa }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Uang Diterima (Rp)</label>
                            <input type="number" name="bayar" id="bayar"
                                class="form-control form-control-lg border-primary" required oninput="hitungKembalian()">
                            <div class="form-text text-end" id="textKembalian">Kembalian: Rp 0</div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold fs-5 shadow">
                            <i class="bi bi-wallet2 me-2"></i> PROSES BAYAR
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function hitungKembalian() {
                let rawTotal = parseFloat(document.getElementById('rawTotal').value) || 0;
                let diskon = parseFloat(document.getElementById('diskon').value) || 0;
                let bayar = parseFloat(document.getElementById('bayar').value) || 0;

                let totalAkhir = rawTotal - diskon;
                if (totalAkhir < 0) totalAkhir = 0;

                // Update tampilan total jika diskon berubah
                document.getElementById('displayTotal').innerText = 'Rp ' + totalAkhir.toLocaleString('id-ID');

                let kembalian = bayar - totalAkhir;
                let textKembalian = document.getElementById('textKembalian');

                if (kembalian >= 0) {
                    textKembalian.innerText = 'Kembalian: Rp ' + kembalian.toLocaleString('id-ID');
                    textKembalian.classList.remove('text-danger');
                    textKembalian.classList.add('text-success', 'fw-bold');
                } else {
                    textKembalian.innerText = 'Kurang: Rp ' + Math.abs(kembalian).toLocaleString('id-ID');
                    textKembalian.classList.add('text-danger');
                    textKembalian.classList.remove('text-success', 'fw-bold');
                }
            }
        </script>
    @endpush
@endsection
