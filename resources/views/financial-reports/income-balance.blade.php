@extends('layouts.app')

@section('page-title', 'Finance & Accounting | Financial Reports')
@section('page-title-heading', 'Financial Reports')
@section('page-subtitle', 'Monitor financial performance and ensure regulatory compliance.')

@section('content')
    @include('financial-reports.header')

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

        {{-- ============ INCOME STATEMENTS ============ --}}
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-card p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-bold text-navy">INCOME STATEMENTS</p>
                <div class="relative">
                    <select id="income-year-select"
                        class="appearance-none flex items-center gap-1 text-xs font-medium text-slate-600 border border-slate-200 rounded-lg pl-2.5 pr-7 py-1 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" @selected($y == $selectedYear)>{{ $y }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down"
                        class="w-3.5 h-3.5 text-slate-400 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>

            <div id="income-statement-body" class="space-y-5 text-sm flex-1">
                {{-- Rendered by JS from data fetched per selected year --}}
            </div>

            <div class="mt-6 flex items-center justify-between rounded-xl bg-navy px-5 py-4 text-white">
                <span class="font-semibold">NET INCOME</span>
                <span id="income-net-income" class="text-xl font-extrabold">₱0</span>
            </div>
        </div>

        {{-- ============ BALANCE SHEETS ============ --}}
        <div class="xl:col-span-3">
            <p class="text-sm font-bold text-navy mb-4">BALANCE SHEETS</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Total Assets --}}
                <div class="bg-white rounded-2xl shadow-card p-6 flex flex-col">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-navy">Total Assets</p>
                        <div class="relative">
                            <select id="assets-year-select"
                                class="appearance-none flex items-center gap-1 text-xs font-medium text-slate-600 border border-slate-200 rounded-lg pl-2.5 pr-7 py-1 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" @selected($y == $selectedYear)>{{ $y }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down"
                                class="w-3.5 h-3.5 text-slate-400 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>
                    <p id="assets-total-headline" class="text-2xl font-extrabold text-navy mb-4">₱0</p>

                    <div id="assets-lines" class="space-y-3 text-sm flex-1"></div>

                    <div class="mt-6 flex items-center justify-between rounded-xl bg-navy px-5 py-3 text-white">
                        <span class="text-sm font-semibold">TOTAL</span>
                        <span id="assets-total-footer" class="text-lg font-extrabold">₱0</span>
                    </div>
                </div>

                {{-- Total Liabilities --}}
                <div class="bg-white rounded-2xl shadow-card p-6 flex flex-col">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-brand-greenDark">Total Liabilities</p>
                        <div class="relative">
                            <select id="liabilities-year-select"
                                class="appearance-none flex items-center gap-1 text-xs font-medium text-slate-600 border border-slate-200 rounded-lg pl-2.5 pr-7 py-1 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" @selected($y == $selectedYear)>{{ $y }}</option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down"
                                class="w-3.5 h-3.5 text-slate-400 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>
                    <p id="liabilities-total-headline" class="text-2xl font-extrabold text-navy mb-4">₱0</p>

                    <div id="liabilities-lines" class="space-y-3 text-sm flex-1"></div>

                    <div class="mt-6 flex items-center justify-between rounded-xl bg-brand-green px-5 py-3 text-white">
                        <span class="text-sm font-semibold">TOTAL</span>
                        <span id="liabilities-total-footer" class="text-lg font-extrabold">₱0</span>
                    </div>
                </div>

                {{-- Total Equity --}}
                <div class="sm:col-span-2 flex justify-center">
                    <div class="w-full sm:max-w-md bg-white rounded-2xl shadow-card p-6 flex flex-col">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-brand-orange">Total Equity</p>
                            <div class="relative">
                                <select id="equity-year-select"
                                    class="appearance-none flex items-center gap-1 text-xs font-medium text-slate-600 border border-slate-200 rounded-lg pl-2.5 pr-7 py-1 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                                    @foreach ($years as $y)
                                        <option value="{{ $y }}" @selected($y == $selectedYear)>{{ $y }}</option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down"
                                    class="w-3.5 h-3.5 text-slate-400 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                        <p id="equity-total-headline" class="text-2xl font-extrabold text-navy mb-4">₱0</p>

                        <div id="equity-lines" class="space-y-3 text-sm flex-1"></div>

                        <div class="mt-6 flex items-center justify-between rounded-xl bg-brand-orange px-5 py-3 text-white">
                            <span class="text-sm font-semibold">TOTAL</span>
                            <span id="equity-total-footer" class="text-lg font-extrabold">₱0</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ==========================================================================
        // Real data for the currently-selected year, computed server-side by
        // FinancialReportService from gl_accounts / gl_entries / gl_entry_lines.
        // Switching the year re-fetches from the server — nothing here is hardcoded.
        // ==========================================================================
        const CURRENT_YEAR = {{ $selectedYear }};
        let INCOME_BASE = @json($incomeStatement);
        let BALANCE_BASE = @json($balanceSheet);

        function formatSigned(n) {
            return (n < 0 ? '-₱' : '₱') + Math.abs(Math.round(n)).toLocaleString();
        }

        // ==========================================================================
        // Income Statement rendering
        // ==========================================================================
        function renderIncomeStatement(data) {
            const groupsHtml = data.groups.map(group => {
                const linesHtml = group.lines.map(([label, amount]) => `
          <div class="flex items-center justify-between text-slate-500">
            <span>${label}</span>
            <span>${formatSigned(amount)}</span>
          </div>
        `).join('');
                return `
          <div>
            <div class="flex items-center justify-between font-bold text-navy">
              <span>${group.label}</span>
              <span>${formatSigned(group.amount)}</span>
            </div>
            <div class="mt-1.5 space-y-1.5">${linesHtml}</div>
          </div>
        `;
            }).join('');

            const subtotalsHtml = data.subtotals.map(([label, amount]) => `
        <div class="flex items-center justify-between font-bold text-navy">
          <span>${label}</span>
          <span>${formatSigned(amount)}</span>
        </div>
      `).join('');

            document.getElementById('income-statement-body').innerHTML = `
        ${groupsHtml}
        <div class="pt-1 space-y-2">${subtotalsHtml}</div>
      `;

            document.getElementById('income-net-income').textContent = formatSigned(data.netIncome);
        }

        // ==========================================================================
        // Balance Sheet card rendering
        // ==========================================================================
        function renderBalanceCard(key, data) {
            const section = data[key];
            const total = formatSigned(section.total);

            document.getElementById(`${key}-total-headline`).textContent = total;
            document.getElementById(`${key}-total-footer`).textContent = total;

            document.getElementById(`${key}-lines`).innerHTML = section.lines.map(([label, amount]) => `
        <div class="flex items-center justify-between">
          <span class="text-slate-500">${label}</span>
          <span class="font-medium text-slate-700">${formatSigned(amount)}</span>
        </div>
      `).join('');
        }

        // ==========================================================================
        // Fetch + render a given year (server computes it live from the GL)
        // ==========================================================================
        async function loadYear(year) {
            if (String(year) === String(CURRENT_YEAR)) {
                renderIncomeStatement(INCOME_BASE);
                renderBalanceCard('assets', BALANCE_BASE);
                renderBalanceCard('liabilities', BALANCE_BASE);
                renderBalanceCard('equity', BALANCE_BASE);
                return;
            }

            const res = await fetch(`{{ url('/financial-reports/income-balance/data') }}?year=${year}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) return;
            const data = await res.json();
            renderIncomeStatement(data.incomeStatement);
            renderBalanceCard('assets', data.balanceSheet);
            renderBalanceCard('liabilities', data.balanceSheet);
            renderBalanceCard('equity', data.balanceSheet);
        }

        // ==========================================================================
        // Initialize and Wire Up Selects
        // ==========================================================================
        loadYear(CURRENT_YEAR);

        document.getElementById('income-year-select').addEventListener('change', function () {
            loadYear(this.value);
        });
        document.getElementById('assets-year-select').addEventListener('change', function () {
            loadYear(this.value);
        });
        document.getElementById('liabilities-year-select').addEventListener('change', function () {
            loadYear(this.value);
        });
        document.getElementById('equity-year-select').addEventListener('change', function () {
            loadYear(this.value);
        });
    </script>
@endpush