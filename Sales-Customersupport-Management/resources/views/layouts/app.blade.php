<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') · Sales & Customer Support</title>

    {{-- Front-end only build: Tailwind via CDN so the UI runs with zero build step.
         Swap this for the Vite/Tailwind pipeline (npm run build) once a real
         backend is wired up. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            900: '#12172b',
                        },
                        brand: {
                            500: '#3b6ef6',
                            600: '#2f5ce0',
                        },
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased">

    @php
        // Front-end only: no auth/permissions logic, just a static nav list.
        // Active state is derived from the current route name.
        $navItems = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'grid'],
            ['label' => 'Customers', 'route' => 'customers.index', 'icon' => 'users'],
            ['label' => 'Sales Invoices', 'route' => 'sales-invoices.index', 'icon' => 'file'],
            ['label' => 'API', 'route' => null, 'icon' => 'code', 'hidden' => true],
        ];
    @endphp

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-navy-900 text-slate-300">

            <div class="flex items-center gap-3 px-6 py-6 border-b border-white/10">
                <div class="w-9 h-9 rounded-lg bg-brand-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l-1 10H6L5 11z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-sm font-semibold leading-tight truncate">Sales &amp; Customer</p>
                    <p class="text-xs text-slate-400 leading-tight truncate">Support Management</p>
                </div>
            </div>

            <nav class="flex-1 px-3 py-6 space-y-1">
                @foreach ($navItems as $item)
                    @php
                        $isHidden = $item['hidden'] ?? false;
                        $routePrefix = $item['route'] ? \Illuminate\Support\Str::before($item['route'], '.index') : null;
                        $isActive = $item['route'] && Route::has($item['route']) && ($routePrefix ? request()->routeIs("{$routePrefix}.*") : request()->routeIs($item['route']));
                        $href = ($item['route'] && Route::has($item['route'])) ? route($item['route']) : '#';
                    @endphp
                    <a href="{{ $href }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                              {{ $isActive ? 'bg-brand-500 text-white shadow-sm' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}
                              {{ $isHidden ? 'opacity-50 cursor-not-allowed' : '' }}"
                       @if ($isHidden) aria-disabled="true" onclick="return false;" @endif>

                        <span class="w-5 h-5 flex items-center justify-center shrink-0">
                            @switch($item['icon'])
                                @case('grid')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h7v7H4V4zm9 0h7v7h-7V4zM4 13h7v7H4v-7zm9 0h7v7h-7v-7z" />
                                    </svg>
                                    @break
                                @case('users')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-2a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-4-4H7a4 4 0 00-4 4v2h14v-2z" />
                                    </svg>
                                    @break
                                @case('file')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    @break
                                @case('code')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16M6 8l-4 4 4 4m12-8l4 4-4 4" />
                                    </svg>
                                    @break
                            @endswitch
                        </span>

                        <span class="truncate">{{ $item['label'] }}</span>

                        @if ($isHidden)
                            <span class="ml-auto text-[10px] uppercase tracking-wide bg-white/10 text-slate-400 px-1.5 py-0.5 rounded">Hidden</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-white/5 cursor-pointer">
                    <div class="w-9 h-9 rounded-full bg-brand-500 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                        {{ collect(explode(' ', $user['name'] ?? 'Juan Dela Cruz'))->map(fn($n) => $n[0])->take(2)->implode('') }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-white truncate">{{ $user['name'] ?? 'Juan Dela Cruz' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $user['role'] ?? 'Administrator' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                </div>
            </div>
        </aside>

        {{-- Main column --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Topbar --}}
            <header class="flex items-center justify-between gap-4 px-6 lg:px-8 py-4 bg-white border-b border-slate-200">
                <button class="lg:hidden text-slate-500" aria-label="Open menu">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center gap-3 ml-auto">
                    <button class="hidden sm:flex items-center gap-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg px-3 py-2 hover:bg-slate-50 transition">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $dateRange ?? 'This week' }}
                    </button>

                    <button class="relative w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-100 transition" aria-label="Notifications">
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <main class="flex-1 p-6 lg:p-8">
                @if (session('status'))
                    <div class="mb-5 flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 text-sm font-medium px-4 py-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
 @stack('scripts')
</body>
</html>