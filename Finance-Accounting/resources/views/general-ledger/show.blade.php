@extends('layouts.app')

@section('page-title', 'View Journal Entry')
@section('page-title-heading', 'Journal Entry Details')
@section('page-subtitle', 'View transaction information.')

@section('content')

<div class="max-w-5xl">
    <div class="bg-white rounded-2xl shadow-card border border-slate-200 p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-navy">Reference: {{ $entry->reference }}</h2>
                <p class="text-slate-500">Journal entry transaction details</p>
            </div>
            <a href="{{ route('ledger.index') }}" class="bg-slate-200 hover:bg-slate-300 px-5 py-2 rounded-xl">
                ← Back
            </a>
        </div>

        <div class="grid grid-cols-3 gap-5 mb-8">
            <div>
                <p class="text-slate-500">Date</p>
                <h3 class="font-bold">{{ \Carbon\Carbon::parse($entry->entry_date)->format('Y-m-d') }}</h3>
            </div>
            <div>
                <p class="text-slate-500">Reference</p>
                <h3 class="font-bold">{{ $entry->reference }}</h3>
            </div>
            <div>
                <p class="text-slate-500">Status</p>
                <h3 class="font-bold text-brand-green">{{ $entry->status }}</h3>
            </div>
        </div>

        <h2 class="text-xl font-bold mb-4 text-navy">Journal Lines</h2>
        <table class="w-full">
            <thead>
                <tr class="bg-slate-100">
                    <th class="p-4 text-left">Account</th>
                    <th class="p-4 text-right">Debit</th>
                    <th class="p-4 text-right">Credit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entry->lines as $line)
                    <tr class="border-b">
                        <td class="p-4">{{ $line->account->account_name }}</td>
                        <td class="p-4 text-right">₱{{ number_format($line->debit, 2) }}</td>
                        <td class="p-4 text-right">₱{{ number_format($line->credit, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection