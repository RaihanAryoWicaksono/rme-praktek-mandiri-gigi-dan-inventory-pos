@php
    $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->transaction_number }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 13px; color: #000; margin: 0; padding: 10px; }
        .receipt { width: 302px; margin: 0 auto; } /* approx 80mm */
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 16px; margin: 0; text-transform: uppercase; }
        .header p { font-size: 11px; margin: 2px 0; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .item-row { display: flex; justify-content: space-between; margin: 3px 0; align-items: flex-start; }
        .item-name { flex: 1; padding-right: 10px; }
        .item-price { text-align: right; white-space: nowrap; }
        .total-section { margin-top: 8px; }
        .total-row { display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 2px; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; }
        .payment-info { margin-top: 10px; font-size: 11px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .receipt { width: 80mm; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #4f46e5; color: white; border: none; border-radius: 5px;">CETAK STRUK</button>
    </div>

    <div class="receipt">
        <div class="header">
            <h1>{{ $settings['clinic_name'] ?? 'KLINIK GIGI' }}</h1>
            <p>{{ $settings['clinic_address'] ?? '-' }}</p>
            <p>Telp: {{ $settings['clinic_phone'] ?? '-' }}</p>
        </div>

        <div class="divider"></div>

        <div>
            <p>No: {{ $transaction->transaction_number }}</p>
            <p>Tgl: {{ $transaction->date->format('d/m/Y H:i') }}</p>
            <p>Pasien: {{ $transaction->patient->name }}</p>
        </div>

        <div class="divider"></div>

        <div class="items">
            @foreach($transaction->treatments as $tr)
                <div class="item-row">
                    <span class="item-name">{{ $tr->treatment->name }}</span>
                    <span class="item-price">{{ number_format($tr->price, 0, ',', '.') }}</span>
                </div>
            @endforeach
            @foreach($transaction->items->where('quantity', '>', 0) as $item)
                <div class="item-row">
                    <span class="item-name">{{ $item->item->name }} ({{ (float)$item->quantity }} {{ $item->item->unit_use }})</span>
                    <span class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <div class="total-section">
            <div class="total-row">
                <span>SUBTOTAL</span>
                <span>{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount > 0)
            <div class="total-row">
                <span>DISKON</span>
                <span>-{{ number_format($transaction->discount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row" style="font-size: 16px; margin-top: 5px; border-top: 1px solid #000; padding-top: 5px;">
                <span>TOTAL AKHIR</span>
                <span>{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="payment-info">
            <div class="total-row" style="font-weight: normal;">
                <span>Metode Bayar:</span>
                <span style="text-transform: uppercase;">{{ $transaction->payment_method }}</span>
            </div>
            <div class="total-row" style="font-weight: normal;">
                <span>Bayar:</span>
                <span>{{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
            </div>
            <div class="total-row" style="font-weight: normal;">
                <span>Kembalian:</span>
                <span>{{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda.</p>
            <p>Semoga lekas sembuh.</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Optional: trigger print automatically
            // window.print();
        }
    </script>
</body>
</html>
