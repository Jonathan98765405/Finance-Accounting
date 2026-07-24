@extends('layouts.app')

@section('page-title-heading', 'Purchase Orders')
@section('page-subtitle', 'Track purchase orders and sync approved POs directly to Accounts Payable (AP).')

@section('content')
<div class="space-y-6">

    {{-- Filter and Action Toolbar --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text" placeholder="Search PO # or vendor..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-navy focus:ring-1 focus:ring-navy outline-none transition">
            </div>
            <div class="hidden md:flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status:</span>
                <select class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 focus:border-navy outline-none">
                    <option value="">All Statuses</option>
                    <option value="pending_approval">Pending Approval</option>
                    <option value="approved">Approved</option>
                    <option value="sent_to_ap">Synced to AP</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <a href="{{ route('purchase-orders.create') }}" class="inline-flex items-center gap-2 bg-navy hover:bg-navy-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Create Purchase Order</span>
            </a>
        </div>
    </div>

    {{-- Purchase Orders Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-bold text-navy">All Purchase Orders</h3>
                <p class="text-xs text-slate-500 mt-0.5">Manage active vendor orders and accounting integrations</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 uppercase text-xs font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">PO #</th>
                        <th class="px-6 py-3.5">Vendor</th>
                        <th class="px-6 py-3.5">Total Amount</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $po)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-4 font-bold text-navy">
                                {{ $po->po_number }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ $po->vendor->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                ${{ number_format($po->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'sent_to_ap'       => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'approved'         => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'pending_approval' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'rejected'         => 'bg-red-50 text-brand-red border-red-200',
                                    ];
                                    $dotClasses = [
                                        'sent_to_ap'       => 'bg-indigo-500',
                                        'approved'         => 'bg-emerald-500',
                                        'pending_approval' => 'bg-amber-500',
                                        'rejected'         => 'bg-brand-red',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusClasses[$po->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses[$po->status] ?? 'bg-slate-400' }}"></span>
                                    {{ ucfirst(str_replace('_', ' ', $po->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($po->status === 'pending_approval' && auth()->user()?->can('approve-items'))
                                    <div class="inline-flex items-center gap-2">
                                        <form action="{{ route('approvals.process') }}" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="approvable_type" value="purchase_order">
                                            <input type="hidden" name="approvable_id" value="{{ $po->id }}">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3 py-1.5 rounded-lg shadow-sm transition">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('approvals.process') }}" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="approvable_type" value="purchase_order">
                                            <input type="hidden" name="approvable_id" value="{{ $po->id }}">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold px-3 py-1.5 rounded-lg shadow-sm transition">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                @elseif($po->status === 'pending_approval')
                                    <span class="text-xs text-slate-400">Awaiting approval</span>
                                @elseif($po->status === 'approved')
                                    <button type="button"
                                            onclick="triggerSync('{{ route('purchase-orders.sync', $po->id) }}')"
                                            class="inline-flex items-center gap-1.5 text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-1.5 rounded-lg shadow-sm transition cursor-pointer">
                                        <span>Sync to AP</span>
                                    </button>
                                @elseif($po->status === 'sent_to_ap')
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                                        Synced
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <p class="font-medium text-slate-500 text-sm">No purchase orders found.</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Approved requisitions will appear here as purchase orders.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function triggerSync(url) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        let token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';

        form.appendChild(token);
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection
