@extends('layouts.app')

@section('title', 'Sales Invoices')

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Sales Invoices</h1>
            <p class="text-sm text-slate-500 mt-1">Manage and view all sales invoices.</p>
        </div>

        <a href="{{ route('sales-invoices.create') }}" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-lg px-4 py-2.5 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Invoice
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Invoice No.</th>
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Invoice Date</th>
                        <th class="px-5 py-3 font-medium">Due Date</th>
                        <th class="px-5 py-3 font-medium">Total Amount</th>
                        <th class="px-5 py-3 font-medium">Payment Status</th>
                        <th class="px-5 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($invoices as $invoice)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-medium text-slate-900">{{ $invoice->invoice_no }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $invoice->customer->name }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $invoice->invoice_date->format('F j, Y') }}</td>
                            <td class="px-5 py-3.5 text-slate-600">
                             {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') : '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-900 font-medium">{{ $invoice->formatted_amount }}</td>
                            <td class="px-5 py-3.5">
                                @if ($invoice->payment_status === 'Paid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Paid</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Unpaid</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('sales-invoices.edit', $invoice) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-slate-100 transition" aria-label="Edit {{ $invoice->invoice_no }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('sales-invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Delete invoice {{ $invoice->invoice_no }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition" aria-label="Delete {{ $invoice->invoice_no }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7h12z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400">No invoices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-5 py-4 border-t border-slate-200">
            {{ $invoices->onEachSide(1)->links() }}
        </div>
    </div>

@endsection