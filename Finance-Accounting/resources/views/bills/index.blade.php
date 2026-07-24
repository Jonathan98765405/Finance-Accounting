<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Synced Bills (AP)</title>
    <style>
        body { font-family: -apple-system, Segoe UI, Roboto, Arial, sans-serif; background: #f3f5f9; margin: 0; padding: 40px; color: #1a1f36; }
        .wrap { max-width: 1100px; margin: 0 auto; }
        h1 { font-size: 28px; margin-bottom: 4px; }
        p.sub { color: #6b7280; margin-top: 0; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        th { text-align: left; background: #f9fafb; color: #6b7280; font-size: 12px; text-transform: uppercase; padding: 12px 16px; border-bottom: 1px solid #e5e7eb; }
        td { padding: 14px 16px; border-bottom: 1px solid #f0f1f3; font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .status-pending_payment { background: #fef3c7; color: #92400e; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-sent_to_ap { background: #e0e7ff; color: #3730a3; }
        a.view-link { color: #4f46e5; text-decoration: none; font-weight: 600; font-size: 13px; }
        a.view-link:hover { text-decoration: underline; }
        .empty { padding: 60px; text-align: center; color: #9ca3af; background: #fff; border-radius: 10px; }
        .pagination { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Synced Bills</h1>
        <p class="sub">Purchase orders synced in from Procurement, landed in Accounts Payable.</p>

        @if ($bills->isEmpty())
            <div class="empty">No bills have been synced yet.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>PO Number</th>
                        <th>Vendor ID</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Synced At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bills as $bill)
                        <tr>
                            <td><strong>{{ $bill->po_number }}</strong></td>
                            <td>{{ $bill->vendor_id }}</td>
                            <td>{{ number_format($bill->total_amount, 2) }}</td>
                            <td><span class="status status-{{ $bill->status }}">{{ str_replace('_', ' ', $bill->status) }}</span></td>
                            <td>{{ $bill->ap_synced_at?->format('M j, Y g:i A') ?? '—' }}</td>
                            <td><a class="view-link" href="{{ route('bills.show', $bill) }}">View items →</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                {{ $bills->links() }}
            </div>
        @endif
    </div>
</body>
</html>