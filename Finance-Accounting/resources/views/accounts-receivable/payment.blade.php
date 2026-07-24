@extends('layouts.app')

@section('page-title', 'Record Payment - Finance & Accounting')
@section('page-title-heading', 'Record Payment')
@section('page-subtitle', 'Accounts Receivable > Record Payment')

@section('content')
<form id="paymentForm" onsubmit="return submitPayment(event)">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-card p-6 min-h-[700px]">
            <h2 class="text-lg font-bold text-navy mb-6 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-navy-600"></i> Payment Information
            </h2>
            <div class="mb-5">
                <label class="block mb-1.5 text-sm font-semibold text-slate-700">Customer</label>
                <select id="customerSelect" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy/20 focus:border-navy">
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block mb-1.5 text-sm font-semibold text-slate-700">Payment Date</label>
                <input id="paymentDate" type="date" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy/20 focus:border-navy">
            </div>
            <div class="mb-5">
                <label class="block mb-1.5 text-sm font-semibold text-slate-700">Payment Method</label>
                <select id="paymentMethod" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy/20 focus:border-navy">
                    <option>Bank Transfer</option>
                    <option>Cash</option>
                    <option>GCash</option>
                    <option>Cheque</option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block mb-1.5 text-sm font-semibold text-slate-700">Reference No.</label>
                <input type="text" id="referenceNo" value="Auto Generated" readonly class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50 text-slate-500 font-medium cursor-not-allowed">
            </div>
            <div>
                <label class="block mb-1.5 text-sm font-semibold text-slate-700">Remarks</label>
                <textarea id="remarks" rows="4" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-navy/20 focus:border-navy" placeholder="Add optional payment details..."></textarea>
            </div>
        </div>

        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-card p-6">
                <h2 class="text-lg font-bold text-navy mb-5 flex items-center gap-2">
                    <i data-lucide="file-spreadsheet" class="w-5 h-5 text-navy-600"></i> Payment Apply To
                </h2>
                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                            <tr>
                                <th class="p-3 w-12 text-center"></th>
                                <th class="text-left p-3">Invoice No.</th>
                                <th class="text-left p-3">Invoice Date</th>
                                <th class="text-left p-3">Due Date</th>
                                <th class="text-right p-3">Amount</th>
                                <th class="text-right p-3">Balance</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTable" class="divide-y divide-slate-100 text-slate-700">
                            <tr><td colspan="6" class="text-center py-8 text-slate-400">Select a customer to see their open invoices.</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    <label class="block mb-2 text-sm font-bold text-navy">Amount Paid</label>
                    <div class="flex max-w-xs shadow-sm">
                        <span class="bg-slate-50 border border-slate-200 border-r-0 rounded-l-xl px-4 flex items-center text-slate-500 font-semibold">₱</span>
                        <input id="amountPaid" type="number" step="0.01" class="w-full border border-slate-200 rounded-r-xl px-4 py-3 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-navy/20 focus:border-navy" placeholder="0.00">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-card p-6">
                <h2 class="text-lg font-bold text-navy mb-6 flex items-center gap-2">
                    <i data-lucide="calculator" class="w-5 h-5 text-navy-600"></i> Payment Summary
                </h2>
                <div class="space-y-4 text-sm text-slate-600">
                    <div class="flex justify-between items-center"><span>Total Selected Invoices</span><span id="selectedTotal" class="font-semibold text-slate-900 text-base">₱0.00</span></div>
                    <div class="flex justify-between items-center"><span>Amount Paid</span><span id="amountPaidDisplay" class="text-brand-blue text-xl font-bold">₱0.00</span></div>
                    <hr class="border-slate-100">
                    <div class="flex justify-between items-center"><span>Remaining Balance</span><span id="remainingBalance" class="text-brand-greenDark font-bold text-lg">₱0.00</span></div>
                    <div class="flex justify-between items-center"><span>Payment Status</span><span id="paymentStatus" class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Pending</span></div>
                </div>
                <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-slate-100">
                    <a href="{{ url('/accounts-receivable') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Cancel</a>
                    <button type="submit" id="submitBtn" class="bg-navy hover:bg-navy-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="successModal" class="fixed inset-0 bg-navy/55 hidden items-center justify-center z-[100] p-4 transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative animate-in fade-in zoom-in duration-200">
        <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 flex items-center justify-center mb-4"><i data-lucide="badge-check" class="w-10 h-10 text-brand-green"></i></div>
        <h2 class="text-xl font-extrabold text-navy text-center">Payment Saved Successfully</h2>
        <div class="bg-slate-50 rounded-xl p-4 mt-5 text-sm space-y-2 border border-slate-100 text-slate-700">
            <div class="flex justify-between"><strong class="text-slate-500">Payment No:</strong> <span id="modalPaymentNo">—</span></div>
            <div class="flex justify-between"><strong class="text-slate-500">Customer:</strong> <span id="modalCustomer">—</span></div>
            <div class="flex justify-between"><strong class="text-slate-500">Amount Paid:</strong> <span id="modalAmount">—</span></div>
            <div class="flex justify-between"><strong class="text-slate-500">Date:</strong> <span id="modalDate">—</span></div>
        </div>
        <div class="flex justify-center gap-3 mt-6">
            <button onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Close</button>
            <a href="{{ url('/accounts-receivable') }}" class="flex-1 px-4 py-2.5 bg-navy hover:bg-navy-700 text-white rounded-xl text-sm font-semibold text-center transition">View Invoices</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.canManageAR = @json(\App\Models\Role::activeRoleCanManageAR());
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const STORE_URL = "{{ route('receivable.storePayment') }}";
const CUSTOMER_INVOICES_URL_BASE = "{{ url('/accounts-receivable/customers') }}";

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

