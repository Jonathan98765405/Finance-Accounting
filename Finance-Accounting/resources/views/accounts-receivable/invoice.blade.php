@extends('layouts.app')
@section('page-title', 'Finance & Accounting | Create Invoice')
@section('page-title-heading', 'Create New Invoice')
@section('page-subtitle', 'Accounts Receivable > Create Invoice.')

@section('content')
<div class="w-full">
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-brand-red/30 bg-brand-red/10 p-4 text-brand-red shadow-sm">
            <p class="font-bold mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="invoiceForm" method="POST" action="{{ route('receivable.invoice.store') }}" onsubmit="return submitInvoice(event)">
        @csrf
        <input type="hidden" name="subtotal" id="subtotalInput" value="0">
        <input type="hidden" name="tax" id="taxInput" value="0">
        <input type="hidden" name="total" id="totalInput" value="0">
        <input type="hidden" name="balance" id="balanceInput" value="0">

        <div class="flex items-center justify-end mb-6">
            <div class="flex gap-3">
                <a href="{{ route('receivable.dashboard') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 font-medium hover:bg-slate-50 transition shadow-sm">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-navy text-white font-medium hover:bg-navy-700 transition shadow-card flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Invoice
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-card p-7 border border-slate-100">
                <h2 class="text-lg font-bold text-navy mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-brand-blue"></i> Customer Information
                </h2>
                <div class="space-y-5">
                    <div>
                        <label class="block mb-2 font-medium text-sm text-slate-700">Customer</label>
                        <select id="customerSelect" name="customer_id" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-sm text-slate-700">Address</label>
                        <textarea id="address" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-3 resize-none outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition" readonly></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 font-medium text-sm text-slate-700">Email</label>
                            <input type="email" id="email" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition bg-slate-50" readonly>
                        </div>
                        <div>
                            <label class="block mb-2 font-medium text-sm text-slate-700">Phone Number</label>
                            <input type="text" id="phone" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition bg-slate-50" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card p-7 border border-slate-100">
                <h2 class="text-lg font-bold text-navy mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-brand-orange"></i> Invoice Information
                </h2>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block mb-2 font-medium text-sm text-slate-700">Invoice Number</label>
                        <input type="text" id="invoiceNumber" readonly class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 text-slate-500 cursor-not-allowed outline-none">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-sm text-slate-700">Invoice Date</label>
                        <input type="date" id="invoiceDate" name="invoice_date" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-sm text-slate-700">Due Date</label>
                        <input type="date" id="dueDate" name="due_date" value="{{ old('due_date') }}" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition">
                    </div>
                    <div>
                        <label class="block mb-2 font-medium text-sm text-slate-700">Payment Terms</label>
                        <select id="paymentTerms" name="payment_terms" class="w-full border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition">
                            <option value="Net 30" {{ old('payment_terms') === 'Net 30' ? 'selected' : '' }}>Net 30</option>
                            <option value="Net 15" {{ old('payment_terms') === 'Net 15' ? 'selected' : '' }}>Net 15</option>
                            <option value="COD" {{ old('payment_terms') === 'COD' ? 'selected' : '' }}>COD</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-6">
            <div class="lg:col-span-3 bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-navy flex items-center gap-2">
                        <i data-lucide="list" class="w-5 h-5 text-brand-blue"></i> Invoice Items
                    </h2>
                    <button type="button" onclick="addRow()" class="bg-navy/5 hover:bg-navy/10 text-navy px-4 py-2 rounded-lg font-medium transition flex items-center gap-2 text-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Item
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full" id="itemsTable">
                        <thead class="bg-slate-50 border-b border-slate-100 text-sm">
                            <tr>
                                <th class="text-left px-6 py-4 font-semibold text-slate-600">Description</th>
                                <th class="text-center px-4 py-4 font-semibold text-slate-600 w-32">Qty</th>
                                <th class="text-right px-4 py-4 font-semibold text-slate-600 w-44">Unit Price</th>
                                <th class="text-right px-4 py-4 font-semibold text-slate-600 w-44">Amount</th>
                                <th class="text-center px-4 py-4 font-semibold text-slate-600 w-20">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="px-6 py-4"><input class="description w-full border border-slate-200 rounded-xl px-4 py-2.5 outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition text-sm" type="text" name="description[]" placeholder="Enter Item Description"></td>
                                <td class="px-4 py-4"><input type="number" name="quantity[]" value="1" min="1" class="qty w-full border border-slate-200 rounded-xl px-3 py-2.5 text-center outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition text-sm"></td>
                                <td class="px-4 py-4"><input type="number" name="unit_price[]" step="0.01" value="0" class="price w-full border border-slate-200 rounded-xl px-3 py-2.5 text-right outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition text-sm"></td>
                                <td class="px-4 py-4"><input type="text" readonly class="amount w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-right text-slate-500 cursor-not-allowed outline-none text-sm" value="₱0.00"></td>
                                <td class="px-4 py-4 text-center">
                                    <button type="button" onclick="removeRow(this)" class="text-slate-400 hover:text-brand-red transition p-2 rounded-lg hover:bg-red-50">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card border border-slate-100 h-fit">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-navy flex items-center gap-2">
                        <i data-lucide="calculator" class="w-5 h-5 text-brand-orange"></i> Summary
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm"><span>Subtotal</span><span class="font-semibold text-slate-700" id="subtotal">₱0.00</span></div>
                    <div class="flex justify-between items-center text-sm"><span>Tax (12%)</span><span class="font-semibold text-slate-700" id="tax">₱0.00</span></div>
                    <div class="flex justify-between items-center text-sm border-b border-slate-100 pb-4"><span>Discount</span><span class="font-semibold text-slate-700">₱0.00</span></div>
                    <div class="flex justify-between items-center pt-2"><span>Total</span><span class="text-xl font-bold text-brand-greenDark" id="total">₱0.00</span></div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    window.canManageAR = @json(\App\Models\Role::activeRoleCanManageAR());
    const customers = @json($customers);

    function showAccessDeniedModal() {
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
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('invoiceNumber').value = generateInvoiceNumber();
        calculateTotals();
        document.getElementById('customerSelect').addEventListener('change', function () {
            const customer = customers.find(c => c.id === Number(this.value));
            document.getElementById('address').value = customer ? (customer.address ?? '') : '';
            document.getElementById('email').value = customer ? (customer.email ?? '') : '';
            document.getElementById('phone').value = customer ? (customer.phone ?? '') : '';
        });
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    });

    function generateInvoiceNumber() {
        const now = new Date();
        const pad = (num) => String(num).padStart(2, '0');
        return 'INV-' + now.getFullYear() + pad(now.getMonth() + 1) + pad(now.getDate()) + pad(now.getHours()) + pad(now.getMinutes()) + pad(now.getSeconds());
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll("#itemsTable tbody tr").forEach(function(row){
            let qty = parseFloat(row.querySelector(".qty").value) || 0;
            let price = parseFloat(row.querySelector(".price").value) || 0;
            let amount = qty * price;
            row.querySelector(".amount").value = "₱" + amount.toLocaleString(undefined,{ minimumFractionDigits:2, maximumFractionDigits:2 });
            subtotal += amount;
        });
        let tax = subtotal * 0.12;
        let total = subtotal + tax;
        document.getElementById("subtotal").innerHTML = "₱" + subtotal.toLocaleString(undefined,{minimumFractionDigits:2});
        document.getElementById("tax").innerHTML = "₱" + tax.toLocaleString(undefined,{minimumFractionDigits:2});
        document.getElementById("total").innerHTML = "₱" + total.toLocaleString(undefined,{minimumFractionDigits:2});
        document.getElementById('subtotalInput').value = subtotal;
        document.getElementById('taxInput').value = tax;
        document.getElementById('totalInput').value = total;
        document.getElementById('balanceInput').value = total;
    }

    document.addEventListener("input", function(e){
        if(e.target.classList.contains("qty") || e.target.classList.contains("price")){ calculateTotals(); }
    });

    function addRow(){
        if(!window.canManageAR) { showAccessDeniedModal(); return; }
        let tbody = document.querySelector("#itemsTable tbody");
        let row = tbody.rows[0].cloneNode(true);
        row.querySelector('.description').value = "";
        row.querySelector(".qty").value = 1;
        row.querySelector(".price").value = 0;
        row.querySelector(".amount").value = "₱0.00";
        tbody.appendChild(row);
        calculateTotals();
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    }

    function removeRow(btn){
        if(!window.canManageAR) { showAccessDeniedModal(); return; }
        let tbody = document.querySelector("#itemsTable tbody");
        if(tbody.rows.length > 1){ btn.closest("tr").remove(); calculateTotals(); }
    }

    function submitInvoice(e) {
        if(!window.canManageAR) { e.preventDefault(); showAccessDeniedModal(); return false; }
        const customerId = document.getElementById('customerSelect').value;
        if (customerId === '') { e.preventDefault(); alert('Please select a customer.'); return false; }
        const descriptions = document.querySelectorAll('#itemsTable .description');
        const hasItem = Array.from(descriptions).some(input => input.value.trim() !== '');
        if (!hasItem) { e.preventDefault(); alert('Please add at least one invoice item.'); return false; }
        calculateTotals();
        return true;
    }
</script>
@endsection