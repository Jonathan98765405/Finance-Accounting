@extends('layouts.app')

@section('page-title', 'Record Goods Receipt')

@section('page-title-heading', 'Record Goods Receipt')

@section('page-subtitle', 'Confirm goods were received against a purchase order so it can pass Three-Way Match.')

@section('content')
<div class="max-w-4xl mx-auto">

    @if ($errors->any())
        <div class="bg-red-50 border border-red-100 text-brand-red rounded-2xl p-4 mb-6 shadow-sm flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
            <div>
                <strong class="font-bold text-sm block mb-1">Please fix the following:</strong>
                <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($purchaseOrders->isEmpty())
        <div class="bg-amber-50 border border-amber-100 text-brand-orange rounded-2xl p-5 mb-6 shadow-sm flex items-center gap-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-brand-orange">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div class="text-sm">
                <p class="font-semibold">No purchase orders exist yet.</p>
                <p class="text-xs opacity-90 mt-0.5">You must create a purchase order first before recording goods received. 
                    <a href="{{ route('ap.po.create') }}" class="underline font-bold hover:opacity-80">Create one first &rarr;</a>
                </p>
            </div>
        </div>
    @else

    <form method="POST" action="{{ route('ap.grn.store') }}" id="grnForm" class="space-y-6">
        @csrf

        <div class="bg-white border border-slate-100 rounded-2xl shadow-card p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-green/10 text-brand-greenDark">
                    <i data-lucide="truck" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-navy">Goods Receipt Information</h3>
                    <p class="text-slate-400 text-xs">Verify details matches the delivery note and invoice.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label for="poSelect" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Purchase Order
                    </label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-xl border border-slate-200 bg-white py-2.5 pl-4 pr-10 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition" 
                                name="purchase_order_id" 
                                id="poSelect" 
                                required>
                            <option value="" selected disabled>Select Purchase Order</option>
                            @foreach ($purchaseOrders as $po)
                                <option
                                    value="{{ $po->id }}"
                                    data-total="{{ $po->total_amount }}"
                                    {{ old('purchase_order_id', $purchaseOrder?->id) == $po->id ? 'selected' : '' }}>
                                    {{ $po->po_number }} &mdash; {{ $po->supplier->name }} (₱{{ number_format($po->total_amount, 2) }})
                                </option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        GRN Number
                    </label>
                    <input
                        type="text"
                        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 px-4 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition"
                        name="grn_number"
                        value="{{ old('grn_number') }}"
                        placeholder="GRN-2026-001"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Receipt Date
                    </label>
                    <input
                        type="date"
                        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 px-4 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition text-slate-700"
                        name="receipt_date"
                        value="{{ old('receipt_date', now()->toDateString()) }}"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Total Amount Received
                    </label>
                    <input
                        type="number"
                        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 px-4 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition"
                        name="total_amount"
                        id="totalAmount"
                        value="{{ old('total_amount', $purchaseOrder?->total_amount) }}"
                        min="0"
                        step="0.01"
                        required>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        Defaults to the PO total. Adjust only if this is a partial receipt.
                    </p>
                </div>

            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mb-6">
            <a href="{{ route('ap.po.index') }}" 
               class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 bg-white hover:bg-slate-50 transition">
                Cancel
            </a>

            <button type="submit" 
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-brand-greenDark hover:bg-brand-greenDark/90 shadow-sm transition">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Goods Receipt
            </button>
        </div>

    </form>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const poSelect = document.getElementById('poSelect');
    const totalAmount = document.getElementById('totalAmount');

    if (poSelect) {
        poSelect.addEventListener('change', function () {
            const selected = poSelect.options[poSelect.selectedIndex];
            const total = selected.getAttribute('data-total');
            if (total) {
                totalAmount.value = parseFloat(total).toFixed(2);
            }
        });
    }
});
</script>
@endsection