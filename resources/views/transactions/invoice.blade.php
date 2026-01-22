<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $transaction->transaction_id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 300px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .border-top {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="text-center mb-1">
        <h3 style="margin:0;">{{ Auth::user()->workshop_name ?? 'BENGKEL SMART' }}</h3>
        <small>Nota Servis & Sparepart</small>
    </div>

    <div class="border-top"></div>

    <table>
        <tr>
            <td>Tgl: {{ date('d/m/Y H:i') }}</td>
            <td class="text-end">No: {{ $transaction->transaction_id }}</td>
        </tr>
        <tr>
            <td colspan="2">Plat: {{ $transaction->customer->license_plate }}</td>
        </tr>
    </table>

    <div class="border-top"></div>

    <table>
        @foreach ($transaction->salesDetails as $item)
            <tr>
                <td colspan="3">{{ $item->sparepart->sparepart_name }}</td>
            </tr>
            <tr>
                <td>{{ $item->jumlah }} x {{ number_format($item->harga_satuan) }}</td>
                <td class="text-end">{{ number_format($item->sub_total) }}</td>
            </tr>
        @endforeach

        @if ($transaction->total_jasa > 0)
            <tr>
                <td>Jasa Servis</td>
                <td class="text-end">{{ number_format($transaction->total_jasa) }}</td>
            </tr>
        @endif
    </table>

    <div class="border-top"></div>

    <table>
        <tr>
            <td>Subtotal</td>
            <td class="text-end">{{ number_format($transaction->total_sparepart + $transaction->total_jasa) }}</td>
        </tr>
        @if ($transaction->diskon > 0)
            <tr>
                <td>Diskon</td>
                <td class="text-end">-{{ number_format($transaction->diskon) }}</td>
            </tr>
        @endif
        <tr>
            <td class="fw-bold" style="font-size: 14px;">TOTAL</td>
            <td class="text-end fw-bold" style="font-size: 14px;">{{ number_format($transaction->total_akhir) }}</td>
        </tr>
    </table>

    <div class="border-top"></div>
    <div class="text-center" style="margin-top: 10px;">
        <p>Terima Kasih<br>Selamat Jalan</p>
    </div>

    <div class="text-center no-print" style="margin-top: 20px;">
        <a href="{{ route('payments.pending') }}"
            style="text-decoration: none; background: #000; color: #fff; padding: 5px 10px; border-radius: 5px;">Kembali
            ke Dashboard</a>
    </div>

</body>

</html>
