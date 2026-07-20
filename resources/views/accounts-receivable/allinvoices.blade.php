@extends('layouts.app')

@section('page-title', 'All Invoices | Finance & Accounting')

@section('page-title-heading', 'All Invoices')
@section('page-subtitle')
    Accounts Receivable &gt; <span class="font-semibold">All Invoices</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- ================= SUMMARY CARDS ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Invoices -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-200/80 p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                Total Invoices
            </p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-navy mt-2">
                {{ $totalInvoices }}
            </h2>
        </div>

        <!-- Paid -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-200/80 p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                Paid
            </p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-brand-green mt-2">
                {{ $paidInvoices }}
            </h2>
        </div>

        <!-- Unpaid -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-200/80 p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                Unpaid
            </p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-brand-orange mt-2">
                {{ $unpaidInvoices }}
            </h2>
        </div>

        <!-- Overdue -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-200/80 p-6">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                Overdue
            </p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-brand-red mt-2">
                {{ $overdueInvoices }}
            </h2>
        </div>
    </div>

    <!-- ================= ACTION BUTTONS ================= -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-2xl shadow-card border border-slate-200/80 p-4 no-print">
        <p class="text-sm font-semibold text-slate-500">Quick Operations:</p>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('receivable.dashboard') }}"
               class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl transition text-sm font-semibold">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Dashboard
            </a>
            <a href="{{ route('receivable.invoice') }}"
               class="flex items-center gap-2 bg-brand-green hover:bg-brand-greenDark text-white px-5 py-2.5 rounded-xl transition text-sm font-semibold">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Create Invoice
            </a>
        </div>
    </div>

    <!-- ================= SEARCH & FILTER ================= -->
    <div class="bg-white rounded-2xl shadow-card border border-slate-200/80 p-6 no-print">
        <form id="filterForm" method="GET" action="{{ route('receivable.allinvoices') }}">
            <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        value="{{ request('search') }}"
                        placeholder="Search invoice number or customer..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy/20 focus:border-navy transition">
                </div>

                <!-- Filters & CTA -->
                <div class="flex flex-wrap items-center gap-3">
                    <select
                        name="status"
                        id="statusFilter"
                        class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy/20 focus:border-navy transition min-w-[140px]">
                        <option value="">All Statuses</option>
                        <option value="Paid" {{ request('status') === 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Unpaid" {{ request('status') === 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Partial" {{ request('status') === 'Partial' ? 'selected' : '' }}>Partial</option>
                        <option value="Overdue" {{ request('status') === 'Overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>

                    <button
                        type="submit"
                        class="flex items-center gap-2 bg-navy hover:bg-navy-700 text-white px-6 py-3 rounded-xl font-semibold text-sm transition">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ================= TABLE ================= -->
    <div class="bg-white rounded-2xl shadow-card border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 font-bold text-navy text-sm">Invoice No.</th>
                        <th class="px-6 py-4 font-bold text-navy text-sm">Customer</th>
                        <th class="px-6 py-4 font-bold text-navy text-sm">Invoice Date</th>
                        <th class="px-6 py-4 font-bold text-navy text-sm">Due Date</th>
                        <th class="px-6 py-4 font-bold text-navy text-sm text-right">Amount</th>
                        <th class="px-6 py-4 font-bold text-navy text-sm text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-navy text-sm text-center no-print">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 font-bold text-navy">
                                {{ $invoice->invoice_number }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">
                                {{ $invoice->customer->customer_name ?? 'No Customer' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-700">
                                &#8369;{{ number_format($invoice->total, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $badgeClass = match($invoice->status) {
                                        'Paid' => 'bg-brand-green/10 text-brand-greenDark font-semibold',
                                        'Unpaid' => 'bg-brand-orange/10 text-brand-orange font-semibold',
                                        'Partial' => 'bg-yellow-100 text-yellow-700 font-semibold',
                                        default => 'bg-brand-red/10 text-brand-red font-semibold',
                                    };
                                @endphp
                                <span class="{{ $badgeClass }} px-3 py-1 rounded-full text-xs">
                                    {{ $invoice->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center no-print">
                                <div class="flex justify-center gap-1.5">
                                    <button
                                        type="button"
                                        onclick="openInvoiceDetailsModal({{ $invoice->id }})"
                                        class="w-9 h-9 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center transition"
                                        title="View Details">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <button
                                        type="button"
                                        onclick="openEditInvoiceModal({{ $invoice->id }})"
                                        class="w-9 h-9 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center transition"
                                        title="Edit Invoice">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <button
                                        type="button"
                                        onclick="deleteInvoice({{ $invoice->id }})"
                                        class="w-9 h-9 rounded-lg border border-red-100 bg-red-50 hover:bg-red-100 text-brand-red flex items-center justify-center transition"
                                        title="Delete Invoice">
                                        <i data-lucide="trash" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">
                                No matching invoices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= PAGINATION ================= -->
    <div class="bg-white rounded-2xl shadow-card border border-slate-200/80 px-6 py-4 no-print">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Showing info -->
            <div class="text-sm text-slate-500">
                Showing
                <span class="font-bold text-navy">{{ $invoices->total() === 0 ? 0 : $invoices->firstItem() }}</span>
                to
                <span class="font-bold text-navy">{{ $invoices->total() === 0 ? 0 : $invoices->lastItem() }}</span>
                of
                <span class="font-bold text-navy">{{ $invoices->total() }}</span>
                invoices
            </div>

            <!-- Pagination buttons -->
            <div class="flex items-center gap-1.5">
                @php
                    $btnClass = "w-9 h-9 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-600 transition text-sm";
                    $activeClass = "w-9 h-9 rounded-lg bg-navy text-white flex items-center justify-center font-semibold text-sm";
                    $disabledClass = "w-9 h-9 rounded-lg border border-slate-100 flex items-center justify-center text-slate-300 pointer-events-none";
                @endphp

                @if ($invoices->onFirstPage())
                    <span class="{{ $disabledClass }}"><i data-lucide="chevron-left" class="w-4 h-4"></i></span>
                @else
                    <a href="{{ $invoices->previousPageUrl() }}" class="{{ $btnClass }}"><i data-lucide="chevron-left" class="w-4 h-4"></i></a>
                @endif

                @for ($page = 1; $page <= $invoices->lastPage(); $page++)
                    @if ($page === $invoices->currentPage())
                        <span class="{{ $activeClass }}">{{ $page }}</span>
                    @else
                        <a href="{{ $invoices->url($page) }}" class="{{ $btnClass }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($invoices->hasMorePages())
                    <a href="{{ $invoices->nextPageUrl() }}" class="{{ $btnClass }}"><i data-lucide="chevron-right" class="w-4 h-4"></i></a>
                @else
                    <span class="{{ $disabledClass }}"><i data-lucide="chevron-right" class="w-4 h-4"></i></span>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<!-- ================================================================
     LIVE DATA FROM DATABASE (current page only) + MODAL/ACTION LOGIC
     ================================================================ -->
<script>
// Only the invoices on THIS page are needed client-side, for the
// details/edit modals. Search, filtering, and pagination are all
// handled server-side by AccountsReceivableController@allInvoices.
const invoicesData = @json($invoices->items());
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;

// ================= HELPERS =================

function formatCurrency(amount) {
    return '₱' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDateLong(dateStr) {

    if(!dateStr){
        return 'N/A';
    }

    const d = new Date(dateStr);

    return d.toLocaleDateString('en-US',{
        year:'numeric',
        month:'long',
        day:'numeric'
    });

}


function statusBadgeClasses(status) {
    if (status === 'Paid') return 'bg-brand-green/10 text-brand-greenDark font-semibold';
    if (status === 'Unpaid') return 'bg-brand-orange/10 text-brand-orange font-semibold';
    if (status === 'Partial') return 'bg-blue-50 text-blue-600 font-semibold';
    return 'bg-brand-red/10 text-brand-red font-semibold';
}

// ================= MODAL BUILDERS =================

// 1. Details Modal Builder
function openInvoiceDetailsModal(id) {
    const invoice = invoicesData.find(inv => inv.id == id);
    if (!invoice) return;

    const badgeClass = statusBadgeClasses(invoice.status);

    let itemsRows = '';
    invoice.items.forEach(item => {
        const itemAmount = item.quantity * item.unit_price;
        itemsRows += `
            <tr class="border-b border-slate-100 text-sm">
                <td class="px-4 py-3 text-slate-600 font-medium">${item.description}</td>
                <td class="px-4 py-3 text-center text-slate-500">${item.quantity}</td>
                <td class="px-4 py-3 text-right text-slate-500">${formatCurrency(item.unit_price)}</td>
                <td class="px-4 py-3 text-right font-semibold text-slate-700">${formatCurrency(itemAmount)}</td>
            </tr>`;
    });

    const modalHTML = `
        <div class="space-y-6">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-xl font-bold text-navy">Invoice Details</h3>
                <p class="text-xs text-slate-400 mt-0.5">Reference ID: #${invoice.id}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-xs sm:text-sm space-y-1">
                    <h4 class="font-bold text-navy mb-2 uppercase tracking-wide text-xs">Invoice Info</h4>
                    <p><span class="text-slate-400">Invoice No:</span> <strong class="text-slate-700">${invoice.invoice_number}</strong></p>
                    <p><span class="text-slate-400">Invoice Date:</span> <strong class="text-slate-700">${formatDateLong(invoice.invoice_date)}</strong></p>
                    <p><span class="text-slate-400">Due Date:</span> <strong class="text-slate-700">${formatDateLong(invoice.due_date)}</strong></p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-xs sm:text-sm space-y-1">
                    <h4 class="font-bold text-navy mb-2 uppercase tracking-wide text-xs">Customer Info</h4>
                   <p><span class="text-slate-400">Name:</span> <strong class="text-slate-700">${invoice.customer?.customer_name ?? ''}</strong></p>
                   <p><span class="text-slate-400">Company:</span> <strong class="text-slate-700">${invoice.customer?.company ?? ''}</strong></p>
                   <p><span class="text-slate-400">Email:</span> <strong class="text-slate-700">${invoice.customer?.email ?? ''}</strong></p>
                   </div>
                </div>

                <div class="border border-slate-150 rounded-xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-150 text-xs uppercase text-slate-400 font-bold">
                        <tr>
                            <th class="px-4 py-2.5">Description</th>
                            <th class="px-4 py-2.5 text-center">Qty</th>
                            <th class="px-4 py-2.5 text-right">Unit Price</th>
                            <th class="px-4 py-2.5 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsRows}
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <div class="w-full sm:w-72 bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm space-y-2">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal</span>
                        <span>${formatCurrency(invoice.subtotal)}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Tax (12%)</span>
                        <span>${formatCurrency(invoice.tax)}</span>
                    </div>
                    <hr class="border-slate-200">
                    <div class="flex justify-between text-base font-bold text-navy">
                        <span>Total</span>
                        <span>${formatCurrency(invoice.total)}</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-brand-red">
                        <span>Balance</span>
                        <span>${formatCurrency(invoice.balance)}</span>
                    </div>
                    <div class="pt-2 text-center">
                        <span class="${badgeClass} px-4 py-1.5 rounded-full text-xs">
                            ${invoice.status}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="button" onclick="AppUI.closeModal()"
                        class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">
                    Close
                </button>
            </div>
        </div>
    `;

    AppUI.openModal(modalHTML, 'lg');
}

// 2. Edit Modal Builder
function openEditInvoiceModal(id) {
    const invoice = invoicesData.find(inv => inv.id == id);
    if (!invoice) return;

    let itemsFormHtml = '';
    invoice.items.forEach((item) => {
        itemsFormHtml += `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 border-b border-slate-100 pb-3 items-center">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Description</label>
                    <input class="edit-description w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-navy focus:border-navy"
                           value="${item.description}">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1 text-center">Qty</label>
                    <input type="number" class="edit-quantity text-center w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-navy focus:border-navy"
                           value="${item.quantity}" oninput="calculateEditTotal()">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1 text-right">Unit Price (₱)</label>
                    <input type="number" class="edit-price text-right w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-navy focus:border-navy"
                           value="${item.unit_price}" oninput="calculateEditTotal()">
                </div>
            </div>`;
    });

    const modalHTML = `
        <div class="space-y-6">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-xl font-bold text-navy">Edit Invoice</h3>
                <p class="text-xs text-slate-400 mt-0.5">Quick update settings</p>
            </div>

            <form id="editInvoiceForm" class="space-y-4" onsubmit="event.preventDefault(); saveInvoiceEdit(${invoice.id});">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Invoice Number</label>
                        <input id="editInvoiceNumber" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-500 outline-none" value="${invoice.invoice_number}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Customer</label>
                        <input id="editCustomer" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-500 outline-none" value="$${invoice.customer?.customer_name ?? ''}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Invoice Date</label>
                        <input type="date" id="editInvoiceDate" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-navy/20 focus:border-navy" value="${invoice.invoice_date}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Due Date</label>
                        <input type="date" id="editDueDate" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-navy/20 focus:border-navy" value="${invoice.due_date}">
                    </div>
                </div>

                <hr class="border-slate-100 my-4">

                <h4 class="text-sm font-bold text-navy mb-3">Line Items</h4>
                <div id="editItemsContainer" class="space-y-3 max-h-52 overflow-y-auto pr-1">
                    ${itemsFormHtml}
                </div>

                <div class="flex justify-end pt-3">
                    <div class="w-72 border border-slate-100 rounded-xl bg-slate-50 p-4 text-xs space-y-2">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span id="editSubtotal">${formatCurrency(invoice.subtotal)}</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Tax (12%)</span>
                            <span id="editTax">${formatCurrency(invoice.tax)}</span>
                        </div>
                        <hr class="border-slate-200">
                        <div class="flex justify-between text-sm font-bold text-navy">
                            <span>New Total</span>
                            <span id="editTotal">${formatCurrency(invoice.total)}</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="AppUI.closeModal()"
                            class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700 transition shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    `;

    AppUI.openModal(modalHTML, 'lg');
}

// 3. Dynamic Edit Calculations
function calculateEditTotal() {
    const quantities = document.querySelectorAll('.edit-quantity');
    const prices = document.querySelectorAll('.edit-price');
    let subtotal = 0;

    quantities.forEach((qty, index) => {
        subtotal += Number(qty.value) * Number(prices[index].value);
    });

    const tax = subtotal * 0.12;
    const total = subtotal + tax;

    document.getElementById('editSubtotal').innerText = formatCurrency(subtotal);
    document.getElementById('editTax').innerText = formatCurrency(tax);
    document.getElementById('editTotal').innerText = formatCurrency(total);
}

// 4. Save edit -> real PUT request to AccountsReceivableController
// @update

function saveInvoiceEdit(id) {
    const descriptions = document.querySelectorAll('.edit-description');
    const quantities = document.querySelectorAll('.edit-quantity');
    const prices = document.querySelectorAll('.edit-price');

    const payload = {
        invoice_date: document.getElementById('editInvoiceDate').value,
        due_date: document.getElementById('editDueDate').value,
        description: [],
        quantity: [],
        unit_price: []
    };

    descriptions.forEach((item, index) => {
        payload.description.push(item.value);
        payload.quantity.push(quantities[index].value);
        payload.unit_price.push(prices[index].value);
    });

    fetch(`/accounts-receivable/invoice/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify(payload)
    })
        .then(res => res.json())
        .then(data => {
            AppUI.closeModal();
            if (data.success) {
                if (typeof AppUI.showToast === 'function') {
                    AppUI.showToast(data.message || 'Invoice updated successfully.', 'success');
                }
                window.location.reload();
            } else {
                if (typeof AppUI.showToast === 'function') {
                    AppUI.showToast('Something went wrong while updating the invoice.', 'error');
                }
            }
        })
        .catch(() => {
            AppUI.closeModal();
            if (typeof AppUI.showToast === 'function') {
                AppUI.showToast('Something went wrong while updating the invoice.', 'error');
            }
        });
}

// ================= ACTIONS =================

// Delete -> real DELETE request to AccountsReceivableController@destroy

function deleteInvoice(id) {
    if (!confirm("Are you sure you want to delete this invoice?")) return;

    fetch(`/accounts-receivable/invoice/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    })
        .then(() => {
            window.location.reload();
        })
        .catch(() => {
            if (typeof AppUI.showToast === 'function') {
                AppUI.showToast('Something went wrong while deleting the invoice.', 'error');
            }
        });
}
</script>
@endpush