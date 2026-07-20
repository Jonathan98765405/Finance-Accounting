@extends('layouts.app')
@section('page-title', 'Finance & Accounting | Purchase Orders')
@section('page-title-heading', 'Purchase Orders')
@section('page-subtitle', 'Purchase orders and their goods receipt status. Both are required for a Three-Way Match.')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 no-print">
        
        <a href="{{ route('ap.grn.create') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-navy-600 bg-white text-navy-600 hover:bg-navy-50 px-4 py-2.5 text-sm font-semibold shadow-sm transition">
            <i data-lucide="truck" class="w-4 h-4"></i>
            Record Goods Receipt
        </a>

        <a href="{{ route('ap.po.create') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-green hover:bg-brand-greenDark text-white px-4 py-2.5 text-sm font-semibold shadow-sm transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            New Purchase Order
        </a>

    </div>

    @if (session('success'))
        <div class="flex items-center gap-2.5 rounded-xl bg-emerald-50 border border-brand-green/30 text-emerald-800 p-4 text-sm font-medium shadow-sm">
            <i data-lucide="circle-check-big" class="w-5 h-5 text-brand-green"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('warning'))
        <div class="flex items-center gap-2.5 rounded-xl bg-amber-50 border border-amber-300/60 text-amber-800 p-4 text-sm font-medium shadow-sm">
            <i data-lucide="triangle-alert" class="w-5 h-5 text-amber-500"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    <div class="relative no-print">
        <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input
            type="text"
            id="poSearch"
            onkeyup="filterPOTable()"
            placeholder="Search by PO number, supplier, or status..."
            class="w-full sm:w-96 rounded-xl border border-slate-200 bg-white pl-10 pr-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green shadow-sm"
        >
    </div>

    <div class="bg-white rounded-2xl shadow-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] uppercase tracking-wider text-slate-400 font-semibold">
                        <th class="py-3.5 px-6">PO Number</th>
                        <th class="py-3.5 px-6">Supplier</th>
                        <th class="py-3.5 px-6">PO Date</th>
                        <th class="py-3.5 px-6 text-right">Total Amount</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6">Goods Receipt</th>
                        <th class="py-3.5 px-6 text-right" style="width: 210px;">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600" id="poTableBody">
                    @forelse ($purchaseOrders as $po)
                        <tr class="po-row hover:bg-slate-50/50 transition cursor-pointer"
                            onclick="openPoDetails('{{ $po->id }}')">
                            
                            <td class="py-4 px-6 font-semibold text-navy">
                                {{ $po->po_number }}
                            </td>
                            
                            <td class="py-4 px-6 font-medium text-slate-700">
                                {{ $po->supplier->name }}
                            </td>
                            
                            <td class="py-4 px-6 text-slate-500">
                                {{ $po->po_date->format('M d, Y') }}
                            </td>
                            
                            <td class="py-4 px-6 text-right font-bold text-navy">
                                ₱{{ number_format($po->total_amount, 2) }}
                            </td>

                            <td class="py-4 px-6">
                                @if ($po->status === 'received')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-brand-greenDark border border-emerald-100 text-xs font-semibold capitalize">
                                        {{ $po->status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200/60 text-xs font-semibold capitalize">
                                        {{ $po->status }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6">
                                @if ($po->goodsReceipts->isNotEmpty())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-brand-greenDark border border-emerald-100 text-xs font-semibold">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        {{ $po->goodsReceipts->first()->grn_number }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-brand-red border border-red-100 text-xs font-semibold">
                                        <i data-lucide="circle-alert" class="w-3.5 h-3.5"></i>
                                        Not received
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-right" onclick="event.stopPropagation()">
                                <div class="flex flex-col items-end gap-1.5">

                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button"
                                                onclick="openPoDetails('{{ $po->id }}')"
                                                title="View details"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 hover:border-navy text-slate-500 hover:text-navy transition">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <a href="{{ route('ap.po.edit', $po) }}"
                                           title="Edit purchase order"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 hover:border-navy text-slate-500 hover:text-navy transition">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </a>

                                        <button type="button"
                                                onclick="openDeleteConfirm('{{ route('ap.po.destroy', $po) }}', '{{ $po->po_number }}')"
                                                title="Delete purchase order"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-red-100 bg-white hover:bg-red-50 text-brand-red transition">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>

                                    @if ($po->goodsReceipts->isEmpty())
                                        <a href="{{ route('ap.grn.create', $po) }}" 
                                           class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 hover:border-navy bg-white hover:bg-slate-50 text-slate-700 hover:text-navy px-3 py-1.5 text-xs font-semibold transition">
                                            <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                            Record GRN
                                        </a>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-400 py-12">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="package-open" class="w-8 h-8 text-slate-300"></i>
                                    <span class="font-medium text-sm">No purchase orders yet. Create one to get started.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p id="poNoResults" class="hidden text-center text-slate-400 py-8 text-sm">
            No matching purchase orders found.
        </p>
    </div>

</div>

{{-- ================= PO DETAILS MODALS (one per PO, shown/hidden via JS) ================= --}}
@foreach ($purchaseOrders as $po)
    <div id="poDetailsModal-{{ $po->id }}"
         class="po-details-modal fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden">

            <div class="flex items-start justify-between px-6 py-5 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-navy">{{ $po->po_number }}</h3>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $po->supplier->name }} &middot; {{ $po->po_date->format('M d, Y') }}</p>
                </div>
                <button type="button" onclick="closePoDetails('{{ $po->id }}')"
                        class="text-slate-400 hover:text-slate-600 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="px-6 py-5 overflow-y-auto flex-1">

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div>
                        <div class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold mb-1">Status</div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold capitalize
                            {{ $po->status === 'received' ? 'bg-emerald-50 text-brand-greenDark border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200/60' }}">
                            {{ $po->status }}
                        </span>
                    </div>
                    <div>
                        <div class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold mb-1">Goods Receipt</div>
                        @if ($po->goodsReceipts->isNotEmpty())
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-brand-greenDark border border-emerald-100 text-xs font-semibold">
                                {{ $po->goodsReceipts->first()->grn_number }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-50 text-brand-red border border-red-100 text-xs font-semibold">
                                Not received
                            </span>
                        @endif
                    </div>
                    <div class="col-span-2 sm:col-span-2 sm:text-right">
                        <div class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold mb-1">Total Amount</div>
                        <div class="text-lg font-bold text-navy">₱{{ number_format($po->total_amount, 2) }}</div>
                    </div>
                </div>

                <div class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold mb-2">Line Items</div>
                <div class="border border-slate-100 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 text-[11px] uppercase tracking-wider text-slate-400 font-semibold">
                                <th class="py-2.5 px-4">Item</th>
                                <th class="py-2.5 px-4 text-right">Qty</th>
                                <th class="py-2.5 px-4 text-right">Unit Price</th>
                                <th class="py-2.5 px-4 text-right">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse (($po->items ?? []) as $item)
                                <tr>
                                    <td class="py-2.5 px-4 text-slate-700">{{ $item->description ?? '—' }}</td>
                                    <td class="py-2.5 px-4 text-right text-slate-600">{{ $item->quantity ?? '—' }}</td>
                                    <td class="py-2.5 px-4 text-right text-slate-600">₱{{ number_format($item->unit_price ?? 0, 2) }}</td>
                                    <td class="py-2.5 px-4 text-right font-semibold text-navy">₱{{ number_format(($item->quantity ?? 0) * ($item->unit_price ?? 0), 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-slate-400 py-6 text-sm">No line items recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                <button type="button" onclick="closePoDetails('{{ $po->id }}')"
                        class="rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 px-4 py-2 text-sm font-semibold transition">
                    Close
                </button>
                <a href="{{ route('ap.po.edit', $po) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-brand-green hover:bg-brand-greenDark text-white px-4 py-2 text-sm font-semibold transition">
                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                    Edit
                </a>
            </div>

        </div>
    </div>
@endforeach

{{-- ================= DELETE CONFIRMATION MODAL (shared) ================= --}}
<div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
        <div class="px-6 pt-6 pb-2 text-center">
            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="triangle-alert" class="w-6 h-6 text-brand-red"></i>
            </div>
            <h3 class="text-base font-bold text-navy mb-1">Delete purchase order?</h3>
            <p class="text-sm text-slate-500" id="deleteConfirmText">This action cannot be undone.</p>
        </div>
        <form method="POST" id="deleteConfirmForm" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-center gap-3 px-6 pt-5 pb-6">
                <button type="button" onclick="closeDeleteConfirm()"
                        class="rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 px-4 py-2 text-sm font-semibold transition">
                    Cancel
                </button>
                <button type="submit"
                        class="rounded-lg bg-brand-red hover:bg-red-700 text-white px-4 py-2 text-sm font-semibold transition">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterPOTable() {
        const query = document.getElementById('poSearch').value.toLowerCase();
        const rows = document.querySelectorAll('.po-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const match = row.innerText.toLowerCase().includes(query);
            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        document.getElementById('poNoResults').classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
    }

    // ================= PO DETAILS MODAL =================
    function openPoDetails(id) {
        const modal = document.getElementById('poDetailsModal-' + id);
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePoDetails(id) {
        const modal = document.getElementById('poDetailsModal-' + id);
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close on click outside the dialog, and on Escape
    document.querySelectorAll('.po-details-modal').forEach(function (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });

    // ================= DELETE CONFIRMATION MODAL =================
    function openDeleteConfirm(action, poNumber) {
        const modal = document.getElementById('deleteConfirmModal');
        const form = document.getElementById('deleteConfirmForm');
        const text = document.getElementById('deleteConfirmText');

        form.action = action;
        text.textContent = poNumber
            ? `This will permanently delete purchase order ${poNumber}. This action cannot be undone.`
            : 'This action cannot be undone.';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteConfirm() {
        const modal = document.getElementById('deleteConfirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('deleteConfirmModal').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteConfirm();
    });

    // Escape key closes whichever modal is open
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;

        document.querySelectorAll('.po-details-modal:not(.hidden)').forEach(function (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        const deleteModal = document.getElementById('deleteConfirmModal');
        if (!deleteModal.classList.contains('hidden')) closeDeleteConfirm();
    });
</script>

@endsection