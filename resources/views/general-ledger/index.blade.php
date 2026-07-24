{{-- Bergado 3:34 --}}
@extends('layouts.app')

@section('page-title', 'General Ledger')
@section('page-title-heading', 'General Ledger')
@section('page-subtitle', 'Monitor journal entries, balances, accounts and financial activities.')

@section('content')
@if(session('success'))
    <div class="bg-brand-green/10 border border-brand-green/20 text-brand-greenDark px-6 py-4 rounded-2xl mb-8 flex items-center shadow-sm">
        <div class="bg-brand-green/20 p-2 rounded-full mr-4">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
        </div>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

{{-- VIEW SWITCHER --}}
<div class="mb-8 flex items-center bg-white/80 backdrop-blur p-1.5 rounded-2xl shadow-[0_4px_24px_-8px_rgba(15,23,42,0.06)] border border-slate-100 max-w-fit">
    <label class="font-bold text-slate-400 px-4 text-[11px] uppercase tracking-widest">View Mode</label>
    <div class="relative">
        <select id="ledgerView" onchange="changeLedgerView()" class="appearance-none bg-slate-50 border border-slate-200/80 text-slate-700 rounded-xl pl-5 pr-10 py-2.5 font-semibold text-sm shadow-sm transition-all duration-300 hover:border-slate-300 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-navy-600/10 focus:border-navy cursor-pointer">
            <option value="records">All Records</option>
            <option value="accounts">Accounts</option>
            <option value="balance">Balance</option>
        </select>
        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
    </div>
</div>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.06)] hover:shadow-[0_12px_32px_-8px_rgba(47,76,221,0.18)] transition-all duration-300 hover:-translate-y-1 p-7 border border-slate-100 flex flex-col justify-between group relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-blue to-brand-blue/30 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-1.5">Total Assets</p>
                <h2 class="text-3xl font-black text-brand-blue tracking-tight">₱{{ number_format($totalAssets, 2) }}</h2>
            </div>
            <div class="p-3 bg-brand-blue/10 rounded-2xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <i data-lucide="wallet" class="w-6 h-6 text-brand-blue"></i>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 opacity-[0.03] transform group-hover:scale-110 transition-transform duration-500 pointer-events-none">
            <i data-lucide="wallet" class="w-32 h-32 text-brand-blue"></i>
        </div>
    </div>

    <div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.06)] hover:shadow-[0_12px_32px_-8px_rgba(239,75,75,0.18)] transition-all duration-300 hover:-translate-y-1 p-7 border border-slate-100 flex flex-col justify-between group relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-red to-brand-red/30 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-1.5">Liabilities</p>
                <h2 class="text-3xl font-black text-brand-red tracking-tight">₱{{ number_format($totalLiabilities, 2) }}</h2>
            </div>
            <div class="p-3 bg-brand-red/10 rounded-2xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <i data-lucide="credit-card" class="w-6 h-6 text-brand-red"></i>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 opacity-[0.03] transform group-hover:scale-110 transition-transform duration-500 pointer-events-none">
            <i data-lucide="credit-card" class="w-32 h-32 text-brand-red"></i>
        </div>
    </div>

    <div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.06)] hover:shadow-[0_12px_32px_-8px_rgba(31,203,136,0.18)] transition-all duration-300 hover:-translate-y-1 p-7 border border-slate-100 flex flex-col justify-between group relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-green to-brand-green/30 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-1.5">Equity</p>
                <h2 class="text-3xl font-black text-brand-green tracking-tight">₱{{ number_format($totalEquity, 2) }}</h2>
            </div>
            <div class="p-3 bg-brand-green/10 rounded-2xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <i data-lucide="landmark" class="w-6 h-6 text-brand-green"></i>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 opacity-[0.03] transform group-hover:scale-110 transition-transform duration-500 pointer-events-none">
            <i data-lucide="landmark" class="w-32 h-32 text-brand-green"></i>
        </div>
    </div>

    <div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.06)] hover:shadow-[0_12px_32px_-8px_rgba(245,148,31,0.18)] transition-all duration-300 hover:-translate-y-1 p-7 border border-slate-100 flex flex-col justify-between group relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand-orange to-brand-orange/30 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-1.5">Net Income</p>
                <h2 class="text-3xl font-black text-brand-orange tracking-tight">₱{{ number_format($netIncome, 2) }}</h2>
            </div>
            <div class="p-3 bg-brand-orange/10 rounded-2xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <i data-lucide="trending-up" class="w-6 h-6 text-brand-orange"></i>
            </div>
        </div>
        <div class="absolute -bottom-6 -right-6 opacity-[0.03] transform group-hover:scale-110 transition-transform duration-500 pointer-events-none">
            <i data-lucide="trending-up" class="w-32 h-32 text-brand-orange"></i>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('ledger.index') }}" class="mb-6">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_24px_-8px_rgba(15,23,42,0.05)] p-4 flex flex-col xl:flex-row xl:items-center gap-3">

        {{-- Left Side --}}
        <div class="flex flex-wrap gap-3">
            <div class="relative">
                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="appearance-none border border-slate-200 rounded-xl pl-4 pr-9 py-2.5 text-sm font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-navy-600/10 focus:border-navy transition-all cursor-pointer">
                    <option value="all">All Records</option>
                    <option value="Posted" {{ request('status') == 'Posted' ? 'selected' : '' }}>Posted</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>

            <div class="relative">
                <select
                    name="account_id"
                    onchange="this.form.submit()"
                    class="appearance-none border border-slate-200 rounded-xl pl-4 pr-9 py-2.5 text-sm font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-navy-600/10 focus:border-navy transition-all cursor-pointer">
                    <option value="">All Accounts</option>
                    @foreach($accounts as $account)
                        <option
                            value="{{ $account->id }}"
                            {{ request('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->account_name }}
                        </option>
                    @endforeach
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>

            <div class="relative">
                <select
                    name="sort"
                    onchange="this.form.submit()"
                    class="appearance-none border border-slate-200 rounded-xl pl-4 pr-9 py-2.5 text-sm font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-navy-600/10 focus:border-navy transition-all cursor-pointer">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest Journal Entry</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Journal Entry</option>
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>
        </div>

        {{-- Right Side --}}
        <div class="flex gap-3 xl:ml-auto">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search reference or description..."
                    class="border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 w-72 text-sm bg-slate-50 focus:outline-none focus:ring-4 focus:ring-navy-600/10 focus:border-navy focus:bg-white transition-all">
            </div>

            <button class="bg-navy hover:bg-navy-700 text-white px-6 py-2.5 rounded-xl font-semibold text-sm shadow-[0_4px_10px_rgba(22,38,91,0.2)] hover:shadow-[0_6px_15px_rgba(22,38,91,0.3)] transition-all active:scale-95">
                Search
            </button>

            @if((request()->anyFilled(['status', 'account_id', 'search']) && request('status') != 'all') || request('sort') == 'oldest')
                <a href="{{ route('ledger.index') }}"
                    class="flex items-center gap-2 border border-slate-200 text-slate-500 hover:text-brand-red hover:border-brand-red/30 hover:bg-brand-red/5 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all">
                    <i data-lucide="x" class="w-4 h-4"></i> Clear
                </a>
            @endif
        </div>

    </div>
</form>

{{-- RECORDS SECTION --}}
<div id="recordsSection" class="transition-all duration-300 transform opacity-100 translate-y-0">
    <div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.05)] border border-slate-100 overflow-hidden">
        <div class="flex justify-between items-center px-8 py-6 border-b border-slate-100 bg-white">
            <div>
                <h2 class="text-xl font-black text-navy tracking-tight flex items-center gap-2">
                    <div class="p-2 bg-navy/5 rounded-xl text-navy">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    Journal Entries
                </h2>
                <p class="text-slate-500 text-sm mt-1 ml-11">
                    Showing the {{ $entries->count() }} most recent transactions
                    @if((request()->anyFilled(['status', 'account_id', 'search']) && request('status') != 'all') || request('sort') == 'oldest')
                        <span class="text-navy font-semibold">(filtered)</span>
                    @endif
                </p>
            </div>
            
            {{-- PERMISSION CHECK FOR NEW ENTRY BUTTON --}}
            @if(\App\Models\Role::activeRoleCanManageLedger())
                <a href="{{ route('ledger.create') }}" class="bg-navy hover:bg-navy-700 text-white px-6 py-2.5 rounded-2xl font-semibold shadow-[0_4px_10px_rgba(22,38,91,0.2)] hover:shadow-[0_6px_15px_rgba(22,38,91,0.3)] transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> <span class="hidden sm:inline">New Entry</span>
                </a>
            @else
                <button onclick="showAccessDenied()" class="bg-navy hover:bg-navy-700 text-white px-6 py-2.5 rounded-2xl font-semibold shadow-[0_4px_10px_rgba(22,38,91,0.2)] hover:shadow-[0_6px_15px_rgba(22,38,91,0.3)] transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> <span class="hidden sm:inline">New Entry</span>
                </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Date</th>
                        <th class="px-8 py-5">Reference</th>
                        <th class="px-8 py-5">Description</th>
                        <th class="px-8 py-5">Account</th>
                        <th class="px-8 py-5 text-right">Debit</th>
                        <th class="px-8 py-5 text-right">Credit</th>
                        <th class="px-8 py-5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($entries as $entry)
                        @foreach($entry->lines as $line)
                            <tr class="hover:bg-slate-50 transition-colors duration-200 group">
                                <td class="px-8 py-5 whitespace-nowrap text-slate-500 font-medium">{{ \Carbon\Carbon::parse($entry->entry_date)->format('Y-m-d') }}</td>
                                <td class="px-8 py-5 whitespace-nowrap font-bold text-navy">{{ $entry->reference }}</td>
                                <td class="px-8 py-5 font-medium text-slate-700">{{ $entry->description }}</td>
                                <td class="px-8 py-5 font-semibold text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                        {{ $line->account->account_name }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right font-black text-brand-blue">
                                    @if($line->debit > 0) ₱{{ number_format($line->debit, 2) }} @else - @endif
                                </td>
                                <td class="px-8 py-5 text-right font-black text-brand-red">
                                    @if($line->credit > 0) ₱{{ number_format($line->credit, 2) }} @else - @endif
                                </td>
                                <td class="px-8 py-5 text-center whitespace-nowrap">
                                    <div class="flex justify-center items-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        {{-- VIEW IS ALLOWED FOR EVERYONE --}}
                                        <a href="{{ route('ledger.show', $entry->id) }}" class="text-brand-blue hover:text-white hover:bg-brand-blue transition-all bg-brand-blue/10 w-9 h-9 flex items-center justify-center rounded-full">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        
                                        {{-- PERMISSION CHECK FOR EDIT & DELETE --}}
                                        @if(\App\Models\Role::activeRoleCanManageLedger())
                                            <a href="{{ route('ledger.edit', $entry->id) }}" class="text-brand-orange hover:text-white hover:bg-brand-orange transition-all bg-brand-orange/10 w-9 h-9 flex items-center justify-center rounded-full">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </a>
                                            <form action="{{ route('ledger.delete', $entry->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Delete this entry?')" class="text-brand-red hover:text-white hover:bg-brand-red transition-all bg-brand-red/10 w-9 h-9 flex items-center justify-center rounded-full">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" onclick="showAccessDenied()" class="text-brand-orange hover:text-white hover:bg-brand-orange transition-all bg-brand-orange/10 w-9 h-9 flex items-center justify-center rounded-full">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </button>
                                            <button type="button" onclick="showAccessDenied()" class="text-brand-red hover:text-white hover:bg-brand-red transition-all bg-brand-red/10 w-9 h-9 flex items-center justify-center rounded-full">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 flex items-center justify-center rounded-full mb-4">
                                        <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-bold text-slate-600">No Journal Entries Found</p>
                                    <p class="text-sm mt-1">Get started by creating a new entry.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($entries->count() > 0)
            <div class="p-6 bg-slate-50/50 border-t border-slate-100 text-center">
                <a href="{{ route('ledger.alljournal') }}" class="text-navy font-bold hover:text-brand-blue transition-colors inline-flex items-center gap-2 group">
                    View All Journal Entries
                    <div class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center group-hover:bg-brand-blue group-hover:text-white transition-all">
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </div>
                </a>
            </div>
        @endif
    </div>
</div>

{{-- ACCOUNTS SECTION --}}
<div id="accountsSection" class="hidden transition-all duration-300 transform opacity-0 translate-y-4">
    <div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.05)] border border-slate-100 overflow-hidden mt-8">
        <div class="px-8 py-6 border-b border-slate-100 bg-white">
            <h2 class="text-xl font-black text-navy tracking-tight flex items-center gap-2">
                <div class="p-2 bg-navy/5 rounded-xl text-navy">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
                Chart of Accounts
            </h2>
            <p class="text-slate-500 text-sm mt-1 ml-11">Comprehensive list of available accounts</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Code</th>
                        <th class="px-8 py-5">Account Name</th>
                        <th class="px-8 py-5">Type</th>
                        <th class="px-8 py-5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($accounts as $account)
                        <tr class="hover:bg-slate-50 transition-colors duration-200">
                            <td class="px-8 py-5 font-mono text-slate-400 font-medium">{{ $account->account_code }}</td>
                            <td class="px-8 py-5 font-bold text-navy">{{ $account->account_name }}</td>
                            <td class="px-8 py-5 font-medium text-slate-500">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg text-xs font-semibold">
                                    {{ $account->account_type }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="bg-brand-green/10 text-brand-green px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wide border border-brand-green/20">
                                    {{ $account->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-slate-50/50 border-t border-slate-100 text-center">
            <a href="{{ route('ledger.accounts') }}" class="text-navy font-bold hover:text-brand-blue transition-colors inline-flex items-center gap-2 group">
                View All Chart of Accounts
                <div class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center group-hover:bg-brand-blue group-hover:text-white transition-all">
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- BALANCE SECTION --}}
<div id="balanceSection" class="hidden transition-all duration-300 transform opacity-0 translate-y-4">
    <div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.05)] border border-slate-100 overflow-hidden mt-8">
        <div class="px-8 py-6 border-b border-slate-100 bg-white">
            <h2 class="text-xl font-black text-navy tracking-tight flex items-center gap-2">
                <div class="p-2 bg-navy/5 rounded-xl text-navy">
                    <i data-lucide="scale" class="w-5 h-5"></i>
                </div>
                Trial Balance
            </h2>
            <p class="text-slate-500 text-sm mt-1 ml-11">Ending balances for all accounts</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Account</th>
                        <th class="px-8 py-5 text-right">Debit</th>
                        <th class="px-8 py-5 text-right">Credit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($trialBalance as $trial)
                        <tr class="hover:bg-slate-50 transition-colors duration-200">
                            <td class="px-8 py-5 flex items-center gap-4">
                                <span class="bg-slate-100 font-mono text-slate-500 px-2 py-1 rounded-lg text-xs font-medium">{{ $trial['account_code'] }}</span>
                                <span class="font-bold text-navy">{{ $trial['account_name'] }}</span>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-brand-blue">
                                @if($trial['debit'] > 0) ₱{{ number_format($trial['debit'], 2) }} @else - @endif
                            </td>
                            <td class="px-8 py-5 text-right font-black text-brand-red">
                                @if($trial['credit'] > 0) ₱{{ number_format($trial['credit'], 2) }} @else - @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-slate-50/50 border-t border-slate-100 text-center">
            <a href="{{ route('ledger.trial-balance') }}" class="text-navy font-bold hover:text-brand-blue transition-colors inline-flex items-center gap-2 group">
                View Full Trial Balance
                <div class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center group-hover:bg-brand-blue group-hover:text-white transition-all">
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- DASHBOARD ANALYTICS --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mt-8">
    <div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.05)] border border-slate-100 p-8 hover:shadow-lg transition-all duration-300 flex flex-col">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-navy tracking-tight">Financial Summary</h2>
                <p class="text-slate-500 text-sm mt-1">Assets, liabilities, equity, and income overview</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-2xl text-slate-400">
                <i data-lucide="pie-chart" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="flex-1 relative flex justify-center items-center min-h-[320px]">
            <canvas id="trialChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.05)] border border-slate-100 p-8 hover:shadow-lg transition-all duration-300 flex flex-col">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-navy tracking-tight">Account Balance Overview</h2>
                <p class="text-slate-500 text-sm mt-1">Current financial balances breakdown</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-2xl text-slate-400">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="flex-1 relative min-h-[320px]">
            <canvas id="balanceChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ACCESS DENIED MODAL FUNCTION
    function showAccessDenied() {
        if(typeof AppUI !== 'undefined') {
            AppUI.openModal(`
                <div class="text-center py-4">
                    <div class="w-12 h-12 rounded-full bg-red-100 text-brand-red mx-auto flex items-center justify-center mb-3">
                        <i data-lucide="shield-alert" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-navy mb-2">Access Denied</h3>
                    <p class="text-sm text-slate-500 mb-5">You don't have permission for this action.</p>
                    <div class="flex justify-center">
                        <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-6 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Understood</button>
                    </div>
                </div>
            `, 'sm');
            // Re-initialize lucide icons inside the dynamically generated modal
            if(typeof lucide !== 'undefined') lucide.createIcons();
        } else {
            alert("Access Denied: You don't have permission for this action.");
        }
    }

    const trialChart = document.getElementById('trialChart');
    new Chart(trialChart, {
        type: 'doughnut',
        data: {
            labels: ['Assets', 'Liabilities', 'Equity', 'Net Income'],
            datasets: [{
                data: [{{ $totalAssets }}, {{ $totalLiabilities }}, {{ $totalEquity }}, {{ max($netIncome, 0) }}],
                backgroundColor: ['#2F4CDD', '#EF4B4B', '#1FCB88', '#F5941F'],
                borderWidth: 4,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 24,
                        usePointStyle: true,
                        font: { family: "'Inter', sans-serif", weight: '600', size: 13 },
                        color: '#64748b'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { family: "'Inter', sans-serif", size: 14 },
                    bodyFont: { family: "'Inter', sans-serif", size: 14, weight: 'bold' },
                    cornerRadius: 8,
                    displayColors: true,
                    boxPadding: 6
                }
            },
            cutout: '75%',
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    const balanceChart = document.getElementById('balanceChart');
    new Chart(balanceChart, {
        type: 'bar',
        data: {
            labels: ['Assets', 'Liabilities', 'Equity', 'Net Income'],
            datasets: [{
                label: 'Amount (₱)',
                data: [{{ $totalAssets }}, {{ $totalLiabilities }}, {{ $totalEquity }}, {{ max($netIncome, 0) }}],
                backgroundColor: ['#2F4CDD', '#EF4B4B', '#1FCB88', '#F5941F'],
                borderRadius: 8,
                barThickness: 48,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f8fafc', drawBorder: false },
                    border: { dash: [4, 4], display: false },
                    ticks: {
                        font: { family: "'Inter', sans-serif", weight: '500' },
                        color: '#94a3b8',
                        padding: 10
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: {
                        font: { family: "'Inter', sans-serif", weight: '600' },
                        color: '#64748b',
                        padding: 10
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { family: "'Inter', sans-serif", size: 14 },
                    bodyFont: { family: "'Inter', sans-serif", size: 14, weight: 'bold' },
                    cornerRadius: 8
                }
            },
            animation: {
                y: { duration: 1000, easing: 'easeOutQuart' }
            }
        }
    });

    function changeLedgerView() {
        const value = document.getElementById('ledgerView').value;
        const targetId = value + 'Section';
        const sections = ['recordsSection', 'accountsSection', 'balanceSection'];

        // Find the currently visible section
        const activeSectionId = sections.find(id => !document.getElementById(id).classList.contains('hidden') && id !== targetId);

        if (activeSectionId) {
            const activeEl = document.getElementById(activeSectionId);
            const targetEl = document.getElementById(targetId);
            
            // 1. Fade and scale out the active section
            activeEl.classList.remove('opacity-100', 'scale-y-100');
            activeEl.classList.add('opacity-0', 'scale-y-95');
            
            // 2. Wait exactly for the Tailwind duration (300ms) to finish
            setTimeout(() => {
                // Remove the old section from the DOM flow
                activeEl.classList.add('hidden');
                
                // Bring the new section into the DOM flow
                targetEl.classList.remove('hidden');
                
                // Force browser reflow so it registers the starting state before animating
                void targetEl.offsetWidth; 
                
                // Fade and scale in the new section
                targetEl.classList.remove('opacity-0', 'scale-y-95');
                targetEl.classList.add('opacity-100', 'scale-y-100');
                
            }, 300); // Matches duration-300
        }
    }
</script>
@endpush