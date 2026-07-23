<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Finance & Accounting')</title>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    {{-- npm run dev --}}

    <style>
        html {
            scrollbar-gutter: stable;
        }
    </style>

    {{-- Tailwind CSS via CDN — no build step required --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            DEFAULT: '#16265B',
                            50: '#eef0fa',
                            100: '#dfe3f5',
                            600: '#1f3372',
                            700: '#182a63',
                            800: '#16265B',
                            900: '#0f1a42',
                        },
                        brand: {
                            green: '#1FCB88',
                            greenDark: '#12A86F',
                            blue: '#2F4CDD',
                            orange: '#F5941F',
                            red: '#EF4B4B',
                        }
                    },
                    boxShadow: {
                        card: '0 2px 10px 0 rgba(16, 24, 64, 0.06)',
                    }
                }
            }
        }
    </script>

    {{-- Inter font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Lucide icons (pinned version) --}}
    <script src="https://unpkg.com/lucide@1.21.0/dist/umd/lucide.js"></script>

    {{-- Chart.js (used by pages that render charts, e.g. Financial Reports) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .nav-link.active {
            background: linear-gradient(180deg, #22D690, #12A86F);
            box-shadow: 0 6px 14px -4px rgba(18, 168, 111, .55);
        }

        .dropdown-panel {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            z-index: 40;
        }

        .dropdown-panel.open {
            display: block;
        }

        #app-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 26, 66, 0.55);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        #app-modal-backdrop.open {
            display: flex;
        }

        #app-modal-box {
            max-height: 90vh;
            overflow-y: auto;
        }

        #app-toast-stack {
            position: fixed;
            bottom: 1.25rem;
            right: 1.25rem;
            z-index: 110;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            max-width: 22rem;
        }

        .app-toast {
            animation: toast-in .2s ease-out;
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ---------- Mobile sidebar drawer ---------- */
        #mobile-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 26, 66, 0.55);
            z-index: 60;
        }

        #mobile-sidebar-backdrop.open {
            display: block;
        }

        #mobile-sidebar {
            transform: translateX(-100%);
            transition: transform .25s ease;
        }

        #mobile-sidebar.open {
            transform: translateX(0);
        }

        @media print {

            aside,
            .no-print {
                display: none !important;
            }

            main {
                padding: 0 !important;
                max-width: 100% !important;
            }

            body {
                background: white !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-slate-100 text-slate-800 antialiased">

    @php
        // Each item's route (if it exists) drives both its href and its
        // active state, so the sidebar highlights the correct page
        // automatically instead of relying on a hardcoded 'active' flag.
        //
        // Items can optionally have a 'children' array (currently only
        // Account Payable does) to render a collapsible submenu instead
        // of a plain link.
        $navItems = [
            ['icon' => 'layout-dashboard', 'label' => 'Dashboard', 'route' => 'dashboard'],
            ['icon' => 'book-text', 'label' => 'General Ledger', 'route' => 'ledger.index'],
            ['icon' => 'user', 'label' => 'Account Receivable', 'route' => 'receivable.dashboard'],
            [
                'icon' => 'wallet',
                'label' => 'Account Payable',
                'route' => 'ap.dashboard',
                'children' => [
                    ['label' => 'Overview', 'route' => 'ap.dashboard', 'patterns' => ['ap.dashboard']],
                    ['label' => 'Purchase Orders & Goods Receipts', 'route' => 'ap.po.index', 'patterns' => ['ap.po.*', 'ap.grn.*']],
                    ['label' => 'Record Incoming Supplier Invoice', 'route' => 'ap.record', 'patterns' => ['ap.record', 'ap.record.*']],
                    ['label' => 'Review & Verify Invoice', 'route' => 'ap.review', 'patterns' => ['ap.review', 'ap.review.*'], 'badge' => $pendingReviewCount ?? 0],
                    ['label' => 'Three-Way Match', 'route' => 'ap.match', 'patterns' => ['ap.match', 'ap.match.*']],
                    ['label' => 'Track Due Dates & Schedule Payments', 'route' => 'ap.schedule', 'patterns' => ['ap.schedule', 'ap.schedule.*']],
                    ['label' => 'Payment Processing & Remittance Advice', 'route' => 'ap.payment', 'patterns' => ['ap.payment', 'ap.payment.*']],
                ],
            ],
            ['icon' => 'box', 'label' => 'Fixed Assets', 'route' => 'fixed-assets.index'],
            ['icon' => 'clipboard-check', 'label' => 'Financial Reports', 'route' => 'financial-reports.overview'],
            ['icon' => 'trending-up', 'label' => 'Budget Forecasting', 'route' => 'budget.view'],

        ];

        foreach ($navItems as &$item) {
            $item['href'] = $item['route'] && \Illuminate\Support\Facades\Route::has($item['route'])
                ? route($item['route'])
                : '#';

            if (!empty($item['children'])) {
                foreach ($item['children'] as &$child) {
                    $child['href'] = $child['route'] && \Illuminate\Support\Facades\Route::has($child['route'])
                        ? route($child['route'])
                        : '#';
                    $child['active'] = $child['route'] && \Illuminate\Support\Facades\Route::has($child['route'])
                        ? request()->routeIs(...($child['patterns'] ?? [$child['route']]))
                        : false;
                }
                unset($child);
                $item['active'] = collect($item['children'])->contains('active', true);
            } else {
                if ($item['route'] && \Illuminate\Support\Facades\Route::has($item['route'])) {
                    // Extract root prefix (e.g., "financial-reports" from "financial-reports.overview")
                    $routePrefix = explode('.', $item['route'])[0];

                    // Match either the exact root route OR any of its sub-routes (e.g., financial-reports.*)
                    $item['active'] = request()->routeIs($item['route']) || request()->routeIs($routePrefix . '.*');
                } else {
                    $item['active'] = false;
                }
            }
        }
        unset($item);
    @endphp

    <div class="flex min-h-screen">

        {{-- ============ SIDEBAR (desktop) ============ --}}
        <aside
            class="hidden lg:flex fixed left-0 top-0 h-screen w-72 flex-col bg-navy text-white px-6 py-8 overflow-y-auto z-50">
            <div class="mb-10">
                <h1 class="text-2xl font-extrabold leading-tight">Finance &amp;<br>Accounting</h1>
            </div>

            <nav class="flex-1 space-y-1.5">
                @foreach ($navItems as $item)
                    @if (!empty($item['children']))
                        <div>
                            <button type="button" data-submenu-toggle
                                class="nav-link w-full flex items-center justify-between gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition
                                                                               {{ $item['active'] ? 'active text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                                <span class="flex items-center gap-3">
                                    <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 shrink-0"></i>
                                    <span>{{ $item['label'] }}</span>
                                </span>
                                <span data-chevron
                                    class="shrink-0 transition-transform {{ $item['active'] ? 'rotate-180' : '' }}">
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </span>
                            </button>

                            <div data-submenu-panel
                                class="{{ $item['active'] ? '' : 'hidden' }} mt-1 ml-4 pl-4 border-l border-white/10 space-y-0.5">
                                @foreach ($item['children'] as $child)
                                    <a href="{{ $child['href'] }}"
                                        class="flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-sm transition
                                                                                                           {{ $child['active'] ? 'text-brand-green font-semibold' : 'text-slate-300 hover:text-white' }}">
                                        <span>{{ $child['label'] }}</span>
                                        @if (!empty($child['badge']))
                                            <span
                                                class="flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-orange px-1.5 text-[11px] font-bold text-white">
                                                {{ $child['badge'] }}
                                            </span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['href'] }}"
                            class="nav-link flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition
                                                                           {{ !empty($item['active']) ? 'active text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 shrink-0"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>

            <button type="button" onclick="AppUI.openAccountModal()"
                class="account-trigger mt-8 flex items-center gap-3 rounded-2xl bg-brand-green/90 px-4 py-3 text-left hover:bg-brand-green transition">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-navy text-white">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
                <p class="account-role-name font-bold text-navy text-sm uppercase">
                    {{ session('active_role_label', 'Administrator') }}
                </p>
            </button>
        </aside>

        {{-- ============ SIDEBAR (mobile drawer) ============ --}}
        <div id="mobile-sidebar-backdrop" class="lg:hidden" onclick="AppUI.closeMobileSidebar()"></div>
        <aside id="mobile-sidebar"
            class="lg:hidden fixed left-0 top-0 h-screen w-72 flex flex-col bg-navy text-white px-6 py-8 overflow-y-auto z-[70]">
            <div class="mb-10 flex items-center justify-between">
                <h1 class="text-2xl font-extrabold leading-tight">Finance &amp;<br>Accounting</h1>
                <button type="button" onclick="AppUI.closeMobileSidebar()"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg hover:bg-white/10">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <nav class="flex-1 space-y-1.5">
                @foreach ($navItems as $item)
                    @if (!empty($item['children']))
                        <div>
                            <button type="button" data-submenu-toggle
                                class="nav-link w-full flex items-center justify-between gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition
                                                                               {{ $item['active'] ? 'active text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                                <span class="flex items-center gap-3">
                                    <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 shrink-0"></i>
                                    <span>{{ $item['label'] }}</span>
                                </span>
                                <span data-chevron
                                    class="shrink-0 transition-transform {{ $item['active'] ? 'rotate-180' : '' }}">
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </span>
                            </button>

                            <div data-submenu-panel
                                class="{{ $item['active'] ? '' : 'hidden' }} mt-1 ml-4 pl-4 border-l border-white/10 space-y-0.5">
                                @foreach ($item['children'] as $child)
                                    <a href="{{ $child['href'] }}"
                                        class="flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-sm transition
                                                                                                           {{ $child['active'] ? 'text-brand-green font-semibold' : 'text-slate-300 hover:text-white' }}">
                                        <span>{{ $child['label'] }}</span>
                                        @if (!empty($child['badge']))
                                            <span
                                                class="flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-orange px-1.5 text-[11px] font-bold text-white">
                                                {{ $child['badge'] }}
                                            </span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['href'] }}"
                            class="nav-link flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition
                                                                           {{ !empty($item['active']) ? 'active text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 shrink-0"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>

            <button type="button" onclick="AppUI.openAccountModal()"
                class="account-trigger mt-8 flex items-center gap-3 rounded-2xl bg-brand-green/90 px-4 py-3 text-left hover:bg-brand-green transition">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-navy text-white">
                    <i data-lucide="user" class="w-7 h-7"></i>
                </div>
                <p class="account-role-name font-bold text-navy text-sm uppercase">
                    {{ session('active_role_label', 'Administrator') }}
                </p>
            </button>
        </aside>

        {{-- ============ MAIN CONTENT ============ --}}
        <main class="flex-1 lg:ml-72 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 max-w-[1600px] mx-auto w-full">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex items-start gap-3">
                    <button type="button" onclick="AppUI.openMobileSidebar()"
                        class="lg:hidden flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm border border-slate-200 hover:bg-slate-50 no-print">
                        <i data-lucide="menu" class="w-5 h-5 text-navy"></i>
                    </button>
                    <div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-navy">
                            @yield('page-title-heading', 'Dashboard')</h2>
                        <p class="text-slate-500 mt-1 text-sm sm:text-base">
                            @yield('page-subtitle', 'Monitor your financial performance and accounting activities in one place.')
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- Notifications --}}
                    <div class="relative no-print">
                        <button id="notif-toggle" type="button"
                            class="relative flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-sm border border-slate-200 hover:bg-slate-50">
                            <i data-lucide="bell" class="w-5 h-5 text-navy"></i>
                            <span id="notif-badge"
                                class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-brand-red text-white text-[10px] font-bold">3</span>
                        </button>

                        <div id="notif-panel"
                            class="dropdown-panel w-80 max-w-[85vw] bg-white rounded-2xl shadow-card border border-slate-100 p-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="font-bold text-navy text-sm">Notifications</p>
                                <button id="notif-mark-read" type="button"
                                    class="text-xs font-semibold text-navy-600 hover:underline">Mark all as
                                    read</button>
                            </div>
                            <div id="notif-list" class="space-y-3 max-h-80 overflow-y-auto">
                                @php
                                    $notifications = [
                                        ['icon' => 'file-clock', 'color' => 'bg-brand-orange', 'title' => 'Payroll tax filing due tomorrow', 'time' => '2h ago', 'unread' => true],
                                        ['icon' => 'shield-check', 'color' => 'bg-brand-green', 'title' => 'March compliance audit failed review', 'time' => '5h ago', 'unread' => true],
                                        ['icon' => 'trending-up', 'color' => 'bg-navy', 'title' => 'Operations spent 6.8% over budget', 'time' => 'Yesterday', 'unread' => true],
                                        ['icon' => 'circle-check-big', 'color' => 'bg-brand-green', 'title' => 'VAT/GST return filed successfully', 'time' => '2 days ago', 'unread' => false],
                                    ];
                                @endphp
                                @foreach ($notifications as $n)
                                    <div class="flex items-start gap-3 {{ $n['unread'] ? '' : 'opacity-60' }}">
                                        <div
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $n['color'] }} text-white">
                                            <i data-lucide="{{ $n['icon'] }}" class="w-4 h-4"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-slate-700 leading-snug">{{ $n['title'] }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $n['time'] }}</p>
                                        </div>
                                        @if ($n['unread'])
                                            <span class="w-2 h-2 rounded-full bg-brand-blue mt-1.5 shrink-0"></span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Help --}}
                    <div class="relative no-print">
                        <button id="help-toggle" type="button"
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-sm border border-slate-200 hover:bg-slate-50">
                            <i data-lucide="circle-help" class="w-5 h-5 text-navy"></i>
                        </button>

                        <div id="help-panel"
                            class="dropdown-panel w-72 max-w-[85vw] bg-white rounded-2xl shadow-card border border-slate-100 p-4">
                            <p class="font-bold text-navy text-sm mb-3">Help &amp; Support</p>
                            <div class="space-y-1 text-sm">
                                <a href="#"
                                    class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-slate-600 hover:bg-slate-50">
                                    <i data-lucide="book-open" class="w-4 h-4 text-navy-600"></i> Documentation
                                </a>
                                <a href="#"
                                    class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-slate-600 hover:bg-slate-50">
                                    <i data-lucide="life-buoy" class="w-4 h-4 text-navy-600"></i> Contact Support
                                </a>
                                <a href="#"
                                    class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-slate-600 hover:bg-slate-50">
                                    <i data-lucide="keyboard" class="w-4 h-4 text-navy-600"></i> Keyboard Shortcuts
                                </a>
                                <button type="button" onclick="AppUI.openReportIssueModal()"
                                    class="w-full flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-slate-600 hover:bg-slate-50 text-left">
                                    <i data-lucide="flag" class="w-4 h-4 text-brand-red"></i> Report an Issue
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')

        </main>
    </div>

    {{-- ============ MODAL ============ --}}
    <div id="app-modal-backdrop" onclick="if(event.target === this) AppUI.closeModal()">
        <div id="app-modal-box"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-5 sm:p-6 relative transition-all">
            <button type="button" onclick="AppUI.closeModal()"
                class="absolute top-4 right-4 flex h-8 w-8 items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 z-10">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div id="app-modal-content"><!-- injected by JS --></div>
        </div>
    </div>

    {{-- ============ TOASTS ============ --}}
    <div id="app-toast-stack"></div>

    <script>
        lucide.createIcons();

        const AppUI = (function () {

            // ---------------- Mobile sidebar ----------------
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileBackdrop = document.getElementById('mobile-sidebar-backdrop');

            function openMobileSidebar() {
                mobileSidebar.classList.add('open');
                mobileBackdrop.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileSidebar() {
                mobileSidebar.classList.remove('open');
                mobileBackdrop.classList.remove('open');
                document.body.style.overflow = '';
            }

            // ---------------- Modal ----------------
            const modalBackdrop = document.getElementById('app-modal-backdrop');
            const modalContent = document.getElementById('app-modal-content');
            const modalBox = document.getElementById('app-modal-box');
            const modalSizes = { sm: 'max-w-md', md: 'max-w-lg', lg: 'max-w-2xl', xl: 'max-w-4xl' };

            function openModal(html, size = 'md') {
                modalContent.innerHTML = html;
                Object.values(modalSizes).forEach((c) => modalBox.classList.remove(c));
                modalBox.classList.add(modalSizes[size] || modalSizes.md);
                modalBackdrop.classList.add('open');
                document.body.style.overflow = 'hidden';
                lucide.createIcons();
            }

            function closeModal() {
                modalBackdrop.classList.remove('open');
                document.body.style.overflow = '';
                modalContent.innerHTML = '';
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') { closeModal(); closeMobileSidebar(); }
            });

            // ---------------- Toasts ----------------
            const toastStack = document.getElementById('app-toast-stack');
            const toastStyles = {
                success: { bg: 'bg-brand-green', icon: 'circle-check-big' },
                error: { bg: 'bg-brand-red', icon: 'circle-alert' },
                info: { bg: 'bg-navy', icon: 'info' },
            };

            function showToast(message, type = 'success') {
                const style = toastStyles[type] || toastStyles.info;
                const el = document.createElement('div');
                el.className = `app-toast flex items-center gap-3 rounded-xl ${style.bg} text-white px-4 py-3 shadow-card text-sm font-medium`;
                el.innerHTML = `<i data-lucide="${style.icon}" class="w-4 h-4 shrink-0"></i><span>${message}</span>`;
                toastStack.appendChild(el);
                lucide.createIcons();
                setTimeout(() => {
                    el.style.transition = 'opacity .25s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 250);
                }, 3500);
            }

            // ---------------- Dropdowns ----------------
            function wireDropdown(toggleId, panelId) {
                const toggle = document.getElementById(toggleId);
                const panel = document.getElementById(panelId);
                if (!toggle || !panel) return;

                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const willOpen = !panel.classList.contains('open');
                    closeAllDropdowns();
                    if (willOpen) panel.classList.add('open');
                });

                panel.addEventListener('click', (e) => e.stopPropagation());
            }

            function closeAllDropdowns() {
                document.querySelectorAll('.dropdown-panel.open').forEach((p) => p.classList.remove('open'));
            }

            document.addEventListener('click', closeAllDropdowns);
            wireDropdown('notif-toggle', 'notif-panel');
            wireDropdown('help-toggle', 'help-panel');

            const notifBadge = document.getElementById('notif-badge');
            const notifMarkRead = document.getElementById('notif-mark-read');
            if (notifMarkRead) {
                notifMarkRead.addEventListener('click', () => {
                    document.querySelectorAll('#notif-list > div').forEach((row) => row.classList.add('opacity-60'));
                    document.querySelectorAll('#notif-list .bg-brand-blue').forEach((dot) => dot.remove());
                    if (notifBadge) notifBadge.remove();
                    showToast('All notifications marked as read.', 'success');
                });
            }

            // ---------------- Export Snapshot ----------------
            function openExportSnapshotModal() {
                openModal(`
          <h3 class="text-lg font-bold text-navy mb-1">Export Snapshot</h3>
          <p class="text-sm text-slate-500 mb-5">Download a quick snapshot of the current dashboard figures.</p>
          <form id="export-snapshot-form" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Format</label>
              <div class="flex flex-wrap gap-4 text-sm text-slate-600">
                <label class="flex items-center gap-2"><input type="radio" name="format" value="pdf" checked> PDF</label>
                <label class="flex items-center gap-2"><input type="radio" name="format" value="csv"> CSV</label>
              </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
              <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel</button>
              <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Download</button>
            </div>
          </form>
        `, 'sm');

                document.getElementById('export-snapshot-form').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const format = new FormData(e.target).get('format');
                    const rows = [
                        ['Metric', 'Value'],
                        ['Total Assets', document.querySelectorAll('.grid.xl\\:grid-cols-4 p.text-xl, .grid.xl\\:grid-cols-4 p.text-2xl')[0]?.innerText || ''],
                    ];
                    closeModal();
                    if (format === 'csv') {
                        const csv = 'Metric,Value\n' + Array.from(document.querySelectorAll('.xl\\:grid-cols-4 > div')).map(card => {
                            const label = card.querySelector('p.text-xs')?.innerText || '';
                            const value = card.querySelector('p.text-xl, p.text-2xl')?.innerText || '';
                            return `"${label}","${value}"`;
                        }).join('\n');
                        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `dashboard-snapshot-${new Date().toISOString().slice(0, 10)}.csv`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        URL.revokeObjectURL(url);
                        showToast('Snapshot exported as CSV.', 'success');
                    } else {
                        setTimeout(() => window.print(), 150);
                        showToast('Snapshot ready — check your print dialog to save as PDF.', 'success');
                    }
                });
            }

            // ---------------- Report an Issue ----------------
            function openReportIssueModal() {
                openModal(`
          <h3 class="text-lg font-bold text-navy mb-1">Report an Issue</h3>
          <p class="text-sm text-slate-500 mb-5">Tell us what went wrong — we'll follow up by email.</p>
          <form id="report-issue-form" class="space-y-4">
            <textarea name="description" rows="4" required placeholder="Describe the issue…"
                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm resize-none"></textarea>
            <div class="flex justify-end gap-3">
              <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel</button>
              <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Send</button>
            </div>
          </form>
        `);
                document.getElementById('report-issue-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    closeModal();
                    showToast('Thanks — your report has been sent.', 'success');
                });
            }

            // ---------------- Account / Switch Account ----------------
            const ROLES = [
                { key: 'administrator', label: 'Administrator' },
                { key: 'finance_manager', label: 'Finance Manager' },
                { key: 'accountant', label: 'Accountant' },
                { key: 'ap_staff', label: 'AP Staff' },
                { key: 'ar_staff', label: 'AR Staff' },
                { key: 'fa_staff', label: 'FA Staff' },
                { key: 'auditor', label: 'Auditor' },
            ];

            function currentRoleLabel() {
                const el = document.querySelector('.account-role-name, .account-role-label');
                return el ? el.textContent.trim() : 'Administrator';
            }

            function openAccountModal() {
                openModal(`
      <div class="flex items-center gap-3 mb-5">
        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-navy text-white">
          <i data-lucide="user" class="w-7 h-7"></i>
        </div>
        <div class="leading-tight">
          <p class="font-bold text-navy text-base uppercase">${currentRoleLabel()}</p>
        </div>
      </div>

      <div class="border-t border-slate-100 pt-4">
        <button type="button" onclick="AppUI.openSwitchAccountModal()"
          class="w-full flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-navy hover:bg-slate-50 transition">
          <span class="flex items-center gap-2.5">
            <i data-lucide="repeat" class="w-4 h-4 text-navy-600"></i>
            Switch Account
          </span>
          <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
        </button>
      </div>
    `, 'sm');
            }
            function openSwitchAccountModal() {
                const options = ROLES.map(r => `<option value="${r.key}">${r.label}</option>`).join('');

                openModal(`
          <h3 class="text-lg font-bold text-navy mb-1">Switch Account</h3>
          <p class="text-sm text-slate-500 mb-5">Select a role and enter its password to switch. You'll stay signed in.</p>
          <form id="switch-account-form" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Role</label>
              <select name="role_key" required
                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
                <option value="" disabled selected>Select a role…</option>
                ${options}
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
              <input type="password" name="password" required placeholder="Enter role password"
                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-navy">
              <p id="switch-account-error" class="hidden text-xs text-brand-red mt-1.5"></p>
            </div>
            <div class="flex justify-end gap-3 pt-2">
              <button type="button" onclick="AppUI.openAccountModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Back</button>
              <button type="submit" id="switch-account-submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Switch</button>
            </div>
          </form>
        `, 'sm');

                const form = document.getElementById('switch-account-form');
                const errorEl = document.getElementById('switch-account-error');
                const submitBtn = document.getElementById('switch-account-submit');

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    errorEl.classList.add('hidden');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Switching…';

                    const formData = new FormData(form);

                    fetch('{{ route("account.switch-role") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                        .then(async (res) => {
                            const data = await res.json();
                            if (!res.ok) throw data;
                            return data;
                        })
                        .then((data) => {

                            document.querySelectorAll('.account-role-label').forEach((el) => {
                                el.textContent = data.role_label;
                            });

                            document.querySelectorAll('.account-role-name').forEach((el) => {
                                el.textContent = data.role_label;
                            });

                            closeModal();
                            showToast(data.message || `Switched to ${data.role_label}.`, 'success');
                        })
                        .catch((err) => {
                            const message = err?.errors?.password?.[0]
                                || err?.message
                                || 'Unable to switch account. Please try again.';
                            errorEl.textContent = message;
                            errorEl.classList.remove('hidden');
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Switch';
                        });
                });
            }

            return {
                openMobileSidebar, closeMobileSidebar,
                openModal, closeModal, showToast,
                openExportSnapshotModal,
                openReportIssueModal,
                openAccountModal, openSwitchAccountModal,
            };
        })();
    </script>

    {{-- Account Payable submenu expand/collapse — kept separate from AppUI above
    so it can't interfere with the modal/toast/dropdown logic already there. --}}
    <script>
        document.querySelectorAll('[data-submenu-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const panel = btn.nextElementSibling;
                const chevron = btn.querySelector('[data-chevron]');
                if (panel) panel.classList.toggle('hidden');
                if (chevron) chevron.classList.toggle('rotate-180');
            });
        });
    </script>

    @stack('scripts')

</body>

</html>