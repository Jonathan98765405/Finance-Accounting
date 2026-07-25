{{-- resources/views/accounts-receivable/report-partial.blade.php --}}
{{-- No @extends here on purpose: this is only ever fetched via AJAX and
     injected into the report modal, so it must NOT include the full layout. --}}

@if ($invoices->isEmpty())
    <p class="text-sm text-slate-500 py-6 text-center">No records found for the selected filters.</p>
@else
    <div class="mb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
        {{ $reportType ?? 'Accounts Receivable Summary' }} &middot; {{ $invoices->count() }} record(s)
    </div>
    <div class="max-h-72 overflow-auto border border-slate-200 rounded-xl">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 sticky top-0">
                <tr>
                    <th class="text-left px-3 py-2 font-semibold text-slate-600 text-xs uppercase">Invoice #</th>
                    <th class="text-left px-3 py-2 font-semibold text-slate-600 text-xs uppercase">Customer</th>
                    <th class="text-left px-3 py-2 font-semibold text-slate-600 text-xs uppercase">Invoice Date</th>
                    <th class="text-left px-3 py-2 font-semibold text-slate-600 text-xs uppercase">Due Date</th>
                    <th class="text-left px-3 py-2 font-semibold text-slate-600 text-xs uppercase">Total</th>
                    <th class="text-left px-3 py-2 font-semibold text-slate-600 text-xs uppercase">Balance</th>
                    <th class="text-left px-3 py-2 font-semibold text-slate-600 text-xs uppercase">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td class="px-3 py-2 border-t border-slate-100">{{ $invoice->invoice_number }}</td>
                        <td class="px-3 py-2 border-t border-slate-100">{{ optional($invoice->customer)->customer_name }}</td>
                        <td class="px-3 py-2 border-t border-slate-100">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                        <td class="px-3 py-2 border-t border-slate-100">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                        <td class="px-3 py-2 border-t border-slate-100">{{ number_format($invoice->total, 2) }}</td>
                        <td class="px-3 py-2 border-t border-slate-100">{{ number_format($invoice->balance, 2) }}</td>
                        <td class="px-3 py-2 border-t border-slate-100">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                @class([
                                    'bg-green-100 text-green-700' => $invoice->status === 'Paid',
                                    'bg-amber-100 text-amber-700' => $invoice->status === 'Partial',
                                    'bg-red-100 text-red-700' => $invoice->status === 'Overdue',
                                    'bg-slate-100 text-slate-600' => $invoice->status === 'Unpaid',
                                ])">
                                {{ $invoice->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif