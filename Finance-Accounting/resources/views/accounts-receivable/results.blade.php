@extends('layouts.app')

@section('content')

<div class="p-6">
    <h2 class="text-3xl font-bold mb-6">
        Accounts Receivable Report
    </h2>

    <div class="flex justify-between items-center mb-6">

    <h2 class="text-3xl font-bold text-[#213D8F]">
        {{ $reportType }}
    </h2>

    <div class="flex gap-3">

    <a href="{{ route('dashboard') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back
        </a>


        <button
            onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

            <i class="fa-solid fa-print mr-2"></i>

            Print

        </button>
<form action="{{ route('receivable.report.export.pdf') }}" method="GET">

    <input type="hidden" name="report_type" value="{{ $reportType }}">

    <input type="hidden" name="customer_id" value="{{ request('customer_id') }}">

    <input type="hidden" name="date_from" value="{{ request('date_from') }}">

    <input type="hidden" name="date_to" value="{{ request('date_to') }}">

    <button
        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg">

        <i class="fa-solid fa-file-pdf mr-2"></i>

        Export PDF

    </button>

</form>

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
        @forelse($invoices as $invoice)
            <tr>
                <td class="border p-2">{{ $invoice->invoice_number }}</td>
                <td class="border p-2">{{ $invoice->customer->customer_name ?? '—' }}</td>
                <td class="border p-2">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                <td class="border p-2">₱{{ number_format($invoice->total,2) }}</td>
                <td class="border p-2">₱{{ number_format($invoice->balance,2) }}</td>
                <td class="border p-2">{{ $invoice->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="border p-4 text-center text-gray-400">
                    No invoices match this report's filters.
                </td>
            </tr>
        @endforelse
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
</div>

@endsection