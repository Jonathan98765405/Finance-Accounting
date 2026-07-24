<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill {{ $bill->po_number }}</title>
    <style>
        body { font-family: -apple-system, Segoe UI, Roboto, Arial, sans-serif; background: #f3f5f9; margin: 0; padding: 40px; color: #1a1f36; }
        .wrap { max-width: 900px; margin: 0 auto; }
        a.back { color: #4f46e5; text-decoration: none; font-size: 14px; font-weight: 600; }
        a.back:hover { text-decoration: underline; }
        h1 { font-size: 26px; margin: 16px 0 20px; }
        .card { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 24px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field label { display: block; font-size: 12px; color: #6b7280; text-transform: uppercase; margin-bottom: 4px; }
        .field div { font-size: 15px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #f9fafb; color: #6b7280; font-size: 12px; text-transform: uppercase; padding: 10px 14px; border-bottom: 1px solid #e5e7eb; }
        td { padding: 12px 14px; border-bottom: 1px solid #f0f1f3; font-size: 14px; }
        tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>
    <div class="wrap">
        <a class="back" href="{{ route('bills.index') }}">← Back to Bills</a>
        <h1>{{ $bill->po_number }}</h1>

        <div class="card">
            <div class="grid">
                <div class="field"><label>Vendor ID</label><div>{{ $bill->vendor_id }}</div></div>
                <div class="field"><label>Status</label><div>{{ str_replace('_', ' ', $bill->status) }}</div></div>
                <div class="field"><label>Total Amount</label><div>{{ number_format($bill->total_amount, 2) }}</div></div>
                <div class="field"><label>Synced At</label><div>{{ $bill->ap_synced_at?->format('M j, Y g:i A') ?? '—' }}</div></div>
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bill->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>