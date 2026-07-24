@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @php
        $colorMap = [
            'blue'   => 'bg-blue-100 text-blue-600',
            'green'  => 'bg-emerald-100 text-emerald-600',
            'amber'  => 'bg-amber-100 text-amber-600',
            'purple' => 'bg-violet-100 text-violet-600',
        ];
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Overview of your sales and customer support activities.</p>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ($stats as $stat)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $colorMap[$stat['color']] ?? $colorMap['blue'] }}">
                    @switch($stat['icon'])
                        @case('users')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-2a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-4-4H7a4 4 0 00-4 4v2h14v-2z" />
                            </svg>
                            @break
                        @case('document')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            @break
                        @case('peso')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-3-8h4.5a2 2 0 010 4H9m0 0h6M9 12H6m3 4h3" />
                            </svg>
                            @break
                        @case('check')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @break
                    @endswitch
                </div>

                <div class="min-w-0">
                    <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $stat['value'] }}</p>
                    <p class="text-xs text-emerald-600 font-medium mt-1">{{ $stat['change'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Recent sales invoices --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
            <h2 class="font-semibold text-slate-900">Recent Sales Invoices</h2>
            <a href="{{ route('sales-invoices.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-500 flex items-center gap-1">
                View all invoices
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Invoice No.</th>
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Invoice Date</th>
                        <th class="px-5 py-3 font-medium">Total Amount</th>
                        <th class="px-5 py-3 font-medium">Payment Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($invoices as $invoice)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-medium text-slate-900">{{ $invoice->invoice_no }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $invoice->customer->name }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $invoice->invoice_date->format('F j, Y') }}</td>
                            <td class="px-5 py-3.5 text-slate-900 font-medium">{{ $invoice->formatted_amount }}</td>
                            <td class="px-5 py-3.5">
                                @if ($invoice->payment_status === 'Paid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Paid</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Unpaid</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">No invoices to show yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection