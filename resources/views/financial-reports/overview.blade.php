@extends('layouts.app')

@section('page-title', 'Finance & Accounting | Financial Reports')
@section('page-title-heading', 'Financial Reports & Compliance')
@section('page-subtitle', 'Monitor financial performance and ensure regulatory compliance.')

@section('content')
    @include('financial-reports.header')

    {{-- Revenue / Profit charts --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
        <div class="bg-white rounded-2xl shadow-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-bold tracking-wide text-slate-400 uppercase">Revenue</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-navy-600"></span>
                        <span class="text-sm text-slate-500">Revenue (PHP)</span>
                    </div>
                </div>
                <div class="relative">
                    <select id="revenue-year-select"
                        class="appearance-none flex items-center gap-1 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg pl-3 pr-8 py-1.5 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" @selected($y == $selectedYear)>{{ $y }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down"
                        class="w-4 h-4 text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            <div class="relative h-64 sm:h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-bold tracking-wide text-slate-400 uppercase">Profit</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-green"></span>
                        <span class="text-sm text-slate-500">Profit (PHP)</span>
                    </div>
                </div>
                <div class="relative">
                    <select id="profit-year-select"
                        class="appearance-none flex items-center gap-1 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg pl-3 pr-8 py-1.5 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" @selected($y == $selectedYear)>{{ $y }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down"
                        class="w-4 h-4 text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            <div class="relative h-64 sm:h-72">
                <canvas id="profitChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Bottom row: Compliance donut / Monthly reports / Recent activity --}}
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

        {{-- Compliance status --}}
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-card px-6 pb-6 pt-4">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center w-full">
                    <p class="text-sm font-bold text-navy">COMPLIANCE STATUS</p>

                    <button type="button" id="audit-history-btn"
                        class="ml-auto text-xs bg-navy-600 font-semibold text-white border border-navy-600/20 rounded-lg px-3 py-2">
                        Audit History
                    </button>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative w-44 h-44 shrink-0">
                    <canvas id="complianceDonut"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <p class="text-xs text-slate-400">TOTAL AUDITS</p>
                        <p id="compliance-total-audits" class="text-2xl font-extrabold text-navy">{{ $complianceDonut['total'] }}</p>
                    </div>
                </div>
                <div class="space-y-3 text-sm w-full">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><i
                                class="w-2.5 h-2.5 rounded-full bg-brand-green inline-block"></i> Complaint</span>
                        <span id="compliance-complaint-pct" class="font-semibold">{{ $complianceDonut['complaint'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><i
                                class="w-2.5 h-2.5 rounded-full bg-navy-600 inline-block"></i> Pending</span>
                        <span id="compliance-pending-pct" class="font-semibold">{{ $complianceDonut['pending'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><i
                                class="w-2.5 h-2.5 rounded-full bg-brand-orange inline-block"></i> Failed</span>
                        <span id="compliance-failed-pct" class="font-semibold">{{ $complianceDonut['failed'] }}%</span>
                    </div>
                    <div class="flex justify-end pt-2">
                        <div class="relative">
                            <select id="compliance-year-select"
                                class="appearance-none text-sm font-medium text-slate-600 border border-slate-200 rounded-lg pl-3 pr-8 py-1.5 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" @selected($y == $selectedYear)>{{ $y }}</option>
                                @endforeach
                            </select>

                            <i data-lucide="chevron-down"
                                class="w-4 h-4 text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly reports + Recent activity --}}
        <div class="xl:col-span-3 flex flex-col gap-5">

            <div class="bg-white rounded-2xl shadow-card p-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-bold text-navy">MONTHLY REPORTS</p>
                    <button type="button" id="reports-view-all-btn"
                        class="text-sm font-semibold text-navy-600 hover:underline">View All</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-slate-400 text-left">
                                <th class="font-medium pb-2">Month</th>
                                <th class="font-medium pb-2">Revenue</th>
                                <th class="font-medium pb-2">Expenses</th>
                                <th class="font-medium pb-2">Profit</th>
                                <th class="font-medium pb-2">Compliance</th>
                                <th class="font-medium pb-2">Details</th>
                            </tr>
                        </thead>
                        <tbody id="monthly-reports-preview-body" class="divide-y divide-slate-100">
                            {{-- Rows are rendered by JS from the shared REPORTS dataset --}}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card p-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-bold text-navy">RECENT COMPLIANCE ACTIVITIES</p>
                    <button type="button" id="activities-view-all-btn"
                        class="text-sm font-semibold text-navy-600 hover:underline">View All</button>
                </div>
                <div id="recent-activities-list" class="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    {{-- Rows are rendered by JS --}}
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const monthAbbr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const CURRENT_YEAR = {{ $selectedYear }};
        const ALL_YEARS = @json($years);
        const yearOptionsHtml = (selected) => ALL_YEARS.map(y => `<option value="${y}" ${String(y) === String(selected) ? 'selected' : ''}>${y}</option>`).join('');

        const REVENUE_BY_YEAR = { [CURRENT_YEAR]: @json($revenueSeries) };
        const PROFIT_BY_YEAR = { [CURRENT_YEAR]: @json($profitSeries) };
        const REPORTS = { [CURRENT_YEAR]: @json($monthlyReports) };
        const COMPLIANCE_DONUT = { [CURRENT_YEAR]: @json($complianceDonut) };
        let AUDITS = @json($audits);
        const AUDITS_CACHE = { [CURRENT_YEAR]: AUDITS };

        const DEFAULT_AUDIT_TYPES = ['Internal', 'External', 'Regulatory', 'Financial'];
        const AUDIT_TYPES = Array.from(new Set([
            ...DEFAULT_AUDIT_TYPES,
            ...AUDITS.map(a => a.auditType).filter(Boolean),
        ]));

        function formatPHP(n) {
            return '₱' + Math.round(n).toLocaleString();
        }

        function complianceBadgeClass(status) {
            if (status === 'Passed') return 'text-brand-green';
            if (status === 'Failed') return 'text-brand-red';
            return 'text-brand-orange';
        }

        const ACTIVITIES = @json($activities);

        async function loadOverviewYear(year) {
            if (String(year) === String(CURRENT_YEAR)) return; 

            const res = await fetch(`{{ url('/financial-reports/overview/data') }}?year=${year}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) return;
            const data = await res.json();

            REVENUE_BY_YEAR[year] = data.revenueSeries;
            PROFIT_BY_YEAR[year] = data.profitSeries;
            REPORTS[year] = data.monthlyReports;
            COMPLIANCE_DONUT[year] = data.complianceDonut;
            AUDITS = data.audits;
            AUDITS_CACHE[year] = data.audits;
        }

        function auditBadgeClass(status) {
            if (status === 'Complaint') return 'text-brand-green';
            if (status === 'Failed') return 'text-brand-red';
            return 'text-brand-orange';
        }

        function buildLineOptions() {
            return {
                responsive: true,
                maintainAspectRatio: false,
                resizeDelay: 0,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.parsed.y.toLocaleString()
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0, max: 3000000,
                        ticks: {
                            stepSize: 500000,
                            callback: (v) => v === 0 ? '0' : (v / 1000000 >= 1 ? (v / 1000000) + 'M' : (v / 1000) + 'K'),
                            color: '#94a3b8', font: { size: 11 }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } }
                }
            };
        }

        const revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: monthAbbr,
                datasets: [{
                    label: 'Revenue (PHP)',
                    data: REVENUE_BY_YEAR[CURRENT_YEAR],
                    borderColor: '#2F4CDD',
                    backgroundColor: 'rgba(47,76,221,0.08)',
                    pointBackgroundColor: '#2F4CDD',
                    tension: 0.4, fill: true, pointRadius: 4, borderWidth: 3
                }]
            },
            options: buildLineOptions()
        });

        const profitChart = new Chart(document.getElementById('profitChart'), {
            type: 'line',
            data: {
                labels: monthAbbr,
                datasets: [{
                    label: 'Profit (PHP)',
                    data: PROFIT_BY_YEAR[CURRENT_YEAR],
                    borderColor: '#1FCB88',
                    backgroundColor: 'rgba(31,203,136,0.08)',
                    pointBackgroundColor: '#1FCB88',
                    tension: 0.4, fill: true, pointRadius: 4, borderWidth: 3
                }]
            },
            options: buildLineOptions()
        });

        document.getElementById('revenue-year-select').addEventListener('change', async function () {
            await loadOverviewYear(this.value);
            revenueChart.data.datasets[0].data = REVENUE_BY_YEAR[this.value];
            revenueChart.update();
        });
        document.getElementById('profit-year-select').addEventListener('change', async function () {
            await loadOverviewYear(this.value);
            profitChart.data.datasets[0].data = PROFIT_BY_YEAR[this.value];
            profitChart.update();
        });

        const complianceDonut = new Chart(document.getElementById('complianceDonut'), {
            type: 'doughnut',
            data: {
                labels: ['Complaint', 'Pending', 'Failed'],
                datasets: [{
                    data: [
                        COMPLIANCE_DONUT[CURRENT_YEAR].complaint,
                        COMPLIANCE_DONUT[CURRENT_YEAR].pending,
                        COMPLIANCE_DONUT[CURRENT_YEAR].failed,
                    ],
                    backgroundColor: ['#1FCB88', '#16265B', '#F5941F'],
                    borderWidth: 0,
                }]
            },
            options: {
                cutout: '72%',
                plugins: { legend: { display: false }, tooltip: { enabled: true } }
            }
        });

        async function updateComplianceDonut() {
            const year = document.getElementById('compliance-year-select').value;
            await loadOverviewYear(year);
            const d = COMPLIANCE_DONUT[year];

            complianceDonut.data.datasets[0].data = [d.complaint, d.pending, d.failed];
            complianceDonut.update();

            document.getElementById('compliance-total-audits').textContent = d.total;
            document.getElementById('compliance-complaint-pct').textContent = d.complaint + '%';
            document.getElementById('compliance-pending-pct').textContent = d.pending + '%';
            document.getElementById('compliance-failed-pct').textContent = d.failed + '%';
        }

        document.getElementById('compliance-year-select').addEventListener('change', updateComplianceDonut);

        function renderPreviewReports() {
            const body = document.getElementById('monthly-reports-preview-body');
            const rows = REPORTS[CURRENT_YEAR].slice(0, 3);
            body.innerHTML = rows.map((r, i) => `
                            <tr>
                              <td class="py-2.5 font-medium text-slate-700">${r.abbr}</td>
                              <td class="py-2.5">${formatPHP(r.revenue)}</td>
                              <td class="py-2.5">${formatPHP(r.expenses)}</td>
                              <td class="py-2.5">${formatPHP(r.profit)}</td>
                              <td class="py-2.5 font-semibold ${complianceBadgeClass(r.compliance)}">${r.compliance}</td>
                              <td class="py-2.5"><button type="button" class="text-navy-600 font-medium hover:underline js-report-view" data-year="${CURRENT_YEAR}" data-index="${i}">View</button></td>
                            </tr>
                          `).join('');
        }
        renderPreviewReports();

        function openReportDetailModal(year, index) {
            const r = REPORTS[year][index];
            const rowsHtml = `
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Period</span><span class="text-sm font-semibold text-navy">${r.month} ${r.year}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Revenue</span><span class="text-sm font-semibold text-navy">${formatPHP(r.revenue)}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Expenses</span><span class="text-sm font-semibold text-navy">${formatPHP(r.expenses)}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Net Profit</span><span class="text-sm font-semibold text-navy">${formatPHP(r.profit)}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Profit Margin</span><span class="text-sm font-semibold text-navy">${r.margin}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Compliance Status</span><span class="text-sm font-semibold ${complianceBadgeClass(r.compliance)}">${r.compliance}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Audit Type</span><span class="text-sm font-semibold text-navy">${r.auditType}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Tax Filed</span><span class="text-sm font-semibold text-navy">${r.taxFiled}</span></div>
                            <div class="py-2"><span class="text-sm text-slate-500 block mb-1">Notes</span><span class="text-sm text-slate-700">${r.notes}</span></div>
                          `;

            AppUI.openModal(`
                            <h3 class="text-lg font-bold text-navy mb-1">${r.month} ${r.year} Report</h3>
                            <p class="text-sm text-slate-500 mb-4">Full financial and compliance breakdown for this period.</p>
                            <div>${rowsHtml}</div>
                            <div class="flex justify-end pt-5">
                              <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Close</button>
                            </div>
                          `);
        }

        function renderAllReportsTable(year) {
            const rows = REPORTS[year];
            return `
                            <table class="w-full text-sm">
                              <thead>
                                <tr class="text-slate-400 text-left">
                                  <th class="font-medium pb-2">Month</th>
                                  <th class="font-medium pb-2">Revenue</th>
                                  <th class="font-medium pb-2">Expenses</th>
                                  <th class="font-medium pb-2">Profit</th>
                                  <th class="font-medium pb-2">Compliance</th>
                                  <th class="font-medium pb-2">Details</th>
                                </tr>
                              </thead>
                              <tbody class="divide-y divide-slate-100">
                                ${rows.map((r, i) => `
                                  <tr>
                                    <td class="py-2.5 font-medium text-slate-700">${r.month}</td>
                                    <td class="py-2.5">${formatPHP(r.revenue)}</td>
                                    <td class="py-2.5">${formatPHP(r.expenses)}</td>
                                    <td class="py-2.5">${formatPHP(r.profit)}</td>
                                    <td class="py-2.5 font-semibold ${complianceBadgeClass(r.compliance)}">${r.compliance}</td>
                                    <td class="py-2.5"><button type="button" class="text-navy-600 font-medium hover:underline js-report-view" data-year="${year}" data-index="${i}">View</button></td>
                                  </tr>
                                `).join('')}
                              </tbody>
                            </table>
                          `;
        }

        function openAllReportsModal() {
            AppUI.openModal(`
                            <div class="flex items-start justify-between gap-4 mb-1 pr-10">
                              <h3 class="text-lg font-bold text-navy">All Monthly Reports</h3>
                              <select id="all-reports-year-select" class="shrink-0 border border-slate-200 rounded-lg px-2.5 py-1.5 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                                ${yearOptionsHtml(CURRENT_YEAR)}
                              </select>
                            </div>
                            <p class="text-sm text-slate-500 mb-4">January – December, filtered by year.</p>
                            <div id="all-reports-table-wrap" class="max-h-[26rem] overflow-y-auto pr-1">
                              ${renderAllReportsTable(CURRENT_YEAR)}
                            </div>
                            <div class="flex justify-end pt-5">
                              <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Close</button>
                            </div>
                          `, 'lg');

            document.getElementById('all-reports-year-select').addEventListener('change', async function () {
                await loadOverviewYear(this.value);
                document.getElementById('all-reports-table-wrap').innerHTML = renderAllReportsTable(this.value);
            });
        }

        document.getElementById('reports-view-all-btn').addEventListener('click', openAllReportsModal);

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.js-report-view');
            if (!btn) return;
            openReportDetailModal(btn.dataset.year, parseInt(btn.dataset.index, 10));
        });

        function renderRecentActivities() {
            const list = document.getElementById('recent-activities-list');

            list.innerHTML = ACTIVITIES.slice(0, 4).map(a => `
                <div class="flex items-center justify-between gap-3">
                    <span class="flex items-center gap-2">
                        <i data-lucide="${a.icon}" class="w-4 h-4 ${a.iconColor}"></i>

                        <span class="flex items-center gap-1">
                            <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                            ${a.title}
                        </span>
                    </span>

                    <span class="text-xs font-medium ${a.color}">
                        ${a.when}
                    </span>
                </div>
            `).join('');

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        renderRecentActivities();

        function openAllActivitiesModal() {
            const rowsHtml = ACTIVITIES.map(a => `
                <div class="flex items-start justify-between gap-3 py-3 border-b border-slate-100 last:border-0">
                    <div class="flex items-start gap-3">
                        <i data-lucide="${a.icon}" class="w-4 h-4 mt-0.5 ${a.iconColor}"></i>

                        <div>
                            <p class="text-sm font-medium text-slate-700 flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                                <span>${a.title}</span>
                            </p>

                            <p class="text-xs text-slate-400 mt-0.5">${a.type}</p>
                            <p class="text-xs text-slate-500 mt-1">${a.notes}</p>
                        </div>
                    </div>

                    <span class="text-xs font-semibold ${a.color} whitespace-nowrap">
                        ${a.when}
                    </span>
                </div>
            `).join('');

            AppUI.openModal(`
                <h3 class="text-lg font-bold text-navy mb-1">All Compliance Activities</h3>
                <p class="text-sm text-slate-500 mb-2">${ACTIVITIES.length} recorded activities.</p>

                <div class="max-h-[26rem] overflow-y-auto pr-1">
                    ${rowsHtml}
                </div>

                <div class="flex justify-end pt-5">
                    <button
                        type="button"
                        onclick="AppUI.closeModal()"
                        class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">
                        Close
                    </button>
                </div>
            `, 'lg');

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        document.getElementById('activities-view-all-btn').addEventListener('click', openAllActivitiesModal);

        function renderAuditHistoryTable(year, overrideRows) {
            const rows = overrideRows ?? (AUDITS_CACHE[year] || (String(year) === String(CURRENT_YEAR) ? AUDITS : []));
            if (!rows.length) {
                return '<p class="text-sm text-slate-400 py-6 text-center">No audit records for this year.</p>';
            }
            return `
                            <table class="w-full text-sm">
                              <thead>
                                <tr class="text-slate-400 text-left">
                                  <th class="font-medium pb-2">Date</th>
                                  <th class="font-medium pb-2">Audit Type</th>
                                  <th class="font-medium pb-2">Auditor</th>
                                  <th class="font-medium pb-2">Status</th>
                                  <th class="font-medium pb-2">Details</th>
                                </tr>
                              </thead>
                              <tbody class="divide-y divide-slate-100">
                                ${rows.map(a => `
                                  <tr>
                                    <td class="py-2.5 font-medium text-slate-700">${a.date ?? `${a.month} ${a.year}`}</td>
                                    <td class="py-2.5">${a.auditType}</td>
                                    <td class="py-2.5">${a.auditor}</td>
                                    <td class="py-2.5 font-semibold ${auditBadgeClass(a.status)}">${a.status}</td>
                                    <td class="py-2.5"><button type="button" class="text-navy-600 font-medium hover:underline js-audit-view" data-id="${a.id}">View</button></td>
                                  </tr>
                                `).join('')}
                              </tbody>
                            </table>
                          `;
        }

        function openAuditHistoryModal() {
            AppUI.openModal(`
                            <div class="flex items-start justify-between gap-4 mb-1 pr-10">
                              <h3 class="text-lg font-bold text-navy">Audit History</h3>
                              <select id="audit-history-year-select" class="shrink-0 border border-slate-200 rounded-lg px-2.5 py-1.5 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                                <option value="all">All Years</option>
                                ${yearOptionsHtml(CURRENT_YEAR)}
                              </select>
                            </div>
                            <p class="text-sm text-slate-500 mb-4">Complete record of internal, external, regulatory and financial audits.</p>
                            <div id="audit-history-table-wrap" class="max-h-[26rem] overflow-y-auto pr-1">
                              ${renderAuditHistoryTable(CURRENT_YEAR)}
                            </div>
                            <div class="flex justify-end pt-5">
                              <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Close</button>
                            </div>
                          `, 'lg');

            document.getElementById('audit-history-year-select').addEventListener('change', async function () {
                const val = this.value;
                if (val === 'all') {
                    const perYear = await Promise.all(ALL_YEARS.map(y => loadOverviewYear(y).then(() => AUDITS_CACHE[y] || [])));
                    document.getElementById('audit-history-table-wrap').innerHTML = renderAuditHistoryTable('all', perYear.flat());
                    return;
                }
                await loadOverviewYear(val);
                document.getElementById('audit-history-table-wrap').innerHTML = renderAuditHistoryTable(val);
            });
        }

        document.getElementById('audit-history-btn').addEventListener('click', openAuditHistoryModal);

        function findAuditIndexById(id) {
            return AUDITS.findIndex(a => a.id === id);
        }

        function openAuditDetailModal(id) {
            const idx = findAuditIndexById(id);
            if (idx === -1) return;
            const a = AUDITS[idx];

            const rowsHtml = `
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Date</span><span class="text-sm font-semibold text-navy">${a.date ?? `${a.month} ${a.year}`}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Audit Type</span><span class="text-sm font-semibold text-navy">${a.auditType}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Auditor</span><span class="text-sm font-semibold text-navy">${a.auditor}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Status</span><span class="text-sm font-semibold ${auditBadgeClass(a.status)}">${a.status}</span></div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100"><span class="text-sm text-slate-500">Date Completed</span><span class="text-sm font-semibold text-navy">${a.dateCompleted}</span></div>
                            <div class="py-2"><span class="text-sm text-slate-500 block mb-1">Findings</span><span class="text-sm text-slate-700">${a.findings}</span></div>
                          `;

            AppUI.openModal(`
                            <h3 class="text-lg font-bold text-navy mb-1">${a.auditType} Audit — ${a.month} ${a.year}</h3>
                            <p class="text-sm text-slate-500 mb-4">Full audit record.</p>
                            <div>${rowsHtml}</div>
                            <div class="flex justify-between items-center pt-5">
                              <button type="button" class="js-audit-delete rounded-xl px-5 py-2.5 text-sm font-semibold text-brand-red border border-brand-red/30 hover:bg-brand-red/5" data-id="${a.id}">Delete</button>
                              <div class="flex gap-2">
                                <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Close</button>
                                <button type="button" class="js-audit-edit rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700" data-id="${a.id}">Edit Information</button>
                              </div>
                            </div>
                          `);
        }

        function openAuditEditModal(id) {
            const idx = findAuditIndexById(id);
            if (idx === -1) return;
            const a = AUDITS[idx];

            const auditTypeOptions = AUDIT_TYPES.map(t => `<option value="${t}" ${t === a.auditType ? 'selected' : ''}>${t}</option>`).join('');
            const statusOptions = ['Complaint', 'Pending', 'Failed'].map(s => `<option value="${s}" ${s === a.status ? 'selected' : ''}>${s}</option>`).join('');

            AppUI.openModal(`
                            <h3 class="text-lg font-bold text-navy mb-1">Edit Audit — ${a.month} ${a.year}</h3>
                            <p class="text-sm text-slate-500 mb-4">Update the audit record details below.</p>
                            <form id="audit-edit-form" class="space-y-3">
                              <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Audit Type</label>
                                <select name="auditType" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                                  ${auditTypeOptions}
                                </select>
                              </div>
                              <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Auditor</label>
                                <input type="text" name="auditor" value="${a.auditor}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-navy-600/30" />
                              </div>
                              <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                                <select name="status" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                                  ${statusOptions}
                                </select>
                              </div>
                              <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Date Completed</label>
                                <input type="date" name="dateCompleted" value="${a.dateCompleted}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-navy-600/30" />
                              </div>
                              <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Findings</label>
                                <textarea name="findings" rows="3" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-navy-600/30">${a.findings ?? ''}</textarea>
                              </div>
                            </form>
                            <p id="audit-edit-error" class="text-sm text-brand-red hidden mt-2"></p>
                            <div class="flex justify-end gap-2 pt-5">
                              <button type="button" onclick="openAuditDetailModal(${a.id})" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel</button>
                              <button type="button" id="audit-edit-save" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Save Changes</button>
                            </div>
                          `);

            document.getElementById('audit-edit-save').addEventListener('click', function () {
                const form = document.getElementById('audit-edit-form');
                const errorEl = document.getElementById('audit-edit-error');
                const fd = new FormData(form);
                const btn = this;

                const payload = {
                    auditType: fd.get('auditType'),
                    auditor: fd.get('auditor'),
                    status: fd.get('status'),
                    dateCompleted: fd.get('dateCompleted') || null,
                    findings: fd.get('findings'),
                };

                btn.disabled = true;
                btn.textContent = 'Saving…';
                errorEl.classList.add('hidden');

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                fetch(`{{ url('/financial-reports/audits') }}/${a.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken ?? '',
                    },
                    body: JSON.stringify(payload),
                })
                    .then(async (res) => {
                        if (!res.ok) {
                            const body = await res.json().catch(() => ({}));
                            const message = body.errors
                                ? Object.values(body.errors).flat().join(' ')
                                : (body.message || 'Failed to save changes.');
                            throw new Error(message);
                        }
                        return res.json();
                    })
                    .then(({ audit: updated }) => {
                        Object.assign(a, updated);
                        const cacheYear = String(a.year);
                        const cacheIdx = (AUDITS_CACHE[cacheYear] || []).findIndex(x => x.id === a.id);
                        if (cacheIdx !== -1) AUDITS_CACHE[cacheYear][cacheIdx] = a;
                        AppUI.showToast('Audit updated.', 'success');
                        openAuditDetailModal(a.id);
                    })
                    .catch((err) => {
                        btn.disabled = false;
                        btn.textContent = 'Save Changes';
                        errorEl.textContent = err.message;
                        errorEl.classList.remove('hidden');
                    });
            });
        }

        function deleteAudit(id) {
            const idx = findAuditIndexById(id);
            if (idx === -1) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            fetch(`{{ url('/financial-reports/audits') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken ?? '',
                },
            })
                .then(async (res) => {
                    if (!res.ok) {
                        const body = await res.json().catch(() => ({}));
                        throw new Error(body.message || 'Failed to delete audit.');
                    }
                    return res.json();
                })
                .then(() => {
                    AUDITS.splice(idx, 1);
                    Object.keys(AUDITS_CACHE).forEach(year => {
                        AUDITS_CACHE[year] = (AUDITS_CACHE[year] || []).filter(a => a.id !== id);
                    });
                    AppUI.closeModal();
                    AppUI.showToast('Audit deleted.', 'success');

                    const historyWrap = document.getElementById('audit-history-table-wrap');
                    const historyYearSelect = document.getElementById('audit-history-year-select');
                    if (historyWrap && historyYearSelect) {
                        if (historyYearSelect.value === 'all') {
                            historyWrap.innerHTML = renderAuditHistoryTable('all', Object.values(AUDITS_CACHE).flat());
                        } else {
                            historyWrap.innerHTML = renderAuditHistoryTable(historyYearSelect.value);
                        }
                    }
                })
                .catch((err) => {
                    AppUI.showToast(err.message, 'error');
                });
        }

        document.addEventListener('click', function (e) {
            const viewBtn = e.target.closest('.js-audit-view');
            if (viewBtn) {
                openAuditDetailModal(parseInt(viewBtn.dataset.id, 10));
                return;
            }
            const editBtn = e.target.closest('.js-audit-edit');
            if (editBtn) {
                if (AppUI.requirePermission && !AppUI.requirePermission()) return;
                openAuditEditModal(parseInt(editBtn.dataset.id, 10));
                return;
            }
            const deleteBtn = e.target.closest('.js-audit-delete');
            if (deleteBtn) {
                if (AppUI.requirePermission && !AppUI.requirePermission()) return;
                const id = parseInt(deleteBtn.dataset.id, 10);
                if (window.confirm('Are you sure you want to delete this audit record? This cannot be undone.')) {
                    deleteAudit(id);
                }
            }
        });

        Object.assign(AppUI, (function () {

            function onAuditCreated(audit) {
                const year = String(audit.year);

                if (year === String(CURRENT_YEAR)) {
                    AUDITS = [audit, ...AUDITS];
                }

                AUDITS_CACHE[year] = [audit, ...(AUDITS_CACHE[year] || [])];

                const complianceYearSelect = document.getElementById('compliance-year-select');
                if (complianceYearSelect && complianceYearSelect.value === year) {
                    const counts = { Complaint: 0, Pending: 0, Failed: 0 };
                    AUDITS_CACHE[year].forEach(a => { counts[a.status] = (counts[a.status] || 0) + 1; });
                    const total = Math.max(counts.Complaint + counts.Pending + counts.Failed, 1);
                    const d = {
                        complaint: Math.round(counts.Complaint / total * 100),
                        pending: Math.round(counts.Pending / total * 100),
                        failed: Math.round(counts.Failed / total * 100),
                        total: counts.Complaint + counts.Pending + counts.Failed,
                    };
                    COMPLIANCE_DONUT[year] = d;
                    complianceDonut.data.datasets[0].data = [d.complaint, d.pending, d.failed];
                    complianceDonut.update();
                    document.getElementById('compliance-total-audits').textContent = d.total;
                    document.getElementById('compliance-complaint-pct').textContent = d.complaint + '%';
                    document.getElementById('compliance-pending-pct').textContent = d.pending + '%';
                    document.getElementById('compliance-failed-pct').textContent = d.failed + '%';
                }

                const historyWrap = document.getElementById('audit-history-table-wrap');
                const historyYearSelect = document.getElementById('audit-history-year-select');
                
                if (historyWrap && historyYearSelect) {
                    if (historyYearSelect.value === 'all') {
                        historyWrap.innerHTML = renderAuditHistoryTable('all', Object.values(AUDITS_CACHE).flat());
                    } else if (historyYearSelect.value === year) {
                        historyWrap.innerHTML = renderAuditHistoryTable(year);
                    }
                }
            }

            return {
                onAuditCreated
            };
        })());
    </script>
@endpush