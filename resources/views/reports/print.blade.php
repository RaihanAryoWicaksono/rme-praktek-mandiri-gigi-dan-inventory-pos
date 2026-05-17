<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.4; padding: 20px; max-width: 800px; margin: auto; }
        h1, h2, h3 { text-align: center; margin-bottom: 5px; }
        .period { text-align: center; color: #666; margin-bottom: 30px; }
        
        .summary-box { border: 1px solid #ddd; padding: 15px; margin-bottom: 30px; background: #fdfdfd; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 16px; }
        .summary-row.total { font-weight: bold; font-size: 20px; border-top: 2px solid #ccc; padding-top: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #eee; padding: 8px 12px; text-align: left; font-size: 14px; }
        th { background: #f9f9f9; font-weight: bold; }
        .text-right { text-align: right; }
        .text-red { color: #d9534f; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer;">Print / Save in PDF</button>
    </div>

    <h1>KLINIK GIGI MEDIKA</h1>
    <h2>Laporan Keuangan & Laba Bersih</h2>
    <div class="period">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>

    <div class="summary-box">
        <div class="summary-row">
            <span>Total Pendapatan Transaksi:</span>
            <span>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row text-red">
            <span>Total Pengeluaran & Stok Masuk:</span>
            <span>Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row total">
            <span>Laba Bersih (Net Profit):</span>
            <span>Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
        </div>
    </div>

    <h3>Rincian Pengeluaran</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan / Nama</th>
                <th>Deskripsi</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $exp)
            <tr>
                <td>{{ \Carbon\Carbon::parse($exp->date)->format('d/m/Y') }}</td>
                <td>{{ $exp->name }}</td>
                <td>{{ $exp->description }}</td>
                <td class="text-right text-red">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @if($expenses->isEmpty())
            <tr><td colspan="4" style="text-align: center;">Tidak ada pengeluraran.</td></tr>
            @endif
        </tbody>
    </table>

    <div style="page-break-before: auto;"></div>

    <h3>Rincian Pendapatan Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No. TRX</th>
                <th>Pasien</th>
                <th>Pembayaran</th>
                <th class="text-right">Total Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $idx => $trx)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $trx->date->format('d/m/Y') }}</td>
                <td>{{ $trx->transaction_number }}</td>
                <td>{{ $trx->patient->name }}</td>
                <td>{{ strtoupper($trx->payment_method) }}</td>
                <td class="text-right">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @if($transactions->isEmpty())
            <tr><td colspan="6" style="text-align: center;">Tidak ada transaksi.</td></tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right; font-size: 14px;">
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

</body>
</html>
