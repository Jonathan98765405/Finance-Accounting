@extends('layouts.app')

@section('title', 'Add Invoice')

@section('content')

    @php
        // Repopulate on validation failure, otherwise start with one blank row.
        $items = old('items', [
            ['description' => '', 'qty' => 1, 'unit_price' => 0],
        ]);
    @endphp

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Add Invoice</h1>
            <p class="text-sm text-slate-500 mt-1">
                <a href="{{ route('sales-invoices.index') }}" class="hover:text-brand-600">Sales Invoices</a>
                <span class="mx-1">&gt;</span> Add Invoice
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('sales-invoices.index') }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium rounded-lg px-4 py-2.5 transition">
                Cancel
            </a>
            <button type="submit" form="invoice-form" class="inline-flex items-center gap-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-lg px-4 py-2.5 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-8H7v8M7 3v5h8M5 3h11l4 4v11a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                </svg>
                Save Invoice
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
            <p class="font-medium mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="invoice-form" method="POST" action="{{ route('sales-invoices.store') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

            {{-- Customer Information --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <h2 class="flex items-center gap-2 font-semibold text-slate-900 mb-4">
                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Customer Information
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5" for="customer_id">Customer</label>
                        <select id="customer_id" name="customer_id" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option
                                    value="{{ $customer->id }}"
                                    data-address="{{ $customer->address }}"
                                    data-email="{{ $customer->email }}"
                                    data-phone="{{ $customer->contact_no }}"
                                    @selected(old('customer_id') == $customer->id)
                                >{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5" for="billing_address">Address</label>
                        <textarea id="billing_address" name="billing_address" rows="2" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">{{ old('billing_address') }}</textarea>
                        @error('billing_address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5" for="billing_email">Email</label>
                            <input id="billing_email" name="billing_email" type="email" value="{{ old('billing_email') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                            @error('billing_email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5" for="billing_phone">Phone Number</label>
                            <input id="billing_phone" name="billing_phone" type="text" value="{{ old('billing_phone') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                            @error('billing_phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Invoice Information --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <h2 class="flex items-center gap-2 font-semibold text-slate-900 mb-4">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Invoice Information
                </h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Invoice Number</label>
                        <input type="text" value="{{ $nextInvoiceNo }}" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-500">
                        <p class="text-xs text-slate-400 mt-1">Auto-generated on save.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5" for="invoice_date">Invoice Date</label>
                        <input id="invoice_date" name="invoice_date" type="date" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                        @error('invoice_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5" for="due_date">Due Date</label>
                        <input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                        @error('due_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5" for="payment_terms">Payment Terms</label>
                        <select id="payment_terms" name="payment_terms" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                            @php $terms = ['Due on Receipt' => 0, 'Net 15' => 15, 'Net 30' => 30, 'Net 45' => 45, 'Net 60' => 60]; @endphp
                            @foreach ($terms as $label => $days)
                                <option value="{{ $label }}" data-days="{{ $days }}" @selected(old('payment_terms', 'Net 30') === $label)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_terms') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5" for="payment_status">Payment Status</label>
                        <select id="payment_status" name="payment_status" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                            <option value="Unpaid" @selected(old('payment_status', 'Unpaid') === 'Unpaid')>Unpaid</option>
                            <option value="Paid" @selected(old('payment_status') === 'Paid')>Paid</option>
                        </select>
                        @error('payment_status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Invoice Items --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                    <h2 class="flex items-center gap-2 font-semibold text-slate-900">
                        <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Invoice Items
                    </h2>
                    <button type="button" id="add-item" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg px-3 py-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Item
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b border-slate-200">
                                <th class="px-5 py-3 font-medium">Item</th>
                                <th class="px-3 py-3 font-medium w-28">Qty</th>
                                <th class="px-3 py-3 font-medium w-28">Unit Price</th>
                                <th class="px-3 py-3 font-medium w-28">Amount</th>
                                <th class="px-3 py-3 font-medium w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="item-rows" class="divide-y divide-slate-100">
                            @foreach ($items as $i => $item)
                                <tr class="item-row">
                                    <td class="px-5 py-2.5">
                                        <select name="items[{{ $i }}][description]" class="product-select w-full rounded-lg border border-slate-200 px-2.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                                            <option value="">Select Item</option>
                                            @foreach ($products as $product)
                                                <option
                                                    value="{{ $product->name }}"
                                                    data-price="{{ $product->price }}"
                                                    @selected(($item['description'] ?? '') === $product->name)
                                                >{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <div class="flex items-stretch rounded-lg border border-slate-200 overflow-hidden">
                                            <button type="button" class="qty-decrease shrink-0 w-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition text-base font-medium" aria-label="Decrease quantity">&minus;</button>
                                            <input type="text" inputmode="numeric" pattern="[0-9]*" name="items[{{ $i }}][qty]" value="{{ $item['qty'] ?? 1 }}" class="qty flex-1 min-w-0 text-center border-0 border-x border-slate-200 px-1 py-2 text-sm font-medium text-slate-900 focus:outline-none focus:ring-0">
                                            <button type="button" class="qty-increase shrink-0 w-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition text-base font-medium" aria-label="Increase quantity">&plus;</button>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <input type="number" name="items[{{ $i }}][unit_price]" value="{{ $item['unit_price'] ?? 0 }}" min="0" step="0.01" readonly class="price w-full rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2 text-sm text-slate-600">
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <input type="text" readonly value="₱0.00" class="row-amount w-full rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2 text-sm text-slate-600">
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <button type="button" class="remove-item text-slate-400 hover:text-red-600 transition" aria-label="Remove item">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7h12z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Summary --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 h-fit">
                <h2 class="flex items-center gap-2 font-semibold text-slate-900 mb-4">
                    <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-6 4h3M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                    </svg>
                    Summary
                </h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span id="summary-subtotal" class="font-medium text-slate-900">₱0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tax (12%)</span>
                        <span id="summary-tax" class="font-medium text-slate-900">₱0.00</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Discount</span>
                        <input type="number" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', 0) }}" min="0" step="0.01" class="w-28 text-right rounded-lg border border-slate-200 px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                    </div>

                    <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                        <span class="font-semibold text-slate-900">Total</span>
                        <span id="summary-total" class="text-lg font-bold text-emerald-600">₱0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('styles')
<style>
    /* Hide native number-input spinner arrows on qty fields since we use
       custom -/+ buttons instead (avoids showing both at the same time). */
    .qty::-webkit-outer-spin-button,
    .qty::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .qty[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endpush
@push('scripts')
<script>
(function () {
    const TAX_RATE = 0.12;
    const itemRowsBody = document.getElementById('item-rows');
    const addItemBtn = document.getElementById('add-item');
    const discountInput = document.getElementById('discount_amount');
    const invoiceDateInput = document.getElementById('invoice_date');
    const dueDateInput = document.getElementById('due_date');
    const paymentTermsSelect = document.getElementById('payment_terms');
    const customerSelect = document.getElementById('customer_id');

    // Build the <option> list for a product select from the first row already
    // rendered by Blade, so the "Add Item" button doesn't need the product
    // list duplicated in JS.
    const productOptionsHtml = document.querySelector('.product-select')
        ? document.querySelector('.product-select').innerHTML
        : '<option value="">Select Item</option>';

    let rowIndex = document.querySelectorAll('.item-row').length;

    function peso(value) {
        return '₱' + Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalcRow(row) {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        row.querySelector('.row-amount').value = peso(qty * price);
    }

    function recalcSummary() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach((row) => {
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            subtotal += qty * price;
        });

        const tax = subtotal * TAX_RATE;
        const discount = parseFloat(discountInput.value) || 0;
        const total = Math.max(subtotal + tax - discount, 0);

        document.getElementById('summary-subtotal').textContent = peso(subtotal);
        document.getElementById('summary-tax').textContent = peso(tax);
        document.getElementById('summary-total').textContent = peso(total);
    }

    function recalcAll() {
        document.querySelectorAll('.item-row').forEach(recalcRow);
        recalcSummary();
    }

    function bindRow(row) {
        const qtyInput = row.querySelector('.qty');

        // Allow free typing, but strip anything that isn't a digit.
        qtyInput.addEventListener('input', () => {
            qtyInput.value = qtyInput.value.replace(/[^0-9]/g, '');
            recalcAll();
        });

        // Once the user leaves the field, make sure it isn't blank or 0.
        qtyInput.addEventListener('blur', () => {
            const current = parseInt(qtyInput.value, 10) || 0;
            qtyInput.value = current < 1 ? 1 : current;
            recalcAll();
        });

        row.querySelector('.qty-decrease').addEventListener('click', () => {
            const current = parseFloat(qtyInput.value) || 0;
            const next = current - 1;
            qtyInput.value = next < 1 ? 1 : next;
            recalcAll();
        });

        row.querySelector('.qty-increase').addEventListener('click', () => {
            const current = parseFloat(qtyInput.value) || 0;
            qtyInput.value = current + 1;
            recalcAll();
        });

        const removeBtn = row.querySelector('.remove-item');
        removeBtn.addEventListener('click', () => {
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                recalcAll();
            }
        });

        const product = row.querySelector('.product-select');
        product.addEventListener('change', function () {
            const price = this.options[this.selectedIndex].dataset.price || 0;
            row.querySelector('.price').value = price;
            recalcAll();
        });
    }

    addItemBtn.addEventListener('click', () => {
        const row = document.createElement('tr');
        row.className = 'item-row';
        row.innerHTML = `
            <td class="px-5 py-2.5">
                <select name="items[${rowIndex}][description]" class="product-select w-full rounded-lg border border-slate-200 px-2.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
                    ${productOptionsHtml}
                </select>
            </td>
            <td class="px-3 py-2.5">
                <div class="flex items-stretch rounded-lg border border-slate-200 overflow-hidden">
                    <button type="button" class="qty-decrease shrink-0 w-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition text-base font-medium" aria-label="Decrease quantity">&minus;</button>
                    <input type="text" inputmode="numeric" pattern="[0-9]*" name="items[${rowIndex}][qty]" value="1" class="qty flex-1 min-w-0 text-center border-0 border-x border-slate-200 px-1 py-2 text-sm font-medium text-slate-900 focus:outline-none focus:ring-0">
                    <button type="button" class="qty-increase shrink-0 w-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition text-base font-medium" aria-label="Increase quantity">&plus;</button>
                </div>
            </td>
            <td class="px-3 py-2.5">
                <input type="number" name="items[${rowIndex}][unit_price]" value="0" min="0" step="0.01" readonly class="price w-full rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2 text-sm text-slate-600">
            </td>
            <td class="px-3 py-2.5">
                <input type="text" readonly value="₱0.00" class="row-amount w-full rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2 text-sm text-slate-600">
            </td>
            <td class="px-3 py-2.5 text-center">
                <button type="button" class="remove-item text-slate-400 hover:text-red-600 transition" aria-label="Remove item">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7h12z" />
                    </svg>
                </button>
            </td>
        `;
        itemRowsBody.appendChild(row);
        bindRow(row);
        rowIndex++;
    });

    document.querySelectorAll('.item-row').forEach(bindRow);
    discountInput.addEventListener('input', recalcSummary);

    function recalcDueDate() {
        const selected = paymentTermsSelect.options[paymentTermsSelect.selectedIndex];
        const days = parseInt(selected.dataset.days || '0', 10);
        if (!invoiceDateInput.value) return;
        const base = new Date(invoiceDateInput.value + 'T00:00:00');
        base.setDate(base.getDate() + days);
        dueDateInput.value = base.toISOString().slice(0, 10);
    }

    paymentTermsSelect.addEventListener('change', recalcDueDate);
    invoiceDateInput.addEventListener('change', recalcDueDate);
    if (!dueDateInput.value) recalcDueDate();

    customerSelect.addEventListener('change', () => {
        const selected = customerSelect.options[customerSelect.selectedIndex];
        document.getElementById('billing_address').value = selected.dataset.address || '';
        document.getElementById('billing_email').value = selected.dataset.email || '';
        document.getElementById('billing_phone').value = selected.dataset.phone || '';
    });

    recalcAll();
})();
</script>
@endpush