@extends('layouts.app')

@section('page-title', 'Chart of Accounts')
@section('page-title-heading', 'Chart of Accounts')
@section('page-subtitle', 'Full list of available general ledger accounts.')

@section('content')

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
                    <th class="p-6">Account Type</th>
                    <th class="p-6">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($accounts as $account)
                    <tr class="hover:bg-slate-50 transition-colors duration-200">
                        <td class="p-6 font-mono font-medium text-slate-400">{{ $account->account_code }}</td>
                        <td class="p-6 font-bold text-slate-800">{{ $account->account_name }}</td>
                        <td class="p-6 font-medium text-slate-600">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider">
                                {{ $account->account_type }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="inline-flex items-center gap-2 bg-brand-green/10 text-brand-greenDark px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-widest border border-brand-green/20">
                                <div class="w-1.5 h-1.5 rounded-full bg-brand-green"></div>
                                {{ $account->status }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-500 bg-slate-50/30">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="folder-open" class="w-8 h-8 text-slate-300 mb-2"></i>
                                <p class="font-medium">No accounts found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection