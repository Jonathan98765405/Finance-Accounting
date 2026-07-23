@extends('layouts.app')

@section('page-title', 'All Journal Entries')
@section('page-title-heading', 'All Journal Entries')
@section('page-subtitle', 'Complete history of every recorded transaction.')

@section('content')

@if(session('success'))
    <div class="bg-brand-green/10 border border-brand-green/20 text-brand-greenDark px-6 py-4 rounded-2xl mb-8 flex items-center shadow-sm">
        <div class="bg-brand-green/20 p-2 rounded-full mr-4">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
        </div>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

{{-- BACK LINK --}}
<a href="{{ route('ledger.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-navy font-semibold text-sm mb-6 transition-colors group">
    <div class="w-8 h-8 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center group-hover:bg-navy group-hover:text-white group-hover:border-navy transition-all">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
    </div>
    Back to General Ledger
</a>

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('ledger.alljournal') }}" class="mb-6">
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
                <a href="{{ route('ledger.alljournal') }}"
                    class="flex items-center gap-2 border border-slate-200 text-slate-500 hover:text-brand-red hover:border-brand-red/30 hover:bg-brand-red/5 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all">
                    <i data-lucide="x" class="w-4 h-4"></i> Clear
                </a>
            @endif
        </div>

    </div>
</form>

{{-- ALL JOURNAL ENTRIES --}}
<div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.05)] border border-slate-100 overflow-hidden">
    <div class="flex justify-between items-center px-8 py-6 border-b border-slate-100 bg-white">
        <div>
            <h2 class="text-xl font-black text-navy tracking-tight flex items-center gap-2">
                <div class="p-2 bg-navy/5 rounded-xl text-navy">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </div>
                All Journal Entries
            </h2>
            <p class="text-slate-500 text-sm mt-1 ml-11">
                @if(method_exists($entries, 'total'))
                    {{ $entries->total() }} total transactions
                @else
                    {{ $entries->count() }} total transactions
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
                                <p class="text-sm mt-1">Try adjusting your filters or search terms.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($entries, 'links') && $entries->hasPages())
        <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
            {{ $entries->appends(request()->query())->links() }}
        </div>
    @endif
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
</script>
@endpush