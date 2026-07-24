<?php

namespace App\Exports\AccountReceivable;

use App\Models\AccountReceivable\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgingreportExcel implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Invoice::with('customer')
            ->where('balance', '>', 0)
            ->get()
            ->map(function ($invoice) {
                return [
                    'Invoice No' => $invoice->invoice_number,
                    'Customer'   => $invoice->customer->customer_name,
                    'Invoice Date' => $invoice->invoice_date,
                    'Due Date'     => $invoice->due_date,
                    'Balance'      => $invoice->balance,
                    'Status'       => $invoice->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Invoice No',
            'Customer',
            'Invoice Date',
            'Due Date',
            'Balance',
            'Status',
        ];
    }
}