@extends('layouts.app')

@section('page-title', 'Trial Balance')
@section('page-title-heading', 'Trial Balance')
@section('page-subtitle', 'Debit and credit balances across all accounts.')

@section('content')

@php
    // Dynamically sum up values passed from the controller
    $totalDebit = array_sum(array_column($trialBalance, 'debit'));
    $totalCredit = array_sum(array_column($trialBalance, 'credit'));
@endphp

<div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 p-8 mt-4">
    <div class="mb-8">
        <a href="{{ route('ledger.index') }}" class="text-navy font-bold hover:text-brand-blue transition-colors inline-flex items-center gap-2 hover:-translate-x-1 duration-300">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to General Ledger
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase font-extrabold tracking-widest text-slate-500 border-b border-slate-200/80">
                <tr>
                    <th class="p-6">Account Code</th>
                    <th class="p-6">Account Name</th>
                    <th class="p-6 text-right">Debit</th>
                    <th class="p-6 text-right">Credit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($trialBalance as $row)
                    <tr class="hover:bg-slate-50 transition-colors duration-200">
                        <td class="p-6 font-mono font-medium text-slate-400">{{ $row['account_code'] }}</td>
                        <td class="p-6 font-bold text-slate-800">{{ $row['account_name'] }}</td>
                        
                        <td class="p-6 text-right font-medium {{ $row['debit'] > 0 ? 'text-slate-800' : 'text-slate-300' }}">
                            {{ $row['debit'] > 0 ? '₱' . number_format($row['debit'], 2) : '—' }}
                        </td>
                        
                        <td class="p-6 text-right font-medium {{ $row['credit'] > 0 ? 'text-slate-800' : 'text-slate-300' }}">
                            {{ $row['credit'] > 0 ? '₱' . number_format($row['credit'], 2) : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-500 bg-slate-50/30">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="folder-open" class="w-8 h-8 text-slate-300 mb-2"></i>
                                <p class="font-medium">No account balances yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

            @if(count($trialBalance) > 0)
                <tfoot class="bg-slate-50/50 border-t border-slate-200">
                    <tr class="font-bold text-slate-800">
                        <td colspan="2" class="p-6 text-right uppercase tracking-wider text-xs text-slate-400 font-extrabold">Total Balance</td>
                        <td class="p-6 text-right text-base text-slate-900 font-extrabold border-b-4 border-double border-slate-900">
                            ₱{{ number_format($totalDebit, 2) }}
                        </td>
                        <td class="p-6 text-right text-base text-slate-900 font-extrabold border-b-4 border-double border-slate-900">
                            ₱{{ number_format($totalCredit, 2) }}
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection