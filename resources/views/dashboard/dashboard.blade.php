@extends('layouts.app')

@section('page-title', 'Finance & Accounting | Dashboard')
@section('page-title-heading', 'Dashboard')
@section('page-subtitle', 'Monitor your financial performance and accounting activities in one place.')

@section('content')

            {{-- Welcome banner --}}
            <div class="relative overflow-hidden rounded-2xl bg-navy text-white p-6 sm:p-8 mb-6 shadow-card">
                <div class="relative z-10 max-w-2xl">
                    <h3 class="text-2xl sm:text-3xl font-extrabold mb-2">Welcome Back, Admin {{ $adminFirstName ?? 'Harvie' }}!</h3>
                    <p class="text-slate-200 text-sm sm:text-base leading-relaxed">
                        Here's a snapshot of your company's financial health for {{ $currentQuarter ?? 'Q2 · FY 2026' }}.
                        All systems are in good standing.
                    </p>

                    <div class="flex flex-wrap gap-3 mt-6 no-print">
                        <a href="{{ route('financial-reports.overview') }}"
                            class="flex items-center gap-2 rounded-xl bg-brand-green px-5 py-3 text-sm font-semibold text-navy shadow-card hover:bg-brand-greenDark hover:text-white transition">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i> Financial Reports
                        </a>
                        <button type="button" onclick="AppUI.openExportSnapshotModal()"
                            class="flex items-center gap-2 rounded-xl bg-white/10 border border-white/20 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                            <i data-lucide="download" class="w-4 h-4"></i> Export Snapshot
                        </button>
                    </div>
                </div>
                <div class="pointer-events-none absolute -right-10 -bottom-16 w-64 h-64 rounded-full bg-white/5"></div>
                <div class="pointer-events-none absolute right-24 -top-16 w-40 h-40 rounded-full bg-white/5"></div>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5 mb-6">
                @php
                    $stats = [
                        ['label' => 'Total Assets', 'value' => $totalAssets ?? '₱12,345,678', 'trend' => '8.5%', 'icon' => 'coins', 'iconBg' => 'bg-brand-green'],
                        ['label' => 'Net Profit', 'value' => $netProfit ?? '₱12,345,678', 'trend' => '8%', 'icon' => 'trending-up', 'iconBg' => 'bg-navy'],
                        ['label' => 'Cash on Hand', 'value' => $cashOnHand ?? '₱12,345,678', 'trend' => '8.5%', 'icon' => 'wallet', 'iconBg' => 'bg-brand-orange'],
                        ['label' => 'Open Tasks', 'value' => $openTasks ?? '12', 'trend' => '8.5%', 'trendLabel' => 'Overdue', 'trendColor' => 'text-brand-red', 'icon' => 'pie-chart', 'iconBg' => 'bg-brand-red'],
                    ];
                @endphp

                @foreach ($stats as $stat)
                    <div class="bg-white rounded-2xl shadow-card p-5 sm:p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full {{ $stat['iconBg'] }} text-white shadow-md">
                                <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <span class="flex items-center gap-1 text-xs font-semibold {{ $stat['trendColor'] ?? 'text-brand-green' }}">
                                <i data-lucide="arrow-up" class="w-3.5 h-3.5"></i> {{ $stat['trend'] }} {{ $stat['trendLabel'] ?? '' }}
                            </span>
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">{{ $stat['label'] }}</p>
                        <p class="text-xl sm:text-2xl font-extrabold text-navy">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Module cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
                @php
                    $modules = [
                        [
                            'icon' => 'book-text', 'iconBg' => 'bg-brand-green/20', 'iconColor' => 'text-brand-greenDark',
                            'title' => 'General Ledger', 'subtitle' => 'Charts of accounts & journal entry',
                            'value' => $ledgerEntries ?? '12,480', 'footnote' => 'Entries this month', 'href' => route('ledger.index'),
                        ],
                        [
                            'icon' => 'user', 'iconBg' => 'bg-brand-orange/20', 'iconColor' => 'text-brand-orange',
                            'title' => 'Account Receivable', 'subtitle' => 'Customer invoice & collection',
                            'value' => $accountReceivable ?? '₱320,000', 'footnote' => 'Outstanding', 'href' => route('receivable.dashboard'),
                        ],
                        [
                            'icon' => 'wallet', 'iconBg' => 'bg-brand-red/20', 'iconColor' => 'text-brand-red',
                            'title' => 'Account Payable', 'subtitle' => 'Customer bills & payments',
                            'value' => $accountPayable ?? '₱210,000', 'footnote' => 'Due in 30 days', 'href' => route('ap.dashboard'),
                        ],
                        [
                            'icon' => 'clipboard-check', 'iconBg' => 'bg-brand-blue/20', 'iconColor' => 'text-brand-blue',
                            'title' => 'Financial Reports', 'subtitle' => 'Statements & compliance',
                            'value' => $complianceScore ?? '98%', 'footnote' => 'Compliance score', 'href' => route('financial-reports.overview'),
                        ],
                        [
                            'icon' => 'box', 'iconBg' => 'bg-brand-green/20', 'iconColor' => 'text-brand-greenDark',
                            'title' => 'Fixed Assets', 'subtitle' => 'Property, equipment, depreciation',
                            'value' => $fixedAssets ?? '₱1,250,000', 'footnote' => 'Net book value', 'href' => route('fixed-assets.index'),
                        ],
                        [
                            'icon' => 'trending-up', 'iconBg' => 'bg-brand-orange/20', 'iconColor' => 'text-brand-orange',
                            'title' => 'Budget Forecasting', 'subtitle' => 'Charts of accounts & journal entry',
                            'value' => $budgetEntries ?? '12,480', 'footnote' => 'Entries this month', 'href' => route('budget.view'),
                        ],
                    ];
                @endphp

                @foreach ($modules as $mod)
                    <a href="{{ $mod['href'] }}"
                        class="group block bg-white rounded-2xl shadow-card p-5 sm:p-6 border border-transparent hover:border-navy-100 hover:shadow-lg transition">
                        <div class="flex items-start justify-between mb-5">
                            <div class="flex h-14 w-14 items-center justify-center rounded-xl {{ $mod['iconBg'] }} {{ $mod['iconColor'] }}">
                                <i data-lucide="{{ $mod['icon'] }}" class="w-6 h-6"></i>
                            </div>
                            <i data-lucide="arrow-up-right"
                                class="w-4 h-4 text-slate-300 group-hover:text-navy transition-colors"></i>
                        </div>
                        <h4 class="font-bold text-navy text-base sm:text-lg">{{ $mod['title'] }}</h4>
                        <p class="text-slate-400 text-xs sm:text-sm mt-0.5">{{ $mod['subtitle'] }}</p>
                        <p class="text-lg sm:text-xl font-extrabold text-navy mt-4">{{ $mod['value'] }}</p>
                        <p class="text-slate-400 text-xs mt-0.5 uppercase tracking-wide">{{ $mod['footnote'] }}</p>
                    </a>
                @endforeach
            </div>

@endsection