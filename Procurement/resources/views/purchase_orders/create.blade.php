@extends('layouts.app')

@section('page-title', 'Create Purchase Order')
@section('page-title-heading', 'Create Purchase Order')
@section('page-subtitle', 'Generate a new purchase order and sync it directly to Accounts Payable (AP).')

@section('content')
<div class="max-w-[1400px] mx-auto space-y-6">

    {{-- Session Alerts --}}
    @if ($errors->any())
        <div class="flex items-center gap-3 rounded-xl bg-red-50 border border-red-200 text-brand-red px-4 py-3.5 text-sm font-semibold shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
            <span>Please fix the errors below before submitting the purchase order.</span>
        </div>
    @endif

    <form method="POST" action="{{ route('purchase-orders.store') }}" class="space-y-6">
        @csrf

        {{-- Main PO Details Card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
            <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                <h3 class="text-sm font-bold text-navy uppercase tracking-wider">Purchase Order Information</h3>
                <span class="text-xs font-semibold text-slate-400">Fields marked with * are required</span>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- PO Number --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">PO Number</label>
                    <input type="text" 
                           name="po_number" 
                           value="{{ old('po_number', $suggestedPoNumber ?? '') }}" 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-navy bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-navy/20">
                    <span class="text-[11px] text-slate-400 mt-1 block">Leave blank to auto-generate.</span>
                    @error('po_number')
                        <span class="text-xs text-brand-red mt-1 block font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Vendor / Supplier --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Vendor / Supplier *</label>
                    <select name="vendor_id" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-navy/20">
                        <option value="" disabled selected>Select a vendor</option>
                        @foreach ($vendors ?? [] as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                        <span class="text-xs text-brand-red mt-1 block font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Requisition Source (Optional) --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Linked Requisition (Optional)</label>
                    <select name="requisition_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-navy/20">
                        <option value="">None / Manual PO</option>
                        @foreach ($requisitions ?? [] as $req)
                            <option value="{{ $req->id }}" {{ old('requisition_id') == $req->id ? 'selected' : '' }}>
                                Req #{{ $req->id }} - ${{ number_format($req->total_amount, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        {{-- Line Items Card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
            <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                <h3 class="text-sm font-bold text-navy uppercase tracking-wider">Line Items</h3>
                <button type="button" id="add-row-btn" class="inline-flex items-center gap-1.5 text-xs font-bold text-navy hover:text-navy-700 transition">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Add Item
                </button>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs border-collapse" id="po-items-table">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase tracking-wider font-bold">
                                <th class="p-3.5">Item Description *</th>
                                <th class="p-3.5 w-28 text-center">Quantity *</th>
                                <th class="p-3.5 w-36 text-right">Unit Price ($) *</th>
                                <th class="p-3.5 w-36 text-right">Total ($)</th>
                                <th class="p-3.5 w-16 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium" id="po-items-body">
                            <tr class="po-item-row">
                                <td class="p-3">
                                    <input type="text" name="items[0][description]" placeholder="Item description or SKU" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-navy/20">
                                </td>
                                <td class="p-3">
                                    <input type="number" name="items[0][quantity]" value="1" min="0.01" step="any" required class="item-qty w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-center text-navy focus:outline-none focus:ring-2 focus:ring-navy/20">
                                </td>
                                <td class="p-3">
                                    <input type="number" name="items[0][unit_price]" value="0.00" min="0" step="0.01" required class="item-price w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-right text-navy focus:outline-none focus:ring-2 focus:ring-navy/20">
                                </td>
                                <td class="p-3 text-right font-bold text-navy item-total">
                                    $0.00
                                </td>
                                <td class="p-3 text-center">
                                    <button type="button" class="remove-row-btn text-slate-400 hover:text-brand-red transition p-1">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Summary Footer --}}
                <div class="flex justify-end pt-5">
                    <div class="w-full sm:w-72 bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-2">
                        <div class="flex justify-between text-xs font-bold text-slate-500 uppercase">
                            <span>Grand Total:</span>
                            <span id="grand-total" class="text-sm font-extrabold text-navy">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 px-6 py-3 text-sm font-bold transition">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-navy hover:bg-navy-700 text-white px-6 py-3 text-sm font-bold shadow-sm transition">
                <i data-lucide="check" class="w-4 h-4"></i>
                Save Purchase Order
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let rowIndex = 1;
        const tbody = document.getElementById('po-items-body');
        const addBtn = document.getElementById('add-row-btn');
        const grandTotalEl = document.getElementById('grand-total');

        function recalculate() {
            let grandTotal = 0;
            document.querySelectorAll('.po-item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
                const price = parseFloat(row.querySelector('.item-price').value) || 0;
                const total = qty * price;
                row.querySelector('.item-total').textContent = '$' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                grandTotal += total;
            });
            grandTotalEl.textContent = '$' + grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        tbody.addEventListener('input', function (e) {
            if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) {
                recalculate();
            }
        });

        addBtn.addEventListener('click', function () {
            const tr = document.createElement('tr');
            tr.className = 'po-item-row';
            tr.innerHTML = `
                <td class="p-3">
                    <input type="text" name="items[${rowIndex}][description]" placeholder="Item description or SKU" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-navy/20">
                </td>
                <td class="p-3">
                    <input type="number" name="items[${rowIndex}][quantity]" value="1" min="0.01" step="any" required class="item-qty w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-center text-navy focus:outline-none focus:ring-2 focus:ring-navy/20">
                </td>
                <td class="p-3">
                    <input type="number" name="items[${rowIndex}][unit_price]" value="0.00" min="0" step="0.01" required class="item-price w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-right text-navy focus:outline-none focus:ring-2 focus:ring-navy/20">
                </td>
                <td class="p-3 text-right font-bold text-navy item-total">
                    $0.00
                </td>
                <td class="p-3 text-center">
                    <button type="button" class="remove-row-btn text-slate-400 hover:text-brand-red transition p-1">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            rowIndex++;
            if (window.lucide) lucide.createIcons();
        });

        tbody.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row-btn')) {
                if (document.querySelectorAll('.po-item-row').length > 1) {
                    e.target.closest('tr').remove();
                    recalculate();
                }
            }
        });

        recalculate();
    });
</script>
@endpush
@endsection