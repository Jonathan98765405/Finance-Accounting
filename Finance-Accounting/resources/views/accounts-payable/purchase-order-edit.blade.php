@extends('layouts.app')
@section('page-title', 'Finance & Accounting | Edit Purchase Order')
@section('page-title-heading', 'Edit Purchase Order')
@section('page-subtitle', 'Update the supplier, date, and line items for this purchase order.')

@section('content')

<div class="space-y-6 max-w-3xl">

    @if ($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-100 text-brand-red p-4 text-sm">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ap.po.update', $purchaseOrder) }}" id="poEditForm">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-card p-6 space-y-5 mb-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Supplier</label>
                    <select name="supplier_id" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green">
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id)>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">PO Number</label>
                    <input type="text" name="po_number" required
                           value="{{ old('po_number', $purchaseOrder->po_number) }}"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">PO Date</label>
                    <input type="date" name="po_date" required
                           value="{{ old('po_date', $purchaseOrder->po_date?->format('Y-m-d')) }}"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green">
                </div>
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-card p-6">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-navy uppercase tracking-wide">Line Items</h3>
                <button type="button" id="addItemRow"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-brand-green text-brand-green hover:bg-brand-green hover:text-white px-3 py-1.5 text-xs font-semibold transition">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    Add Item
                </button>
            </div>

            <div class="border border-slate-100 rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-[11px] uppercase tracking-wider text-slate-400 font-semibold">
                            <th class="py-2.5 px-4">Item</th>
                            <th class="py-2.5 px-4 text-right" style="width: 110px;">Qty</th>
                            <th class="py-2.5 px-4 text-right" style="width: 140px;">Unit Price</th>
                            <th class="py-2.5 px-4" style="width: 40px;"></th>
                        </tr>
                    </thead>
                    <tbody id="itemRows" class="divide-y divide-slate-100">
                        @php $existingItems = old('items', $purchaseOrder->items->map(fn ($i) => [
                            'description' => $i->description,
                            'quantity' => $i->quantity,
                            'unit_price' => $i->unit_price,
                        ])->toArray()); @endphp

                        @forelse ($existingItems as $index => $item)
                            <tr class="item-row">
                                <td class="py-2 px-4">
                                    <input type="text" name="items[{{ $index }}][description]" required
                                           value="{{ $item['description'] ?? '' }}"
                                           placeholder="Item description"
                                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green">
                                </td>
                                <td class="py-2 px-4">
                                    <input type="number" step="0.01" min="0" name="items[{{ $index }}][quantity]" required
                                           value="{{ $item['quantity'] ?? '' }}"
                                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green">
                                </td>
                                <td class="py-2 px-4">
                                    <input type="number" step="0.01" min="0" name="items[{{ $index }}][unit_price]" required
                                           value="{{ $item['unit_price'] ?? '' }}"
                                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green">
                                </td>
                                <td class="py-2 px-2 text-center">
                                    <button type="button" class="remove-item-row text-slate-400 hover:text-brand-red transition">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="text-xs text-slate-400 mt-3">Total is recalculated automatically (subtotal + 12% VAT) when you save.</p>

        </div>

        <div class="flex items-center justify-end gap-3 mt-6">
            <a href="{{ route('ap.po.index') }}"
               class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 px-5 py-2.5 text-sm font-semibold transition">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-xl bg-brand-green hover:bg-brand-greenDark text-white px-5 py-2.5 text-sm font-semibold shadow-sm transition">
                Save Changes
            </button>
        </div>

    </form>

</div>

{{-- Hidden template used to clone a fresh row when "Add Item" is clicked.
     __INDEX__ gets replaced with a real, unique numeric index by the JS below,
     the same way the pre-filled rows above use $index — otherwise every row's
     fields collapse onto the same top-level PHP array index on submit. --}}
<template id="itemRowTemplate">
    <tr class="item-row">
        <td class="py-2 px-4">
            <input type="text" name="items[__INDEX__][description]" required placeholder="Item description"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green">
        </td>
        <td class="py-2 px-4">
            <input type="number" step="0.01" min="0" name="items[__INDEX__][quantity]" required
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green">
        </td>
        <td class="py-2 px-4">
            <input type="number" step="0.01" min="0" name="items[__INDEX__][unit_price]" required
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-brand-green/30 focus:border-brand-green">
        </td>
        <td class="py-2 px-2 text-center">
            <button type="button" class="remove-item-row text-slate-400 hover:text-brand-red transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemRows = document.getElementById('itemRows');
    const template = document.getElementById('itemRowTemplate');

    // Start counting new rows after the highest index already on the page,
    // so a freshly added row never collides with an existing one.
    let nextIndex = itemRows.querySelectorAll('.item-row').length;

    function bindRemoveButtons() {
        document.querySelectorAll('.remove-item-row').forEach(function (btn) {
            btn.onclick = function () {
                // Always keep at least one row so the form can't submit empty.
                if (itemRows.querySelectorAll('.item-row').length > 1) {
                    btn.closest('.item-row').remove();
                }
            };
        });
    }

    document.getElementById('addItemRow').addEventListener('click', function () {
        const html = template.innerHTML.replaceAll('__INDEX__', nextIndex);
        nextIndex++;

        const wrapper = document.createElement('tbody');
        wrapper.innerHTML = html.trim();
        itemRows.appendChild(wrapper.firstElementChild);

        bindRemoveButtons();
        if (window.lucide) lucide.createIcons();
    });

    bindRemoveButtons();

    // No existing items (edge case) -> start with one empty row.
    if (itemRows.querySelectorAll('.item-row').length === 0) {
        document.getElementById('addItemRow').click();
    }
});
</script>

@endsection