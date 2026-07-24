
<div class="p-6">
    <h2 class="text-3xl font-bold mb-6">
        Accounts Receivable Report
    </h2>

    <div class="flex justify-between items-center mb-6">

    <h2 class="text-3xl font-bold text-[#213D8F]">
        {{ $reportType }}
    </h2>

    <div class="flex gap-3">
    </div>

</div>
<div class="bg-white rounded-2xl ">
    <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
    <tr>
        <th class="px-5 py-3 border border-gray-200 text-left">Invoice No.</th>
        <th class="px-5 py-3 border border-gray-200 text-left">Customer</th>
        <th class="px-5 py-3 border border-gray-200 text-left">Date</th>
        <th class="px-5 py-3 border border-gray-200 text-left">Total</th>
        <th class="px-5 py-3 border border-gray-200 text-left">Balance</th>
        <th class="px-5 py-3 border border-gray-200 text-left">Status</th>
    </tr>
</thead>
 

        <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td class="border p-2">{{ $invoice->invoice_number }}</td>
                <td class="border p-2">{{ $invoice->customer->customer_name }}</td>
                <td class="border p-2">{{ $invoice->invoice_date }}</td>
                <td class="border p-2">₱{{ number_format($invoice->total,2) }}</td>
                <td class="border p-2">₱{{ number_format($invoice->balance,2) }}</td>
                <td class="border p-2">{{ $invoice->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
   @if($reportType == 'Accounts Receivable Summary')

<div class="mt-8 bg-gray-50 border rounded-lg p-6">

    <h3 class="text-xl font-bold text-[#213D8F] mb-4">
        Summary
    </h3>

    <p><strong>Total Invoiced:</strong>
        ₱{{ number_format($invoices->sum('total'),2) }}
    </p>

    <p><strong>Total Outstanding:</strong>
        ₱{{ number_format($invoices->sum('balance'),2) }}
    </p>

    <p><strong>Number of Invoices:</strong>
        {{ $invoices->count() }}
    </p>

</div>

@elseif($reportType == 'Outstanding Invoices')

<div class="mt-8 bg-gray-50 border rounded-lg p-6">

    <h3 class="text-xl font-bold text-[#213D8F] mb-4">
        Outstanding Summary
    </h3>

    <p><strong>Total Outstanding Invoices:</strong>
        {{ $invoices->count() }}
    </p>

    <p><strong>Total Outstanding Balance:</strong>
        ₱{{ number_format($invoices->sum('balance'),2) }}
    </p>

    <p><strong>Overdue Invoices:</strong>
        {{ $invoices->where('status','Overdue')->count() }}
    </p>

    <p><strong>Unpaid Invoices:</strong>
        {{ $invoices->where('status','Unpaid')->count() }}
    </p>

</div>

@elseif($reportType == 'Customer Statement')

<div class="mt-8 bg-gray-50 border rounded-lg p-6">

    <h3 class="text-xl font-bold text-[#213D8F] mb-4">
        Customer Statement Summary
    </h3>

    <p><strong>Total Invoiced:</strong>
        ₱{{ number_format($invoices->sum('total'),2) }}
    </p>

    <p><strong>Total Outstanding:</strong>
        ₱{{ number_format($invoices->sum('balance'),2) }}
    </p>

    <p><strong>Number of Invoices:</strong>
        {{ $invoices->count() }}
    </p>

</div>

@elseif($reportType == 'Collection Report')

<div class="mt-8 bg-gray-50 border rounded-lg p-6">

    <h3 class="text-xl font-bold text-[#213D8F] mb-4">
        Collection Summary
    </h3>

    <p><strong>Paid Invoices:</strong>
        {{ $invoices->count() }}
    </p>

    <p><strong>Total Collections:</strong>
        ₱{{ number_format($invoices->sum('total'),2) }}
    </p>

</div>

@endif
