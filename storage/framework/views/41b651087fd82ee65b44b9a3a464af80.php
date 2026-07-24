<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; color:#3D4658; margin:0; padding:0; background:#f5f7fb;">

    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; margin:30px auto; background:#fff; border-radius:12px; overflow:hidden;">

        <tr>
            <td style="background:#243f90; padding:24px 30px;">
                <span style="color:#fff; font-size:20px; font-weight:700;">Remittance Advice</span>
            </td>
        </tr>

        <tr>
            <td style="padding:30px;">

                <p style="font-size:14px; line-height:1.6;">
                    Dear <?php echo e($payment->invoice->supplier->name); ?>,
                </p>

                <p style="font-size:14px; line-height:1.6; white-space:pre-line;"><?php echo e($messageBody); ?></p>

                <table width="100%" cellpadding="8" style="background:#f5f7fb; border-radius:10px; margin:20px 0; font-size:13px;">
                    <tr>
                        <td style="color:#96A1B4;">Invoice Number</td>
                        <td style="font-weight:700;"><?php echo e($payment->invoice->invoice_number); ?></td>
                    </tr>
                    <tr>
                        <td style="color:#96A1B4;">Payment Date</td>
                        <td style="font-weight:700;"><?php echo e($payment->payment_date?->format('M d, Y')); ?></td>
                    </tr>
                    <tr>
                        <td style="color:#96A1B4;">Amount Paid</td>
                        <td style="font-weight:700; color:#18B876;"><?php echo e($payment->invoice->currency); ?> <?php echo e(number_format((float) $payment->amount, 2)); ?></td>
                    </tr>
                    <tr>
                        <td style="color:#96A1B4;">Reference No.</td>
                        <td style="font-weight:700;"><?php echo e($payment->remittance_number); ?></td>
                    </tr>
                </table>

                <p style="font-size:13px; color:#7A8899;">
                    The full remittance advice is attached to this email as a PDF.
                </p>

                <p style="font-size:14px; margin-top:24px;">
                    Regards,<br>
                    Accounts Payable Team
                </p>

            </td>
        </tr>

    </table>

</body>
</html><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/emails/remittance-advice.blade.php ENDPATH**/ ?>