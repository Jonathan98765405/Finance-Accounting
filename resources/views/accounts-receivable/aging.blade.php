@extends('layouts.app')

@section('page-title', 'Aging Report')
@section('page-title-heading', 'Aging Report')
@section('page-subtitle', 'Monitor outstanding receivables and analyze overdue customer balances.')

@section('content')
<div class="space-y-6">
    @php
        // Direct privilege resolution bypassing traditional middleware
        $isAdmin = auth()->user() && auth()->user()->hasRole('admin');
    @endphp

    {{-- Quick Actions Row --}}
    <div class="flex flex-wrap items-center justify-end gap-2 sm:gap-3 no-print">
        <a href="{{ url('/accounts-receivable') }}"
            class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-xl text-sm font-semibold transition inline-flex items-center gap-2 shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Dashboard
        </a>

        <!-- Export PDF Link guarded with AppUI Modal Warning -->
        <a href="{{ $isAdmin ? route('receivable.aging.export.pdf') : '#' }}"
            onclick="return verifySubmoduleAccess(event, {{ $isAdmin ? 'true' : 'false' }}, 'Export PDF')"
            class="bg-brand-red text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-600 transition inline-flex items-center gap-2 shadow-sm">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            Export PDF
        </a>

        <!-- Export Excel Link guarded with AppUI Modal Warning -->
        <a href="{{ $isAdmin ? route('receivable.aging.export.excel') : '#' }}"
            onclick="return verifySubmoduleAccess(event, {{ $isAdmin ? 'true' : 'false' }}, 'Export Excel')"
            class="bg-brand-green text-navy px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-brand-greenDark transition inline-flex items-center gap-2 shadow-sm">
            <i data-lucide="sheet" class="w-4 h-4"></i>
            Export Excel
        </a>

        <!-- Print Trigger guarded with AppUI Modal Warning -->
        <button onclick="if(verifySubmoduleAccess(event, {{ $isAdmin ? 'true' : 'false' }}, 'Print Report')) window.print()"
            class="bg-navy hover:bg-navy-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition inline-flex items-center gap-2 shadow-sm">
            <i data-lucide="printer" class="w-4 h-4"></i>
            Print
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Outstanding -->
        <div class="bg-navy/80 text-white rounded-2xl shadow-card p-6 flex items-center justify-between">
            <div>
                <p class="text-white/70 text-xs font-semibold uppercase tracking-wider">Total Outstanding</p>
                <h2 class="text-2xl font-bold mt-2" id="cardTotalOutstanding">₱0.00</h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-navy/10 flex items-center justify-center">
                <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
            </div>
        </div>

        <!-- Current -->
        <div class="bg-brand-green/30 rounded-2xl shadow-card p-6 flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Current</p>
                <h2 class="text-2xl font-extrabold text-brand-greenDark mt-2" id="cardCurrent">₱0.00</h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-green/10 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-6 h-6 text-brand-greenDark"></i>
            </div>
        </div>

        <!-- 31-60 Days -->
        <div class="bg-brand-blue/30 rounded-2xl shadow-card p-6 flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">31–60 Days</p>
                <h2 class="text-2xl font-extrabold text-brand-blue mt-2" id="card31_60">₱0.00</h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-blue/10 flex items-center justify-center">
                <i data-lucide="calendar" class="w-6 h-6 text-brand-blue"></i>
            </div>
        </div>

        <!-- 61-90 Days -->
        <div class="bg-brand-orange/30 rounded-2xl shadow-card p-6 flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">61–90 Days</p>
                <h2 class="text-2xl font-extrabold text-brand-orange mt-2" id="card61_90">₱0.00</h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-orange/10 flex items-center justify-center">
                <i data-lucide="hourglass" class="w-6 h-6 text-brand-orange"></i>
            </div>
        </div>

        <!-- Over 90 Days -->
        <div class="bg-brand-red/30 rounded-2xl shadow-card p-6 flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">90+ Days</p>
                <h2 class="text-2xl font-extrabold text-brand-red mt-2" id="cardOver90">₱0.00</h2>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-red/10 flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-6 h-6 text-brand-red"></i>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Bar Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-card border border-slate-100 p-6">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-navy">Receivables by Aging</h2>
                    <p class="text-sm text-slate-400">Outstanding balances by aging category</p>
                </div>
                <select id="chartFilter" onchange="onChartFilterChange()"
                    class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy bg-white text-slate-700">
                    <option value="month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="year">This Year</option>
                    <option value="all" selected>All</option>
                </select>
            </div>
            <div class="h-[320px]">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart -->
        <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-navy">Distribution</h2>
                <p class="text-sm text-slate-400">Receivable Categories</p>
            </div>
            <div class="h-[200px] flex items-center justify-center">
                <canvas id="doughnutChart"></canvas>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex justify-between items-center text-sm border-b border-slate-50 pb-2">
                    <span class="flex items-center gap-2 text-slate-600">
                        <span class="w-3 h-3 rounded-full bg-brand-green"></span> Current
                    </span>
                    <span class="font-bold text-navy" id="legendCurrent">₱0.00</span>
                </div>
                <div class="flex justify-between items-center text-sm border-b border-slate-50 pb-2">
                    <span class="flex items-center gap-2 text-slate-600">
                        <span class="w-3 h-3 rounded-full bg-brand-blue"></span> 31-60 Days
                    </span>
                    <span class="font-bold text-navy" id="legend31_60">₱0.00</span>
                </div>
                <div class="flex justify-between items-center text-sm border-b border-slate-50 pb-2">
                    <span class="flex items-center gap-2 text-slate-600">
                        <span class="w-3 h-3 rounded-full bg-brand-orange"></span> 61-90 Days
                    </span>
                    <span class="font-bold text-navy" id="legend61_90">₱0.00</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center gap-2 text-slate-600">
                        <span class="w-3 h-3 rounded-full bg-brand-red"></span> 90+ Days
                    </span>
                    <span class="font-bold text-navy" id="legendOver90">₱0.00</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer Aging Details Table --}}
    <div class="bg-white rounded-2xl shadow-card border border-slate-100 overflow-hidden">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-navy">Customer Aging Details</h2>
                <p class="text-slate-400 text-sm mt-0.5">Monitor customer receivables and overdue invoices.</p>
            </div>
            <select id="tableFilter" onchange="onTableFilterChange()"
                class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy bg-white text-slate-700">
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
                <option value="all" selected>All</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Invoice No.</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Invoice Date</th>
                        <th class="px-6 py-4">Due Date</th>
                        <th class="px-6 py-4">Balance</th>
                        <th class="px-6 py-4">Aging</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody id="receivablesTableBody" class="divide-y divide-slate-100 text-sm text-slate-700">
                    <!-- Dynamic Rows Rendered by JS -->
                </tbody>
            </table>
        </div>

        {{-- Pagination Panel --}}
        <div class="bg-white border-t border-slate-100 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-slate-500">
                Showing <span class="font-semibold text-navy" id="pageFirstItem">0</span> to
                <span class="font-semibold text-navy" id="pageLastItem">0</span> of
                <span class="font-semibold text-navy" id="pageTotalItems">0</span> receivables
            </div>
            <div class="flex items-center gap-1.5" id="paginationButtons">
                <!-- Buttons Rendered by JS -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // System Constants
    const CURRENT_DATE = new Date('{{ now()->format("Y-m-d") }}T00:00:00');
    const PER_PAGE = 5;
    let currentPage = 1;
    let tableFilter = 'all';
    let chartFilter = 'all';

    const invoicesData = @json($invoicesData ?? []);
    let barChartInstance = null;
    let doughnutChartInstance = null;

    // Integrated custom AppUI modal warning system
    function verifySubmoduleAccess(event, isAdmin, actionName) {
        if (!isAdmin) {
            event.preventDefault();
            
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

            // Hydrate the lucide icon inside the newly opened modal
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            return false;
        }
        return true;
    }

    function formatCurrency(amount) {
        return '₱' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return 'N/A';
        return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' });
    }

    function daysPastDue(dueDateStr) {
        const due = new Date(dueDateStr);
        return Math.floor((CURRENT_DATE - due) / (1000 * 60 * 60 * 24));
    }

    function agingBucket(dueDateStr) {
        const days = daysPastDue(dueDateStr);

        if (days <= 0) return { key: 'current', label: 'Current', className: 'text-brand-greenDark' };
        if (days <= 30) return { key: 'current', label: '1–30 Days', className: 'text-brand-greenDark' };
        if (days <= 60) return { key: 'days31_60', label: '31–60 Days', className: 'text-brand-blue' };
        if (days <= 90) return { key: 'days61_90', label: '61–90 Days', className: 'text-brand-orange' };
        return { key: 'over90', label: '90+ Days', className: 'text-brand-red' };
    }

    function statusBadge(status) {
        if (status === 'Current') return { className: 'bg-green-100 text-brand-greenDark', label: 'Current' };
        if (status === 'Pending') return { className: 'bg-blue-100 text-brand-blue', label: 'Pending' };
        if (status === 'Overdue') return { className: 'bg-orange-100 text-brand-orange', label: 'Overdue' };
        if (status === 'Delinquent') return { className: 'bg-red-50 text-red-600', label: 'Delinquent' };
        return { className: 'bg-red-100 text-brand-red', label: 'Critical' };
    }

    function withinRange(dateStr, range) {
        const d = new Date(dateStr);
        if (range === 'all') return true;

        if (range === 'today') return d.toDateString() === CURRENT_DATE.toDateString();

        if (range === 'week') {
            const startOfWeek = new Date(CURRENT_DATE);
            startOfWeek.setDate(CURRENT_DATE.getDate() - CURRENT_DATE.getDay());
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);
            return d >= startOfWeek && d <= endOfWeek;
        }

        if (range === 'month') {
            return d.getFullYear() === CURRENT_DATE.getFullYear() && d.getMonth() === CURRENT_DATE.getMonth();
        }

        if (range === 'last_month') {
            const lastMonth = new Date(CURRENT_DATE.getFullYear(), CURRENT_DATE.getMonth() - 1, 1);
            return d.getFullYear() === lastMonth.getFullYear() && d.getMonth() === lastMonth.getMonth();
        }

        if (range === 'year') return d.getFullYear() === CURRENT_DATE.getFullYear();
        return true;
    }

    function computeAging(invoices) {
        const aging = { current: 0, days31_60: 0, days61_90: 0, over90: 0 };
        invoices.forEach(inv => {
            const bucket = agingBucket(inv.due_date);
            aging[bucket.key] += Number(inv.balance ?? 0);
        });
        return aging;
    }

    function renderSummaryCards() {
        const aging = computeAging(invoicesData);
        const totalOutstanding = aging.current + aging.days31_60 + aging.days61_90 + aging.over90;

        document.getElementById('cardTotalOutstanding').innerText = formatCurrency(totalOutstanding);
        document.getElementById('cardCurrent').innerText = formatCurrency(aging.current);
        document.getElementById('card31_60').innerText = formatCurrency(aging.days31_60);
        document.getElementById('card61_90').innerText = formatCurrency(aging.days61_90);
        document.getElementById('cardOver90').innerText = formatCurrency(aging.over90);
    }

    function onChartFilterChange() {
        chartFilter = document.getElementById('chartFilter').value;
        renderCharts();
    }

    function renderCharts() {
        const filtered = invoicesData.filter(inv => withinRange(inv.invoice_date, chartFilter));
        const aging = computeAging(filtered);

        document.getElementById('legendCurrent').innerText = formatCurrency(aging.current);
        document.getElementById('legend31_60').innerText = formatCurrency(aging.days31_60);
        document.getElementById('legend61_90').innerText = formatCurrency(aging.days61_90);
        document.getElementById('legendOver90').innerText = formatCurrency(aging.over90);

        const chartData = [aging.current, aging.days31_60, aging.days61_90, aging.over90];
        const barChartEl = document.getElementById('barChart');

        if (barChartEl) {
            if (barChartInstance) barChartInstance.destroy();
            barChartInstance = new Chart(barChartEl, {
                type: 'bar',
                data: {
                    labels: ['Current', '31-60 Days', '61-90 Days', '90+ Days'],
                    datasets: [{
                        label: 'Outstanding Amount',
                        data: chartData,
                        backgroundColor: ['#1FCB88', '#2F4CDD', '#F5941F', '#EF4B4B'],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        const doughnutChartEl = document.getElementById('doughnutChart');
        if (doughnutChartEl) {
            if (doughnutChartInstance) doughnutChartInstance.destroy();
            doughnutChartInstance = new Chart(doughnutChartEl, {
                type: 'doughnut',
                data: {
                    labels: ['Current', '31-60 Days', '61-90 Days', '90+ Days'],
                    datasets: [{
                        data: chartData,
                        backgroundColor: ['#1FCB88', '#2F4CDD', '#F5941F', '#EF4B4B'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { display: false } }
                }
            });
        }
    }
    
    function onTableFilterChange() {
        tableFilter = document.getElementById('tableFilter').value;  
        currentPage = 1;
        renderTable();
    }

    function goToPage(page) {
        currentPage = page;
        renderTable();
    }

    function getFilteredReceivables() {
        return invoicesData.filter(inv => withinRange(inv.invoice_date, tableFilter));
    }

    function renderTable() {
        const filtered = getFilteredReceivables();
        const totalItems = filtered.length;
        const lastPage = Math.max(Math.ceil(totalItems / PER_PAGE), 1);

        if (currentPage > lastPage) currentPage = lastPage;
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * PER_PAGE;
        const pageItems = filtered.slice(startIndex, startIndex + PER_PAGE);

        renderReceivablesTable(pageItems);
        renderPaginationInfo(totalItems, startIndex, pageItems.length);
        renderPaginationButtons(lastPage);
    }

    function renderReceivablesTable(rows) {
        const tbody = document.getElementById('receivablesTableBody');

        if (rows.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-8 text-slate-400 font-medium">
                        No receivables found.
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = rows.map(row => {
            const bucket = agingBucket(row.due_date);
            const badge = statusBadge(row.status || 'Current');

            return `
            <tr class="hover:bg-slate-50 transition border-b border-slate-100">
                <td class="px-6 py-4 font-bold text-navy">
                    ${row.invoice_no || 'N/A'}
                </td>
                <td class="px-6 py-4">
                   <span class="font-semibold text-slate-700">${row.customer_name || 'Unknown Vendor'}</span>
                </td>
                <td class="px-6 py-4 text-slate-600">
                    ${formatDate(row.invoice_date)}
                </td>
                <td class="px-6 py-4 text-slate-600">
                   ${formatDate(row.due_date)}
                </td>
                <td class="px-6 py-4 font-bold text-navy">
                   ${formatCurrency(row.balance)}
                </td>
                <td class="px-6 py-4">
                    <span class="${bucket.className} font-bold">
                        ${row.aging || bucket.label}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="${badge.className} px-2.5 py-1 rounded-full text-xs font-bold">
                        ${badge.label}
                    </span>
                </td>
            </tr>`;
        }).join('');
    }

    function renderPaginationInfo(totalItems, startIndex, pageCount) {
        document.getElementById('pageFirstItem').innerText = totalItems === 0 ? 0 : startIndex + 1;
        document.getElementById('pageLastItem').innerText = totalItems === 0 ? 0 : startIndex + pageCount;
        document.getElementById('pageTotalItems').innerText = totalItems;
    }

    function renderPaginationButtons(lastPage) {
        const container = document.getElementById('paginationButtons');
        let html = '';

        if (currentPage === 1) {
            html += `<span class="w-9 h-9 border border-slate-200 rounded-xl flex items-center justify-center text-slate-300">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                     </span>`;
        } else {
            html += `<button onclick="goToPage(${currentPage - 1})"
                        class="w-9 h-9 border border-slate-200 rounded-xl hover:bg-slate-50 flex items-center justify-center text-slate-600">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                     </button>`;
        }

        for (let page = 1; page <= lastPage; page++) {
            if (page === currentPage) {
                html += `<span class="w-9 h-9 rounded-xl bg-navy text-white font-semibold flex items-center justify-center">
                            ${page}
                         </span>`;
            } else {
                html += `<button onclick="goToPage(${page})"
                            class="w-9 h-9 border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-600 font-medium flex items-center justify-center">
                            ${page}
                         </button>`;
            }
        }

        if (currentPage === lastPage) {
            html += `<span class="w-9 h-9 border border-slate-200 rounded-xl flex items-center justify-center text-slate-300">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                     </span>`;
        } else {
            html += `<button onclick="goToPage(${currentPage + 1})"
                        class="w-9 h-9 border border-slate-200 rounded-xl hover:bg-slate-50 flex items-center justify-center text-slate-600">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                     </button>`;
        }

        container.innerHTML = html;
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        renderSummaryCards();
        renderCharts();
        renderTable();
    });
</script>
@endpush