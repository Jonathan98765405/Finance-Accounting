<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Aging Report</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th,
        table td{
            border:1px solid #000;
            padding:8px;
        }

        table th{
            background:#f2f2f2;
        }

        .summary{
            margin-top:25px;
        }
    </style>
</head>

<body>

<h2>Accounts Receivable Aging Report</h2>

<table>
    <thead>
        <tr>
            <th>Invoice No.</th>
            <th>Customer</th>
            <th>Due Date</th>
            <th>Balance</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

    @foreach($receivables as $invoice)

        <tr>
            <td>{{ $invoice->invoice_number }}</td>
            <td>{{ $invoice->customer->customer_name }}</td>
            <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
            <td>₱{{ number_format($invoice->balance,2) }}</td>
            <td>{{ $invoice->status }}</td>
        </tr>

    @endforeach

    </tbody>

</table>

<div class="summary">

    <h3>Summary</h3>

    <p><strong>Total Outstanding:</strong>
        ₱{{ number_format($totalOutstanding,2) }}
    </p>

    <p><strong>Current (0-30 Days):</strong>
        ₱{{ number_format($current,2) }}
    </p>

    <p><strong>31-60 Days:</strong>
        ₱{{ number_format($days31_60,2) }}
    </p>

    <p><strong>61-90 Days:</strong>
        ₱{{ number_format($days61_90,2) }}
    </p>

    <p><strong>90+ Days:</strong>
        ₱{{ number_format($over90,2) }}
    </p>

</div>

</body>
</html>