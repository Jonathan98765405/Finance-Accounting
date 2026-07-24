{{-- Shared fields for create.blade.php and edit.blade.php --}}

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="customer_id">Customer</label>
    <select id="customer_id" name="customer_id" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">
        <option value="">Select a customer</option>
        @php $currentCustomerId = old('customer_id', $invoice->customer_id ?? ''); @endphp
        @foreach ($customers as $customer)
            <option value="{{ $customer->id }}" @selected((string) $currentCustomerId === (string) $customer->id)>{{ $customer->name }}</option>
        @endforeach
    </select>
    @error('customer_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="invoice_date">Invoice Date</label>
    <input
        id="invoice_date" name="invoice_date" type="date"
        value="{{ old('invoice_date', isset($invoice) ? $invoice->invoice_date->format('Y-m-d') : '') }}"
        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
    >
    @error('invoice_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="total_amount">Total Amount (₱)</label>
    <input
        id="total_amount" name="total_amount" type="number" step="0.01" min="0"
        value="{{ old('total_amount', $invoice->total_amount ?? '') }}"
        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500"
    >
    @error('total_amount') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="payment_status">
        Payment Status
    </label>

    <select
        id="payment_status"
        name="payment_status"
        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-500">

        @php
            $currentStatus = old('payment_status', $invoice->payment_status ?? 'Unpaid');
        @endphp

        <option value="Paid" @selected($currentStatus === 'Paid')>Paid</option>
        <option value="Unpaid" @selected($currentStatus === 'Unpaid')>Unpaid</option>
    </select>

    @error('payment_status')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>