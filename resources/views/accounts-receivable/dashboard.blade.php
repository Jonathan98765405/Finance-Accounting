//marcelo 2:34pm
@extends('layouts.app')

@section('page-title', 'Accounts Receivable')
@section('page-title-heading', 'Accounts Receivable')
@section('page-subtitle', 'Manage customer invoices, track payments, and monitor outstanding balances.')

@section('content')
<!-- Action Ribbon -->
<div class="flex flex-wrap items-center justify-end gap-3 mb-6 no-print">
    <a href="{{ url('/accounts-receivable/invoice') }}"
    class="inline-flex items-center gap-2 bg-navy text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:bg-navy/95 transition">
        <i data-lucide="plus" class="w-4 h-4"></i>
        New Invoice
    </a>
    <a href="{{ route('receivable.payment') }}" class="inline-flex items-center gap-2 bg-brand-green text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:bg-brand-green/95 transition">
        <i data-lucide="dollar-sign" class="w-4 h-4"></i>
        Record Payment
    </a>
    <button type="button" onclick="openReminderModal()" class="inline-flex items-center gap-2 bg-navy text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:bg-navy/95 transition">
        <i data-lucide="send" class="w-4 h-4"></i>
        Send Reminder
    </button>
    <button type="button" onclick="openReportModal()" class="inline-flex items-center gap-2 bg-white text-slate-700 border border-slate-200 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:bg-slate-50 transition">
        <i data-lucide="file-text" class="w-4 h-4"></i>
        Generate Report
    </button>
</div>

<!-- Financial Indicator Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Total Receivable -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl border border-slate-200 shadow-card p-6 flex items-center justify-between">
        <div>
            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Receivable</p>
            <h2 class="text-2xl font-extrabold text-navy mt-2" id="cardTotalReceivable">₱0.00</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-navy shrink-0">
            <i data-lucide="receipt" class="w-6 h-6"></i>
        </div>
    </div>

    <!-- Paid Invoices -->
    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl border border-slate-200 shadow-card p-6 flex items-center justify-between">
        <div>
            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Paid Invoices</p>
            <h2 class="text-2xl font-extrabold text-brand-green mt-2" id="cardTotalPaid">₱0.00</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-brand-green/10 flex items-center justify-center text-brand-green shrink-0">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
    </div>

    <!-- Unpaid Invoices -->
    <div class="bg-orange-50 border border-orange-100 rounded-2xl border border-slate-200 shadow-card p-6 flex items-center justify-between">
        <div>
            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Unpaid Invoices</p>
            <h2 class="text-2xl font-extrabold text-brand-orange mt-2" id="cardTotalUnpaid">₱0.00</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-brand-orange/10 flex items-center justify-center text-brand-orange shrink-0">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
    </div>

    <!-- Overdue Invoices -->
    <div class="bg-red-50 border border-red-100 rounded-2xl border border-slate-200 shadow-card p-6 flex items-center justify-between">
        <div>
            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Overdue Invoices</p>
            <h2 class="text-2xl font-extrabold text-brand-red mt-2" id="cardTotalOverdue">₱0.00</h2>
        </div>
        <div class="w-12 h-12 rounded-xl bg-brand-red/10 flex items-center justify-center text-brand-red shrink-0">
            <i data-lucide="alert-circle" class="w-6 h-6"></i>
        </div>
    </div>
</div>

<!-- Primary Dashboard Workspace Grid -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

    <!-- Left Layout Content Area -->
    <div class="xl:col-span-2 space-y-6">

        <!-- Invoices Datatable Card -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-navy text-lg">Invoices</h3>
                <a href="{{ route('receivable.allinvoices') }}" class="text-sm font-semibold text-navy hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3.5">Invoice No.</th>
                            <th class="px-6 py-3.5">Customer</th>
                            <th class="px-6 py-3.5">Invoice Date</th>
                            <th class="px-6 py-3.5">Due Date</th>
                            <th class="px-6 py-3.5 text-right">Amount</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-center no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody id="invoicesTableBody" class="divide-y divide-slate-100 text-slate-700">
                        <!-- Rendered systematically by JavaScript Engine -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payments Registry Area -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-navy text-lg">Recent Payments</h3>
                <button type="button" onclick="openAllPaymentsModal()" class="text-sm font-semibold text-navy hover:underline">View All</button>
            </div>
            <div id="recentPaymentsList" class="divide-y divide-slate-100">
                <!-- Rendered systematically by JavaScript Engine -->
            </div>
        </div>
    </div>

    <!-- Right Component Sidebar Column Widget Container -->
    <div class="space-y-6">

        <!-- Outstanding Customer Ledger Balances Widget -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-navy text-base">Outstanding Balances</h3>
                <a href="{{ route('receivable.aging') }}"
                class="text-xs font-semibold text-navy hover:underline">View All</a>
            </div>
            <div id="outstandingList" class="divide-y divide-slate-100 max-h-[320px] overflow-y-auto">
                <!-- Rendered systematically by JavaScript Engine -->
            </div>
        </div>

        <!-- Aging of Receivables Chart and Interactive Legend Widget -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-200 p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-navy text-base">Aging of Receivables</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Outstanding invoice distribution breakdown</p>
                </div>
                <a href="{{ route('receivable.aging') }}" class="text-xs font-semibold text-navy hover:underline">View All</a>
            </div>
            <div class="flex-1 flex items-center justify-center min-h-[160px] py-2">
                <div class="w-[140px] h-[140px] relative">
                    <canvas id="agingChart"></canvas>
                </div>
            </div>
            <div class="mt-4 space-y-2 text-xs font-medium text-slate-600">
                <div class="flex justify-between items-center p-1.5 rounded-lg hover:bg-slate-50">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-green shrink-0"></span>
                        <span>Current (0–30 Days)</span>
                    </div>
                    <span class="font-bold text-slate-800" id="agingCurrentLabel">₱0.00</span>
                </div>
                <div class="flex justify-between items-center p-1.5 rounded-lg hover:bg-slate-50">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-blue shrink-0"></span>
                        <span>31–60 Days</span>
                    </div>
                    <span class="font-bold text-slate-800" id="aging31_60Label">₱0.00</span>
                </div>
                <div class="flex justify-between items-center p-1.5 rounded-lg hover:bg-slate-50">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-orange shrink-0"></span>
                        <span>61–90 Days</span>
                    </div>
                    <span class="font-bold text-slate-800" id="aging61_90Label">₱0.00</span>
                </div>
                <div class="flex justify-between items-center p-1.5 rounded-lg hover:bg-slate-50">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-red shrink-0"></span>
                        <span>Over 90 Days</span>
                    </div>
                    <span class="font-bold text-slate-800" id="agingOver90Label">₱0.00</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions Alternative Context Panel Widget -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-200 p-5 no-print">
            <h3 class="font-bold text-navy text-sm mb-3">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-2 text-center text-xs font-semibold">
                <a href="{{ url('/accounts-receivable/invoice') }}"
                 class="flex flex-col items-center justify-center gap-1.5 bg-slate-50 border border-slate-200 hover:bg-navy hover:text-white hover:border-navy text-slate-700 p-3 rounded-xl transition group">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-navy group-hover:text-white transition"></i>
                    <span>New Invoice</span>
                </a>
                <a href="{{ route('receivable.payment') }}" class="flex flex-col items-center justify-center gap-1.5 bg-slate-50 border border-slate-200 hover:bg-brand-green hover:text-white hover:border-brand-green text-slate-700 p-3 rounded-xl transition group">
                    <i data-lucide="circle-dollar-sign" class="w-5 h-5 text-brand-green group-hover:text-white transition"></i>
                    <span>Payment</span>
                </a>
                <button type="button" onclick="openReminderModal()" class="flex flex-col items-center justify-center gap-1.5 bg-slate-50 border border-slate-200 hover:bg-brand-orange hover:text-white hover:border-brand-orange text-slate-700 p-3 rounded-xl transition group">
                    <i data-lucide="mail" class="w-5 h-5 text-brand-orange group-hover:text-white transition"></i>
                    <span>Reminder</span>
                </button>
                <button type="button" onclick="openReportModal()" class="flex flex-col items-center justify-center gap-1.5 bg-slate-50 border border-slate-200 hover:bg-navy hover:text-white hover:border-navy text-slate-700 p-3 rounded-xl transition group">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-navy group-hover:text-white transition"></i>
                    <span>Reports</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Inline Context-Sensitive Table Dropdown Menu Element Structure -->
<div id="actionMenu" class="hidden fixed w-40 bg-white border border-slate-200 rounded-xl shadow-lg z-[9999] p-1.5 text-sm font-medium">
    <button id="viewBtn" class="flex items-center w-full gap-2 px-3 py-2 text-slate-700 hover:bg-slate-50 rounded-lg text-left">
        <i data-lucide="eye" class="w-4 h-4 text-navy"></i> View
    </button>
    <button id="editBtn" class="flex items-center w-full gap-2 px-3 py-2 text-slate-700 hover:bg-slate-50 rounded-lg text-left">
        <i data-lucide="edit" class="w-4 h-4 text-brand-green"></i> Edit
    </button>
    <button id="deleteBtn" class="flex items-center w-full gap-2 px-3 py-2 text-brand-red hover:bg-brand-red/5 rounded-lg text-left">
        <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
    </button>
</div>
@endsection

@push('scripts')
<script>
// ================= REAL DATABASE DATA (INJECTED BY CONTROLLER) =================
// Sourced from AccountsReceivableController@dashboard, backed by the
// customers / invoices / invoice_items / payments migrations.

const CURRENT_DATE = new Date('{{ now()->format('Y-m-d') }}T00:00:00');

// All customers (customers table)
const customers = @json($customers);

// Most recent 7 invoices, with their line items (invoices + invoice_items tables)
let invoicesData = @json($invoices);


// Top 5 outstanding invoices by balance (invoices table, balance > 0)
const outstandingData = @json($outstanding);
// Most recent 5 payments, for the widget (payments table)
const recentPaymentsData = @json($recentPayments);

// All payments, for the "View All" modal (payments table)
const paymentsData = @json($allRecentPayments);
// Summary card totals, computed server-side across ALL invoices (not just the 7 shown)
const summaryTotals = {
    totalReceivable: {{ (float) $totalReceivable }},
    totalPaid: {{ (float) $totalPaid }},
    totalUnpaid: {{ (float) $totalUnpaid }},
    totalPartial: {{ (float) $totalPartial }},
    totalOverdue: {{ (float) $totalOverdue }}
};

// Aging of receivables buckets, computed server-side across ALL outstanding invoices
const agingTotals = {
    current: {{ (float) $current }},
    days31_60: {{ (float) $days31_60 }},
    days61_90: {{ (float) $days61_90 }},
    over90: {{ (float) $over90 }}
};

const reportTypes = [
    'Accounts Receivable Summary',
    'Outstanding Invoices',
    'Customer Statement',
    'Collection Report'
];

let agingChartInstance = null;

// ================= CORE FORMATTING UTILITIES =================
function formatCurrency(amount) {
    return '₱' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(dateStr) {

    if (!dateStr) {
        return 'N/A';
    }

    const d = new Date(dateStr);

    if (isNaN(d.getTime())) {
        return 'N/A';
    }

    return d.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: '2-digit'
    });
}

function formatDateLong(dateStr) {

    if (!dateStr) {
        return 'N/A';
    }

    const d = new Date(dateStr);

    if (isNaN(d.getTime())) {
        return 'N/A';
    }

    return d.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function getCustomer(customerId) {
    return customers.find(c => Number(c.id) === Number(customerId));
}

function daysBetween(dateStr) {

    if (!dateStr) {
        return 0;
    }

    const d = new Date(dateStr);

    if (isNaN(d.getTime())) {
        return 0;
    }

    return Math.abs(
        Math.floor((CURRENT_DATE - d) / (1000 * 60 * 60 * 24))
    );
}

function statusBadgeClasses(status) {
    if (status === 'Paid') {
        return 'bg-brand-green/10 text-brand-green';
    }

    if (status === 'Partial') {
        return 'bg-yellow-100 text-yellow-700';
    }

    if (status === 'Unpaid') {
        return 'bg-brand-orange/10 text-brand-orange';
    }

    if (status === 'Overdue') {
        return 'bg-brand-red/10 text-brand-red';
    }

    return 'bg-slate-100 text-slate-600';
}

// ================= DATA PROCESSING LOGIC =================
// Totals now come straight from the database (controller-computed),
// so no client-side aggregation across the (limited) invoicesData array.
function recalculateSummaryData() {
    document.getElementById('cardTotalReceivable').innerText = formatCurrency(summaryTotals.totalReceivable);
    document.getElementById('cardTotalPaid').innerText = formatCurrency(summaryTotals.totalPaid);
    document.getElementById('cardTotalUnpaid').innerText = formatCurrency(summaryTotals.totalUnpaid);
    document.getElementById('cardTotalOverdue').innerText = formatCurrency(summaryTotals.totalOverdue);
}

function computeAging() {
    return agingTotals;
}



// ================= PRIMARY UI RENDERING ENGINE =================
function renderInvoicesTable() {
    const tbody = document.getElementById('invoicesTableBody');
    if (invoicesData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-slate-400 font-medium">No system invoices located.</td></tr>`;
        return;
    }

    tbody.innerHTML = invoicesData.map(invoice => {
        const customer = getCustomer(invoice.customer_id);
        return `
        <tr class="hover:bg-slate-50/80 transition duration-150">
            <td class="px-6 py-4 font-semibold text-navy">${invoice.invoice_number}</td>
            <td class="px-6 py-4 font-medium text-slate-800">${customer ? customer.customer_name : 'Unknown'}</td>
            <td class="px-6 py-4 text-slate-500">${formatDate(invoice.invoice_date)}</td>
            <td class="px-6 py-4 text-slate-500">${formatDate(invoice.due_date)}</td>
            <td class="px-6 py-4 text-right font-bold text-slate-900">${formatCurrency(invoice.total)}</td>
            <td class="px-6 py-4 text-center">
                <span class="${statusBadgeClasses(invoice.status)} px-2.5 py-1 rounded-full text-xs font-bold tracking-wide">
                    ${invoice.status}
                </span>
            </td>
            <td class="px-6 py-4 text-center no-print">
                <button type="button" onclick="showMenu(event, ${invoice.id})" class="inline-flex h-8 w-8 items-center justify-center text-slate-400 hover:text-navy hover:bg-slate-100 rounded-lg transition">
                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                </button>
            </td>
        </tr>`;
    }).join('');
    lucide.createIcons();
}

function renderRecentPayments() {
    const container = document.getElementById('recentPaymentsList');

    if (recentPaymentsData.length === 0) {
        container.innerHTML = `<div class="p-6 text-center text-slate-400 font-medium text-sm">No transaction payments posted.</div>`;
        return;
    }

    container.innerHTML = recentPaymentsData.map(payment => {
        const customer = getCustomer(payment.customer_id);
        return `
        <div class="flex justify-between items-center px-6 py-3.5 hover:bg-slate-50/50 transition">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-green/10 text-brand-green flex items-center justify-center shrink-0">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">${customer ? customer.customer_name : 'Unknown Customer'}</h4>
                    <p class="text-xs text-slate-400 font-medium">${payment.invoice_number ?? 'General Link'}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-brand-green text-sm">${formatCurrency(payment.amount)}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">${formatDate(payment.payment_date)}</p>
            </div>
        </div>`;
    }).join('');
    lucide.createIcons();
}

function renderOutstandingBalances() {
    const container = document.getElementById('outstandingList');

    if (outstandingData.length === 0) {
        container.innerHTML = `<div class="p-6 text-center text-slate-400 font-medium text-sm">Clear balances detected across portfolios.</div>`;
        return;
    }

    container.innerHTML = outstandingData.map(invoice => {
        const customer = getCustomer(invoice.customer_id);
        return `
        <div class="flex justify-between items-center px-6 py-3 hover:bg-slate-50/50 transition">
            <div>
                <h4 class="font-bold text-slate-800 text-sm">${customer ? customer.customer_name : 'Unknown'}</h4>
                <p class="text-xs text-slate-400 font-medium">${invoice.invoice_number}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-brand-red text-sm">${formatCurrency(invoice.balance)}</p>
                <span class="inline-flex mt-0.5 bg-brand-red/10 text-brand-red text-[10px] font-bold px-1.5 py-0.5 rounded">
                    ${daysBetween(invoice.due_date)} Days Out
                </span>
            </div>
        </div>`;
    }).join('');
}

function renderAgingChart() {
    const aging = computeAging();
    document.getElementById('agingCurrentLabel').innerText = formatCurrency(aging.current);
    document.getElementById('aging31_60Label').innerText = formatCurrency(aging.days31_60);
    document.getElementById('aging61_90Label').innerText = formatCurrency(aging.days61_90);
    document.getElementById('agingOver90Label').innerText = formatCurrency(aging.over90);

    const ctx = document.getElementById('agingChart');
    if (agingChartInstance) agingChartInstance.destroy();

    agingChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Current', '31-60 Days', '61-90 Days', '90+ Days'],
            datasets: [{
                data: [aging.current, aging.days31_60, aging.days61_90, aging.over90],
                backgroundColor: ['#1FCB88', '#2F4CDD', '#F5941F', '#EF4B4B'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            cutout: '75%'
        }
    });
}

function renderDashboard() {
    recalculateSummaryData();
    renderInvoicesTable();
    renderRecentPayments();
    renderOutstandingBalances();
    renderAgingChart();
}

// ================= INTERACTIVE DROPDOWN SYSTEM =================
function showMenu(event, id) {
    event.stopPropagation();
    const menu = document.getElementById('actionMenu');
    menu.style.left = `${event.clientX}px`;
    menu.style.top = `${event.clientY + window.scrollY}px`;
    menu.classList.remove('hidden');

    document.getElementById('viewBtn').onclick = (e) => { e.stopPropagation(); openInvoiceModal(id); menu.classList.add('hidden'); };
    document.getElementById('editBtn').onclick = (e) => { e.stopPropagation(); openEditModal(id); menu.classList.add('hidden'); };
    document.getElementById('deleteBtn').onclick = (e) => { e.stopPropagation(); deleteInvoice(id); menu.classList.add('hidden'); };
}

document.addEventListener('click', (e) => {
    const menu = document.getElementById('actionMenu');
    if (menu && !menu.contains(e.target)) menu.classList.add('hidden');
});

// ================= SYSTEM MODAL TRIGGERS VIA APPUI CORE =================
function openReminderModal() {
    const customerOpts = customers.map(c => `<option value="${c.id}">${c.customer_name}</option>`).join('');
    const invoiceOpts = invoicesData.map(i => `<option value="${i.id}">${i.invoice_number} - (${formatCurrency(i.balance)})</option>`).join('');

    AppUI.openModal(`
        <h3 class="text-xl font-bold text-navy mb-1">Send Automated Reminder</h3>
        <p class="text-sm text-slate-400 mb-5">Accounts Receivable > Notifications Center</p>
        <form id="reminderSubmitForm" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Customer</label>
                <select class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-navy" required>${customerOpts}</select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Pending Invoice</label>
                <select class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-navy" required>${invoiceOpts}</select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Custom Broadcast Message</label>
                <textarea rows="4" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-navy resize-none" required>Dear Customer,\n\nThis is a friendly statement alert confirming that your outstanding portfolio invoices have reached maturation maturity parameters. Kindly arrange settlement processing coordinates.\n\nThank you.</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy/95 shadow-sm">Dispatch Transmission</button>
            </div>
        </form>
    `, 'md');

    document.getElementById('reminderSubmitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        AppUI.closeModal();
        AppUI.showToast('Billing compliance reminder successfully sent.', 'success');
    });
}

function openReportModal() {
    const typeOpts = reportTypes.map(r => `<option>${r}</option>`).join('');
    const customerOpts = '<option value="">All Customers</option>' + customers.map(c => `<option value="${c.id}">${c.customer_name}</option>`).join('');

    AppUI.openModal(`
        <h3 class="text-xl font-bold text-navy mb-1">Generate Analytical Statement</h3>
        <p class="text-sm text-slate-400 mb-5">Accounts Receivable > Financial Reporting Module</p>
        <form id="reportSubmitForm" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Statement Strategy Type</label>
                <select id="repType" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-navy">${typeOpts}</select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Execution Range From</label>
                    <input type="date" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-navy">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Execution Range To</label>
                    <input type="date" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-navy">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Target Account Portfolio Target</label>
                <select id="repCust" class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-navy">${customerOpts}</select>
            </div>
            <p id="stmtError" class="text-brand-red text-xs font-semibold hidden">Portfolio target verification requires explicit configuration selection for statement parameters.</p>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy/95 shadow-sm">Compile Ledger</button>
            </div>
        </form>
    `, 'md');

    document.getElementById('reportSubmitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const type = document.getElementById('repType').value;
        const cust = document.getElementById('repCust').value;
        if (type === 'Customer Statement' && !cust) {
            document.getElementById('stmtError').classList.remove('hidden');
            return;
        }
        AppUI.closeModal();
        AppUI.showToast('Ledger document compilation completed successfully.', 'success');
    });
}

