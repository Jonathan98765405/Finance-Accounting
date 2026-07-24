<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Procurement')</title>

    <style>
        html {
            scrollbar-gutter: stable;
        }
    </style>

    {{-- Tailwind CSS via CDN --}}
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

    {{-- Lucide icons --}}
    <script src="https://unpkg.com/lucide@1.21.0/dist/umd/lucide.js"></script>

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

        @media print {
            aside, .no-print {
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
        $navItems = [
            ['icon' => 'layout-dashboard', 'label' => 'Dashboard', 'route' => 'dashboard'],
            ['icon' => 'file-plus-2', 'label' => 'Requisitions', 'route' => 'requisitions.index'],
            ['icon' => 'shopping-cart', 'label' => 'Purchase Orders', 'route' => 'purchase-orders.index'],
            ['icon' => 'building-2', 'label' => 'Vendors', 'route' => 'vendors.index'],
        ];

        foreach ($navItems as &$item) {
            $item['href'] = $item['route'] && \Illuminate\Support\Facades\Route::has($item['route'])
                ? route($item['route'])
                : '#';

            if ($item['route'] && \Illuminate\Support\Facades\Route::has($item['route'])) {
                $routePrefix = explode('.', $item['route'])[0];
                $item['active'] = request()->routeIs($item['route']) || request()->routeIs($routePrefix . '.*');
            } else {
                $item['active'] = false;
            }
        }
        unset($item);
    @endphp

    <div class="flex min-h-screen">

        {{-- ============ SIDEBAR ============ --}}
        <aside class="fixed left-0 top-0 h-screen w-72 flex flex-col bg-navy text-white px-6 py-8 overflow-y-auto z-50">
            <div class="mb-10">
                <h1 class="text-2xl font-extrabold leading-tight">Procurement</h1>
            </div>

            <nav class="flex-1 space-y-1.5">
                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] }}"
                        class="nav-link flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition {{ !empty($item['active']) ? 'active text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 shrink-0"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="mt-8 flex items-center gap-3 rounded-2xl bg-brand-green/90 px-4 py-3 text-left">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-navy text-white">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="font-bold text-navy text-sm truncate uppercase">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-navy/80 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
        </aside>

        {{-- ============ MAIN CONTENT ============ --}}
        <main class="flex-1 ml-72 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 max-w-[1600px] mx-auto w-full">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-navy">
                        @yield('page-title-heading', 'Dashboard')
                    </h2>
                    <p class="text-slate-500 mt-1 text-sm sm:text-base">
                        @yield('page-subtitle', 'Manage your purchase orders, requisitions, and vendors.')
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm border border-slate-200 hover:bg-red-50 transition">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3.5 text-sm font-semibold shadow-sm mb-6">
                    <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-center gap-3 rounded-xl bg-red-50 border border-red-200 text-brand-red px-4 py-3.5 text-sm font-semibold shadow-sm mb-6">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')

        </main>
    </div>

    {{-- ============ MODAL ============ --}}
    <div id="app-modal-backdrop" onclick="if(event.target === this) AppUI.closeModal()">
        <div id="app-modal-box" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-5 sm:p-6 relative transition-all">
            <button type="button" onclick="AppUI.closeModal()"
                class="absolute top-4 right-4 flex h-8 w-8 items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 z-10">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div id="app-modal-content"></div>
        </div>
    </div>

    {{-- ============ TOASTS ============ --}}
    <div id="app-toast-stack"></div>

    <script>
        lucide.createIcons();

        const AppUI = (function () {
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
                if (e.key === 'Escape') { closeModal(); }
            });

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

            return { openModal, closeModal, showToast };
        })();
    </script>

    @stack('scripts')

</body>

</html>