function formatCurrency(amount) {
    return '₱' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' });
}

document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('customerSelect');
    const amountInput = document.getElementById('amountPaid');
    if (select) { select.addEventListener('change', onCustomerChange); }
    if (amountInput) { amountInput.addEventListener('input', updateSummary); }
});

async function onCustomerChange() {
    const customerId = document.getElementById('customerSelect').value;
    const table = document.getElementById('invoiceTable');
    table.innerHTML = '';

    if (customerId === '') {
        table.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-slate-400">Select a customer to see their open invoices.</td></tr>`;
        updateSummary();
        return;
    }

    table.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-slate-400">Loading invoices...</td></tr>`;
    try {
        const response = await fetch(`${CUSTOMER_INVOICES_URL_BASE}/${customerId}/invoices`, { headers: { 'Accept': 'application/json' } });
        if (!response.ok) throw new Error('Failed to load invoices');
        const openInvoices = await response.json();
        table.innerHTML = '';

        if (openInvoices.length === 0) {
            table.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-slate-400">This customer has no open invoices.</td></tr>`;
            updateSummary();
            return;
        }

        openInvoices.forEach(invoice => {
            table.innerHTML += `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="text-center p-3"><input type="checkbox" class="invoice-check rounded border-slate-300 text-navy focus:ring-navy" value="${invoice.id}" data-balance="${invoice.balance}"></td>
                    <td class="p-3 font-semibold text-slate-800">${invoice.invoice_number}</td>
                    <td class="p-3 text-slate-500">${formatDate(invoice.invoice_date)}</td>
                    <td class="p-3 text-slate-500">${formatDate(invoice.due_date)}</td>
                    <td class="text-right p-3 font-medium">${formatCurrency(invoice.total)}</td>
                    <td class="text-right p-3 font-semibold text-slate-900">${formatCurrency(invoice.balance)}</td>
                </tr>`;
        });

        document.querySelectorAll('.invoice-check').forEach(box => box.addEventListener('change', updateSummary));
        updateSummary();
    } catch (err) {
        table.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-red-400">Could not load invoices. Please try again.</td></tr>`;
        updateSummary();
    }
}

function updateSummary() {
    let total = 0;
    document.querySelectorAll('.invoice-check:checked').forEach(item => { total += Number(item.dataset.balance); });
    document.getElementById('selectedTotal').innerHTML = formatCurrency(total);
    let paid = Number(document.getElementById('amountPaid').value) || 0;
    document.getElementById('amountPaidDisplay').innerHTML = formatCurrency(paid);
    document.getElementById('remainingBalance').innerHTML = formatCurrency(total - paid);

    let status = document.getElementById('paymentStatus');
    status.className = "px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider";
    if (total === 0) { status.innerText = 'Pending'; status.classList.add('bg-slate-100', 'text-slate-600'); } 
    else if (paid >= total) { status.innerText = 'Paid'; status.classList.add('bg-emerald-100', 'text-brand-greenDark'); } 
    else { status.innerText = 'Partial'; status.classList.add('bg-amber-100', 'text-brand-orange'); }
}

async function submitPayment(e) {
    e.preventDefault();
    if(!window.canManageAR) { showAccessDeniedModal(); return false; }

    const customerId = document.getElementById('customerSelect').value;
    const customerName = document.getElementById('customerSelect').selectedOptions[0]?.text ?? '';
    const paymentDate = document.getElementById('paymentDate').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    const remarks = document.getElementById('remarks').value;
    const amountPaid = Number(document.getElementById('amountPaid').value) || 0;
    const checkedInvoices = Array.from(document.querySelectorAll('.invoice-check:checked')).map(cb => Number(cb.value));

    if (customerId === '' || checkedInvoices.length === 0 || amountPaid <= 0) {
        AppUI.showToast('Please complete selection criteria validation.', 'error');
        return false;
    }

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;

    try {
        const response = await fetch(STORE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ customer_id: Number(customerId), payment_date: paymentDate, payment_method: paymentMethod, remarks: remarks || null, amount: amountPaid, invoice_id: checkedInvoices })
        });

        if (!response.ok) {
            const errorBody = await response.json().catch(() => null);
            AppUI.showToast(errorBody?.message || 'Failed to save payment.', 'error');
            return false;
        }

        const result = await response.json();
        document.getElementById('referenceNo').value = result.payment_no;
        document.getElementById('modalPaymentNo').innerText = result.payment_no;
        document.getElementById('modalCustomer').innerText = result.customer || customerName;
        document.getElementById('modalAmount').innerText = '₱' + result.amount;
        document.getElementById('modalDate').innerText = result.date;

        const modal = document.getElementById('successModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');

        document.getElementById('amountPaid').value = '';
        onCustomerChange();
    } catch (err) {
        AppUI.showToast('Something went wrong while saving the payment.', 'error');
    } finally {
        submitBtn.disabled = false;
    }
    return false;
}

function closeModal(){
    const modal = document.getElementById('successModal');
    modal.classList.remove('flex'); modal.classList.add('hidden');
}
</script>
@endpush