function openInvoiceModal(id) {
    const invoice = invoicesData.find(i => i.id === id);
    if (!invoice) return;
    const customer = getCustomer(invoice.customer_id);

    const itemsHtml = invoice.items.map(item => `
        <tr class="border-b border-slate-100 text-slate-700 text-sm">
            <td class="px-5 py-3 font-medium">${item.description}</td>
            <td class="px-5 py-3 text-center font-bold">${item.quantity}</td>
            <td class="px-5 py-3 text-right text-slate-500">${formatCurrency(item.unit_price)}</td>
            <td class="px-5 py-3 text-right font-bold text-slate-900">${formatCurrency(item.quantity * item.unit_price)}</td>
        </tr>
    `).join('');

    AppUI.openModal(`
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5 bg-slate-50/50 p-4 rounded-xl">
            <div>
                <h3 class="text-xl font-bold text-navy">Invoice Manifest Information</h3>
                <p class="text-xs text-slate-400 mt-0.5">Reference Verification ID: <span class="font-bold text-navy">${invoice.invoice_number}</span></p>
            </div>
            <span class="${statusBadgeClasses(invoice.status)} px-3 py-1 rounded-full text-xs font-extrabold tracking-wider uppercase">${invoice.status}</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl text-xs space-y-1">
                <h4 class="font-bold text-navy text-sm uppercase tracking-wider mb-2">Structure Metadata</h4>
                <p><span class="text-slate-400 font-semibold">Timeline Origin:</span> <span class="text-slate-800 font-bold">${formatDateLong(invoice.invoice_date)}</span></p>
                <p><span class="text-slate-400 font-semibold">Maturity Settlement:</span> <span class="text-slate-800 font-bold">${formatDateLong(invoice.due_date)}</span></p>
            </div>
            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl text-xs space-y-1">
                <h4 class="font-bold text-navy text-sm uppercase tracking-wider mb-2">Client Parameters</h4>
                <p><span class="text-slate-400 font-semibold">Entity Signature:</span> <span class="text-slate-800 font-bold">${customer ? customer.customer_name : 'Unknown'}</span></p>
                <p><span class="text-slate-400 font-semibold">Corporate Suite:</span> <span class="text-slate-600 font-medium">${customer ? customer.company : 'N/A'}</span></p>
                <p><span class="text-slate-400 font-semibold">Secure Mail:</span> <span class="text-slate-600">${customer ? customer.email : 'N/A'}</span></p>
            </div>
        </div>
        <div class="border border-slate-200 rounded-xl overflow-hidden mb-5">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3">Specification Line</th>
                        <th class="px-5 py-3 text-center">Volume</th>
                        <th class="px-5 py-3 text-right">Unit Scalar</th>
                        <th class="px-5 py-3 text-right">Subtotal Gross</th>
                    </tr>
                </thead>
                <tbody>${itemsHtml}</tbody>
            </table>
        </div>
        <div class="flex justify-end">
            <div class="w-full sm:w-72 bg-slate-50 border border-slate-100 p-4 rounded-xl space-y-2 text-xs font-semibold text-slate-600">
                <div class="flex justify-between"><span>Base Aggregate:</span><span class="text-slate-900">${formatCurrency(invoice.subtotal)}</span></div>
                <div class="flex justify-between"><span>Fiscal Levy (12%):</span><span class="text-slate-900">${formatCurrency(invoice.tax)}</span></div>
                <div class="flex justify-between border-t border-slate-200 pt-2 text-sm font-bold text-slate-900"><span>Gross Value Total:</span><span>${formatCurrency(invoice.total)}</span></div>
                <div class="flex justify-between text-brand-red font-bold"><span>Outstanding Residual:</span><span>${formatCurrency(invoice.balance)}</span></div>
            </div>
        </div>
    `, 'lg');
}

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
function openEditModal(id) {
    const invoice = invoicesData.find(i => i.id === id);
    if (!invoice) return;
    const customer = getCustomer(invoice.customer_id);

    const linesHtml = invoice.items.map((item, index) => `
        <tr class="border-b border-slate-100">
            <td class="p-3"><input type="text" class="edit-description w-full border border-slate-200 rounded-lg px-2.5 py-1.5 text-sm" value="${item.description}" required></td>
            <td class="p-3"><input type="number" class="edit-quantity w-20 text-center border border-slate-200 rounded-lg px-2.5 py-1.5 text-sm" value="${item.quantity}" min="1" required oninput="evaluateModalFormAggregates()"></td>
            <td class="p-3"><input type="number" class="edit-price w-32 text-right border border-slate-200 rounded-lg px-2.5 py-1.5 text-sm" value="${item.unit_price}" min="0" required oninput="evaluateModalFormAggregates()"></td>
        </tr>
    `).join('');

    AppUI.openModal(`
        <h3 class="text-xl font-bold text-navy mb-1">Modify Document Schema</h3>
        <p class="text-sm text-slate-400 mb-5">Accounts Receivable > Data Matrix Editing Framework</p>
        <form id="editInvoiceSubmitForm" class="space-y-4">
            <input type="hidden" id="editInvId" value="${invoice.id}">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">System Index Reference</label>
                    <input type="text" class="w-full border border-slate-200 bg-slate-50 text-slate-400 rounded-xl px-3 py-2 text-sm" value="${invoice.invoice_number}" readonly>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Corporate Client Entity</label>
                    <input type="text" class="w-full border border-slate-200 bg-slate-50 text-slate-400 rounded-xl px-3 py-2 text-sm" value="${customer ? customer.customer_name : ''}" readonly>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Timeline Registration Date</label>
                    <input type="date" id="editInvoiceDate" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm" value="${invoice.invoice_date.split('T')[0]}" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Maturity Processing Deadline</label>
                    <input type="date" id="editDueDate" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm"value="${invoice.due_date.split('T')[0]}" required>
                </div>
            </div>
            <div class="border border-slate-200 rounded-xl overflow-hidden mt-4">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-100">
                        <tr>
                            <th class="px-3 py-2">Item Line Description</th>
                            <th class="px-3 py-2 text-center">Volume</th>
                            <th class="px-3 py-2 text-right">Scalar Value</th>
                        </tr>
                    </thead>
                    <tbody id="editModalItemContainer">${linesHtml}</tbody>
                </table>
            </div>
            <div class="flex justify-end">
                <div class="w-64 bg-slate-50 p-3 border border-slate-100 rounded-xl text-xs space-y-1 font-bold text-slate-600">
                    <div class="flex justify-between"><span>Base Aggregates:</span><span id="editSubTotalLabel">₱0.00</span></div>
                    <div class="flex justify-between"><span>Levy Accumulation:</span><span id="editTaxTotalLabel">₱0.00</span></div>
                    <div class="flex justify-between border-t border-slate-200 pt-1.5 text-sm text-slate-900"><span>Adjusted Gross Matrix:</span><span id="editTotalCalculatedLabel">₱0.00</span></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel All Changes</button>
                <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy/95 shadow-sm">Commit Manifest Modifies</button>
            </div>
        </form>
    `, 'xl');

    evaluateModalFormAggregates();

    document.getElementById('editInvoiceSubmitForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const targetId = Number(document.getElementById('editInvId').value);

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

    descriptions.forEach((item, idx) => {
        payload.description.push(item.value);
        payload.quantity.push(quantities[idx].value);
        payload.unit_price.push(prices[idx].value);
    });

    fetch(`/accounts-receivable/invoice/${targetId}`, {
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
            AppUI.showToast(data.message || 'Invoice updated successfully.', 'success');
            window.location.reload(); // or refetch + renderDashboard()
        } else {
            AppUI.showToast('Something went wrong while updating the invoice.', 'error');
        }
    })
    .catch(() => {
        AppUI.closeModal();
        AppUI.showToast('Something went wrong while updating the invoice.', 'error');
    });
});
}

