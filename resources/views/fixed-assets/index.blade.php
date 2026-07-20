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
                    <i data-lucide="{{ $resolveIcon($stat) }}" class="w-5 h-5"></i>
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
            <span class="panel-badge inline-block text-white text-xs font-semibold px-3 py-1.5 rounded-md mb-3">Asset Category Overview</span>
            <div class="flex items-center gap-3">
                <div class="shrink-0">
                    <canvas id="categoryChart" width="100" height="100"></canvas>
                </div>
                <ul class="text-xs text-gray-600 space-y-1.5 flex-1 min-w-0">
                    <li class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#3B82F6;"></span><span class="flex-1 whitespace-nowrap">IT Equipment</span><span class="text-gray-400 shrink-0">40%</span></li>
                    <li class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#8B5CF6;"></span><span class="flex-1 whitespace-nowrap">Furniture &amp; Fixtures</span><span class="text-gray-400 shrink-0">25%</span></li>
                    <li class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#F5A623;"></span><span class="flex-1 whitespace-nowrap">Vehicles</span><span class="text-gray-400 shrink-0">20%</span></li>
                    <li class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#22B57A;"></span><span class="flex-1 whitespace-nowrap">Machinery &amp; Equipment</span><span class="text-gray-400 shrink-0">10%</span></li>
                    <li class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#EF4444;"></span><span class="flex-1 whitespace-nowrap">Others</span><span class="text-gray-400 shrink-0">5%</span></li>
                </ul>
            </div>
        </div>

        {{-- Asset Status Overview --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <span class="panel-badge inline-block text-white text-xs font-semibold px-3 py-1.5 rounded-md mb-3">Asset Status Overview</span>
            <div class="flex items-center gap-3">
                <div class="shrink-0">
                    <canvas id="statusChart" width="100" height="100"></canvas>
                </div>
                <ul class="text-xs text-gray-600 space-y-1.5 flex-1 min-w-0">
                    <li class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#22B57A;"></span><span class="flex-1 whitespace-nowrap">Active</span><span class="text-gray-400 shrink-0">60%</span></li>
                    <li class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#3B82F6;"></span><span class="flex-1 whitespace-nowrap">In use</span><span class="text-gray-400 shrink-0">20%</span></li>
                    <li class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#F5A623;"></span><span class="flex-1 whitespace-nowrap">Under Maintenance</span><span class="text-gray-400 shrink-0">15%</span></li>
                    <li class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#EF4444;"></span><span class="flex-1 whitespace-nowrap">Disposed</span><span class="text-gray-400 shrink-0">5%</span></li>
                </ul>
            </div>
        </div>

        {{-- Recent Activities --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <span class="panel-badge inline-block text-white text-xs font-semibold px-3 py-1.5 rounded-md mb-3">Recent Activities</span>
            <ul class="space-y-3 text-xs">
                @php
                    $activities = [
                        ['icon' => 'plus', 'color' => '#1F2937', 'text' => 'New asset Dell Laptop added', 'time' => '2h ago'],
                        ['icon' => 'wrench', 'color' => '#F5A623', 'text' => 'Maintenance scheduled for Office Printer', 'time' => '4h ago'],
                        ['icon' => 'truck', 'color' => '#EF4444', 'text' => 'Asset Toyota Hiace marked as in use', 'time' => '1d ago'],
                        ['icon' => 'trending-up', 'color' => '#22B57A', 'text' => 'Depreciation posted for May 2025', 'time' => '2d ago'],
                        ['icon' => 'archive', 'color' => '#22B57A', 'text' => 'Asset Office Chair disposed', 'time' => '3d ago'],
                    ];
                @endphp
                @foreach ($activities as $a)
                    <li class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5" style="background: {{ $a['color'] }};">
                            <i data-lucide="{{ $a['icon'] }}" class="text-white" style="width:9px;height:9px;"></i>
                        </span>
                        <div class="flex-1 flex items-center justify-between gap-2">
                            <span class="text-gray-700">{{ $a['text'] }}</span>
                            <span class="text-gray-400 whitespace-nowrap">{{ $a['time'] }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Recent Assets Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="p-4 flex items-center justify-between gap-3">
            <span class="panel-badge inline-block text-white text-xs font-semibold px-3 py-1.5 rounded-md">Recent Assets</span>
            <div class="relative w-72">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="width:14px;height:14px;"></i>
                <input type="text" placeholder="Search assets..."
                       class="w-full pl-9 pr-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>
            <div class="flex gap-3">
                <select class="border border-gray-200 rounded-md text-sm px-3 py-2">
                    <option>All Categories</option>
                    <option>IT Equipment</option>
                    <option>Furniture &amp; Fixtures</option>
                    <option>Vehicles</option>
                    <option>Machinery &amp; Equipment</option>
                    <option>Others</option>
                </select>
                <select class="border border-gray-200 rounded-md text-sm px-3 py-2">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>In use</option>
                    <option>Under Maintenance</option>
                    <option>Disposed</option>
                </select>
            </div>
        </div>

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
                        'In use' => 'background:#173A66;color:#FFFFFF;',
                        'Under Maintenance' => 'background:#F5A623;color:#FFFFFF;',
                        'Disposed' => 'background:#FEE2E2;color:#DC2626;',
                    ];
                @endphp
                @foreach ($assets as $asset)
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
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap" style="{{ $statusColors[$asset['status']] }}">
                                {{ $asset['status'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex items-center justify-between px-4 py-3 text-xs text-gray-400">
            <span>Showing 1 to 5 of 125 entries</span>
            <div class="flex gap-1">
                <button class="w-7 h-7 rounded-md text-white text-xs" style="background:#173A66;">1</button>
                <button class="w-7 h-7 rounded-md border border-gray-200 text-xs">2</button>
                <button class="w-7 h-7 rounded-md border border-gray-200 text-xs">3</button>
                <span class="px-1">...</span>
                <button class="w-7 h-7 rounded-md border border-gray-200 text-xs">25</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: ['IT Equipment', 'Furniture & Fixtures', 'Vehicles', 'Machinery & Equipment', 'Others'],
                datasets: [{
                    data: [40, 25, 20, 10, 5],
                    backgroundColor: ['#3B82F6', '#8B5CF6', '#F5A623', '#22B57A', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: { plugins: { legend: { display: false } }, cutout: '65%' }
        });

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Active', 'In use', 'Under Maintenance', 'Disposed'],
                datasets: [{
                    data: [60, 20, 15, 5],
                    backgroundColor: ['#22B57A', '#3B82F6', '#F5A623', '#EF4444'],
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