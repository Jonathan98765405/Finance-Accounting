<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #2c2c2c;
            font-size: 12px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #243f90;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #243f90;
        }
        .muted {
            color: #7A8899;
            font-size: 10.5px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            color: #243f90;
            margin-bottom: 4px;
        }
        table.info {
            width: 100%;
            margin-bottom: 20px;
        }
        table.info td {
            vertical-align: top;
            padding: 2px 0;
        }
        table.line-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.line-items th {
            background: #f0f3fa;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            border-bottom: 1px solid #dfe6ef;
            color: #243f90;
        }
        table.line-items td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        .text-right { text-align: right; }
        .total-row td {
            font-weight: bold;
            font-size: 13px;
            color: #18B876;
            border-top: 2px solid #243f90;
        }
        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #9AA4B5;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-name">Company Inc.</div>
        <div class="muted">123 Business Ave, Makati City, Metro Manila | (02) 2373-2713</div>
    </div>

    <div class="title">Remittance Advice</div>
    <div class="muted" style="margin-bottom:16px;">Reference No. <?php echo e($payment->remittance_number); ?></div>

    <table class="info">
        <tr>
            <td width="50%">
                <strong>Paid To:</strong><br>
                <?php echo e($payment->invoice->supplier->name); ?><br>
                <?php echo e($payment->invoice->supplier->address); ?><br>
                <?php echo e($payment->invoice->supplier->email); ?>

            </td>
            <td width="50%">
                <strong>Payment Date:</strong> <?php echo e($payment->payment_date?->format('M d, Y')); ?><br>
                <strong>Payment Method:</strong> <?php echo e($payment->payment_method); ?><br>
                <?php if($payment->bank_account): ?>
                    <strong>Bank Account:</strong> <?php echo e($payment->bank_account); ?><br>
                <?php endif; ?>
                <strong>Reference No.:</strong> <?php echo e($payment->reference_number); ?>

            </td>
        </tr>
    </table>

    <table class="line-items">
        <thead>
            <tr>
                <th>Invoice No.</th>
                <th>Invoice Date</th>
                <th>Due Date</th>
                <th class="text-right">Amount Paid</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo e($payment->invoice->invoice_number); ?></td>
                <td><?php echo e($payment->invoice->invoice_date?->format('M d, Y')); ?></td>
                <td><?php echo e($payment->invoice->due_date?->format('M d, Y')); ?></td>
                <td class="text-right"><?php echo e($payment->invoice->currency); ?> <?php echo e(number_format((float) $payment->amount, 2)); ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="3">Total Paid</td>
                <td class="text-right"><?php echo e($payment->invoice->currency); ?> <?php echo e(number_format((float) $payment->amount, 2)); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        This is a system-generated remittance advice. Please retain this document for your records.
    </div>

</body>
</html><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/pdf/remittance-advice.blade.php ENDPATH**/ ?>