function evaluateModalFormAggregates() {
    const quantities = document.querySelectorAll('.edit-quantity');
    const prices = document.querySelectorAll('.edit-price');
    let runningSubtotal = 0;

    quantities.forEach((qty, index) => {
        runningSubtotal += Number(qty.value) * Number(prices[index].value);
    });

    const runningTax = runningSubtotal * 0.12;
    const finalGrossTotal = runningSubtotal + runningTax;

    document.getElementById('editSubTotalLabel').innerText = formatCurrency(runningSubtotal);
    document.getElementById('editTaxTotalLabel').innerText = formatCurrency(runningTax);
    document.getElementById('editTotalCalculatedLabel').innerText = formatCurrency(finalGrossTotal);
}

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

function deleteInvoice(id) {
    console.log("Deleting:", id);

    fetch(`/accounts-receivable/invoice/${id}`, {
    method: 'DELETE',
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content')
    }
})
    .then(async res => {
        console.log("Status:", res.status);

        const data = await res.json().catch(() => null);
        console.log("Response:", data);

        if (res.ok) {
            window.location.reload();
        } else {
            AppUI.showToast("Delete failed.", "error");
        }
    })
    .catch(err => {
        console.error(err);
    });
}

function openAllPaymentsModal() {
    const listHtml = paymentsData.map(p => {
        const customer = getCustomer(p.customer_id);
        return `
            <tr class="border-b border-slate-100 text-sm hover:bg-slate-50/50">
                <td class="px-4 py-3 font-semibold text-slate-800">${customer ? customer.customer_name : 'N/A'}</td>
                <td class="px-4 py-3 font-medium text-navy">${p.invoice_number ?? 'N/A'}</td>
                <td class="px-4 py-3 text-right font-bold text-brand-green">${formatCurrency(p.amount)}</td>
                <td class="px-4 py-3 text-center text-slate-500 font-medium">${formatDate(p.payment_date)}</td>
            </tr>
        `;
    }).join('');

    AppUI.openModal(`
        <h3 class="text-xl font-bold text-navy mb-1">Complete Settlement Log</h3>
        <p class="text-sm text-slate-400 mb-4">Accounts Receivable > Archive Processing Ledger</p>
        <div class="border border-slate-200 rounded-xl overflow-hidden max-h-[400px] overflow-y-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-100 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3">Client Entity</th>
                        <th class="px-4 py-3">Document Identification</th>
                        <th class="px-4 py-3 text-right">Settled Volume</th>
                        <th class="px-4 py-3 text-center">Posting Timeline</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">${listHtml}</tbody>
            </table>
        </div>
        <div class="flex justify-end mt-4">
            <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Close Registry View</button>
        </div>
    `, 'lg');
}

// Initialization Lifecycle Hook Execution Trigger
document.addEventListener('DOMContentLoaded', function () {
    renderDashboard();
});
</script>
@endpush