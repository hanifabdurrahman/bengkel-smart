<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight: bold; font-size: 14px; text-align: center;">
                LAPORAN KEUANGAN - {{ strtoupper($workshop->workshop_name) }}
            </th>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center;">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </td>
        </tr>
        <tr></tr>

        {{-- Header Tabel --}}
        <tr style="background-color: #eeeeee;">
            <th style="border: 1px solid #000000; font-weight: bold; width: 5px; text-align: center;">No</th>
            <th style="border: 1px solid #000000; font-weight: bold; width: 20px;">Tanggal</th>
            <th style="border: 1px solid #000000; font-weight: bold; width: 25px;">Kode Servis</th>
            <th style="border: 1px solid #000000; font-weight: bold; width: 30px;">Pelanggan / Item Detail</th>
            <th style="border: 1px solid #000000; font-weight: bold; width: 20px; text-align: right;">Omset (Rp)</th>
            <th style="border: 1px solid #000000; font-weight: bold; width: 20px; text-align: right; color: #ff0000;">
                Modal (Rp)</th>
            <th style="border: 1px solid #000000; font-weight: bold; width: 20px; text-align: right; color: #008000;">
                Profit (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $index => $trx)
            {{-- BARIS UTAMA TRANSAKSI --}}
            <tr>
                <td
                    style="border: 1px solid #000000; text-align: center; vertical-align: top; background-color: #ffffff;">
                    {{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; vertical-align: top; background-color: #ffffff;">
                    {{ $trx->created_at->format('d/m/Y H:i') }}</td>
                <td style="border: 1px solid #000000; vertical-align: top; background-color: #ffffff;">
                    {{ $trx->services->kode_servis ?? '-' }}</td>
                <td
                    style="border: 1px solid #000000; vertical-align: top; background-color: #ffffff; font-weight: bold;">
                    {{ $trx->customer->customer_name ?? 'Guest' }} - ({{ $trx->customer->vehicle ?? '-' }})
                </td>

                <td
                    style="border: 1px solid #000000; text-align: right; vertical-align: top; background-color: #ffffff;">
                    Rp {{ number_format($trx->total_akhir, 0, ',', '.') }}
                </td>

                {{-- Format Rupiah Modal Transaksi --}}
                <td
                    style="border: 1px solid #000000; text-align: right; vertical-align: top; background-color: #ffffff; color: #ff0000;">
                    Rp {{ number_format($trx->modal_transaksi, 0, ',', '.') }}
                </td>

                {{-- Format Rupiah Profit Transaksi --}}
                <td
                    style="border: 1px solid #000000; text-align: right; vertical-align: top; background-color: #ffffff; color: #008000;">
                    Rp {{ number_format($trx->profit_transaksi, 0, ',', '.') }}
                </td>
            </tr>

            {{-- LOOP DETAIL SPAREPART --}}
            @foreach ($trx->salesDetails as $detail)
                @php
                    // Logika Snapshot Modal
                    $modalSatuan =
                        $detail->current_buying_price > 0
                            ? $detail->current_buying_price
                            : $detail->sparepart->buying_price ?? 0;
                    $subModal = $modalSatuan * $detail->jumlah;
                @endphp
                <tr>
                    <td
                        style="border-left: 1px solid #000000; border-right: 1px solid #000000; background-color: #fafafa;">
                    </td>
                    <td
                        style="border-left: 1px solid #000000; border-right: 1px solid #000000; background-color: #fafafa;">
                    </td>
                    <td
                        style="border-left: 1px solid #000000; border-right: 1px solid #000000; background-color: #fafafa;">
                    </td>
                    <td
                        style="border: 1px solid #000000; color: #555555; font-style: italic; background-color: #fafafa;">
                        - {{ $detail->sparepart->sparepart_name ?? 'Item Terhapus' }}
                        {{-- Format Rupiah Harga Satuan di dalam kurung --}}
                        (Qty: {{ $detail->jumlah }} x Rp {{ number_format($modalSatuan, 0, ',', '.') }})
                    </td>
                    <td style="border: 1px solid #000000; background-color: #fafafa;"></td> {{-- Kosongkan kolom omset per item --}}

                    {{-- Format Rupiah Sub Modal Item --}}
                    <td
                        style="border: 1px solid #000000; text-align: right; color: #ff0000; font-style: italic; background-color: #fafafa;">
                        Rp {{ number_format($subModal, 0, ',', '.') }}
                    </td>
                    <td style="border: 1px solid #000000; background-color: #fafafa;"></td> {{-- Kosongkan kolom profit per item --}}
                </tr>
            @endforeach

            {{-- DETAIL JASA MEKANIK (Jika ada) --}}
            @if ($trx->services && $trx->services->biaya_jasa_modal > 0)
                <tr>
                    <td
                        style="border-left: 1px solid #000000; border-right: 1px solid #000000; border-bottom: 1px solid #000000; background-color: #fafafa;">
                    </td>
                    <td
                        style="border-left: 1px solid #000000; border-right: 1px solid #000000; border-bottom: 1px solid #000000; background-color: #fafafa;">
                    </td>
                    <td
                        style="border-left: 1px solid #000000; border-right: 1px solid #000000; border-bottom: 1px solid #000000; background-color: #fafafa;">
                    </td>
                    <td
                        style="border: 1px solid #000000; color: #555555; font-style: italic; background-color: #fafafa;">
                        - Jasa Mekanik / Komisi
                    </td>
                    <td style="border: 1px solid #000000; background-color: #fafafa;"></td>

                    {{-- Format Rupiah Modal Jasa --}}
                    <td
                        style="border: 1px solid #000000; text-align: right; color: #ff0000; font-style: italic; background-color: #fafafa;">
                        Rp {{ number_format($trx->services->biaya_jasa_modal, 0, ',', '.') }}
                    </td>
                    <td style="border: 1px solid #000000; background-color: #fafafa;"></td>
                </tr>
            @else
                {{-- Penutup border bawah jika tidak ada jasa tapi ada sparepart --}}
                <tr>
                    <td colspan="7" style="border-top: 1px solid #cccccc; height: 1px;"></td>
                </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"
                style="border: 1px solid #000000; font-weight: bold; text-align: right; background-color: #eeeeee;">
                GRAND TOTAL</td>

            {{-- Format Rupiah Total Revenue --}}
            <td style="border: 1px solid #000000; font-weight: bold; text-align: right; background-color: #eeeeee;">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </td>

            {{-- Format Rupiah Total Modal --}}
            <td
                style="border: 1px solid #000000; font-weight: bold; text-align: right; color: #ff0000; background-color: #eeeeee;">
                Rp {{ number_format($totalModal, 0, ',', '.') }}
            </td>

            {{-- Format Rupiah Net Profit --}}
            <td
                style="border: 1px solid #000000; font-weight: bold; text-align: right; color: #008000; background-color: #eeeeee;">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>
