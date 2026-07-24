@extends('layouts.app')

@section('title', 'Fixed Assets')
@section('active', 'Fixed Assets')

@section('page-title','Fixed Assets')
@section('page-title-heading','Fixed Assets')
@section('page-subtitle','Manage company fixed assets.')

@section('content')
    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold" style="color:#173A66;">  </h1>
            <p class="text-gray-500 mt-1">  </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ url('/fixed-assets/register') }}"
               class="px-5 py-2 rounded-md text-white text-sm font-semibold shadow" style="background:#22B57A;">
                Registration
            </a>
            <a href="{{ url('/fixed-assets/assignment') }}"
               class="px-5 py-2 rounded-md text-white text-sm font-semibold shadow" style="background:#173A66;">
                Assignment
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    @php
        // Maps common Font Awesome icon names (which the controller may still be
        // sending) to valid Lucide icon names. Falls back to matching by label,
        // then to a generic icon, so a card is never left blank.
        $faToLucide = [
            'fa-boxes-stacked' => 'box', 'fa-box' => 'box', 'fa-cubes' => 'box',
            'fa-dollar-sign' => 'dollar-sign', 'fa-sack-dollar' => 'dollar-sign', 'fa-money-bill' => 'dollar-sign',
            'fa-chart-line' => 'trending-up', 'fa-arrow-trend-down' => 'trending-down',
            'fa-screwdriver-wrench' => 'wrench', 'fa-wrench' => 'wrench', 'fa-tools' => 'wrench',
            'fa-circle-info' => 'info',
        ];

        $labelToLucide = [
            'total assets' => 'box',
            'total assets value' => 'dollar-sign',
            'accumulated depreciation' => 'trending-up',
            'under maintenance' => 'wrench',
        ];

        $resolveIcon = function ($stat) use ($faToLucide, $labelToLucide) {
            $raw = trim($stat['icon'] ?? '');
            $raw = preg_replace('/^fa-solid\s+/', '', $raw); // strip "fa-solid " prefix if present

            if (isset($faToLucide[$raw])) {
                return $faToLucide[$raw];
            }
            // If it isn't a known FA name, assume it's already a valid Lucide name.
            if ($raw !== '' && !str_starts_with($raw, 'fa-')) {
                return $raw;
            }
            return $labelToLucide[strtolower(trim($stat['label'] ?? ''))] ?? 'info';
        };
    @endphp
    <div class="grid grid-cols-4 gap-5 mb-5">
        @foreach ($stats as $stat)
            <div class="bg-white rounded-lg border border-gray-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white shrink-0"
                     style="background: {{ $stat['color'] ?? '#22B57A' }};">
                    @if (strtolower(trim($stat['label'] ?? '')) === 'total assets value')
                        <span class="text-lg font-bold leading-none">&#8369;</span>
                    @else
                        <i data-lucide="{{ $resolveIcon($stat) }}" class="w-5 h-5"></i>
                    @endif
                </div>
                <div>
                    <div class="text-gray-500 text-sm">{{ $stat['label'] }}</div>
                    <div class="text-xl font-bold" style="color:#173A66;">{{ $stat['value'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Charts + Activities --}}
    <div class="grid grid-cols-3 gap-5 mb-5">

        {{-- Asset Category Overview --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <span class="panel-badge inline-block text-black text-xs font-semibold px-3 py-1.5 rounded-md mb-3">Asset Category Overview</span>
            <div class="flex items-center gap-3">
                <div class="shrink-0">
                    <canvas id="categoryChart" width="100" height="100"></canvas>
                </div>
                @php
                    $categoryColors = ['#3B82F6', '#8B5CF6', '#F5A623', '#22B57A', '#EF4444', '#A855F7', '#14B8A6'];
                @endphp
                <ul class="text-xs text-gray-600 space-y-1.5 flex-1 min-w-0">
                    @forelse ($categoryBreakdown as $i => $cat)
                        <li class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $categoryColors[$i % count($categoryColors)] }};"></span>
                            <span class="flex-1 whitespace-nowrap">{{ $cat['label'] }}</span>
                            <span class="text-gray-400 shrink-0">{{ $cat['percent'] }}%</span>
                        </li>
                    @empty
                        <li class="text-gray-400">No assets yet</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Asset Status Overview --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <span class="panel-badge inline-block text-black text-xs font-semibold px-3 py-1.5 rounded-md mb-3">Asset Status Overview</span>
            <div class="flex items-center gap-3">
                <div class="shrink-0">
                    <canvas id="statusChart" width="100" height="100"></canvas>
                </div>
                @php
                    $statusColorsMap = [
                        'Active' => '#22B57A',
                        'Disposed' => '#EF4444',
                        'Under Maintenance' => '#F5A623',
                        'Fully Depreciated' => '#6B7280',
                    ];
                @endphp
                <ul class="text-xs text-gray-600 space-y-1.5 flex-1 min-w-0">
                    @forelse ($statusBreakdown as $s)
                        <li class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $statusColorsMap[$s['label']] ?? '#9CA3AF' }};"></span>
                            <span class="flex-1 whitespace-nowrap">{{ $s['label'] }}</span>
                            <span class="text-gray-400 shrink-0">{{ $s['percent'] }}%</span>
                        </li>
                    @empty
                        <li class="text-gray-400">No assets yet</li>
                    @endforelse
                </ul>
            </div>
        </div>

       {{-- Recent Activities --}}
   <div class="bg-white rounded-lg border border-gray-200 p-4">
       <span class="panel-badge inline-block text-black text-xs font-semibold px-3 py-1.5 rounded-md mb-3">Recent Activities</span>
       <ul class="space-y-3 text-xs">
           @forelse ($recentActivities as $a)
               <li class="flex items-start gap-2.5">
                   <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5" style="background: {{ $a['color'] }};">
                       <i data-lucide="{{ $a['icon'] }}" class="text-white" style="width:9px;height:9px;"></i>
                   </span>
                   <div class="flex-1 flex items-center justify-between gap-2">
                       <span class="text-gray-700">{{ $a['text'] }}</span>
                       <span class="text-gray-400 whitespace-nowrap">{{ $a['time'] }}</span>
                   </div>
               </li>
           @empty
               <li class="text-gray-400">No recent activity yet.</li>
           @endforelse
       </ul>
   </div>
    </div>  

    {{-- Recent Assets Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <form method="GET" action="{{ url('/fixed-assets') }}" class="p-4 flex items-center justify-between gap-3">
            <span class="panel-badge inline-block text-grey text-xs font-semibold px-3 py-1.5 rounded-md">Recent Assets</span>
            <div class="relative w-72">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="width:14px;height:14px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search assets..."
                       class="w-full pl-9 pr-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>
            <div class="flex gap-3">
                <select name="category" onchange="this.form.submit()" class="border border-gray-200 rounded-md text-sm px-3 py-2">
                    <option value="">All Categories</option>
                    @foreach (['IT Equipment', 'Furniture & Fixtures', 'Vehicles', 'Machinery & Equipment', 'Others'] as $catOption)
                        <option value="{{ $catOption }}" {{ request('category') === $catOption ? 'selected' : '' }}>{{ $catOption }}</option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-md text-sm px-3 py-2">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="under_maintenance" {{ request('status') === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="disposed" {{ request('status') === 'disposed' ? 'selected' : '' }}>Disposed</option>
                    <option value="fully_depreciated" {{ request('status') === 'fully_depreciated' ? 'selected' : '' }}>Fully Depreciated</option>
                </select>
                <button type="submit" class="px-4 py-2 rounded-md text-white text-sm font-medium" style="background:#173A66;">Filter</button>
            </div>
        </form>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600" style="background:#EEF0FA;">
                    <th class="px-4 py-3 font-medium">Asset ID</th>
                    <th class="px-4 py-3 font-medium">Asset Name</th>
                    <th class="px-4 py-3 font-medium">Category</th>
                    <th class="px-4 py-3 font-medium">Location</th>
                    <th class="px-4 py-3 font-medium">Purchase Date</th>
                    <th class="px-4 py-3 font-medium">Cost</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusColors = [
                        'Active' => 'background:#D6F5DF;color:#16A34A;',
                        'Under Maintenance' => 'background:#F5A623;color:#FFFFFF;',
                        'Disposed' => 'background:#FEE2E2;color:#DC2626;',
                        'Fully Depreciated' => 'background:#E5E7EB;color:#374151;',
                    ];
                @endphp
                @forelse ($assets as $asset)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium" style="color:#173A66;">{{ $asset['id'] }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ url('/fixed-assets/assignment/' . $asset['asset_id']) }}" class="hover:underline" style="color:#173A66;">
                                {{ $asset['name'] }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $asset['category'] }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $asset['location'] }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $asset['date'] }}</td>
                        <td class="px-4 py-3 text-green-600 font-medium">{{ $asset['cost'] }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap" style="{{ $statusColors[$asset['status']] ?? 'background:#F3F4F6;color:#374151;' }}">
                                {{ $asset['status'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-400">No assets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="flex items-center justify-between px-4 py-3 text-xs text-gray-400">
            <span>Showing {{ $assets->firstItem() ?? 0 }} to {{ $assets->lastItem() ?? 0 }} of {{ $assets->total() }} entries</span>
            <div class="flex gap-1">
                {{ $assets->links() }}
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryBreakdown->pluck('label')) !!},
                datasets: [{
                    data: {!! json_encode($categoryBreakdown->pluck('percent')) !!},
                    backgroundColor: ['#3B82F6', '#8B5CF6', '#F5A623', '#22B57A', '#EF4444', '#A855F7', '#14B8A6'],
                    borderWidth: 0
                }]
            },
            options: { plugins: { legend: { display: false } }, cutout: '65%' }
        });

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($statusBreakdown->pluck('label')) !!},
                datasets: [{
                    data: {!! json_encode($statusBreakdown->pluck('percent')) !!},
                    backgroundColor: ['#22B57A', '#EF4444', '#F5A623', '#6B7280'],
                    borderWidth: 0
                }]
            },
            options: { plugins: { legend: { display: false } }, cutout: '65%' }
        });

        // Re-render Lucide icons injected by this page (stat card icons, activity icons, search icon)
        if (window.lucide) {
            lucide.createIcons();
        }
    </script>
@endpush