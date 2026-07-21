// Peradilla 3:34
@extends('layouts.app')
@section('page-title', 'Finance & Accounting | Accounts Payable')
@section('page-title-heading', 'Accounts Payable')
@section('page-subtitle', 'Manage vendor invoices, ensure timely payments, and maintain strong supplier relationships.')

@section('content')

<div class="space-y-6">

    <!-- ================= KPI CARDS ================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

        <!-- TOTAL AP -->
        <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-navy-50 text-navy-800">
                <i data-lucide="file-text" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total AP</p>
                <p class="text-2xl font-extrabold text-navy mt-0.5">₱{{ number_format($totalAP, 2) }}</p>
                <p class="text-xs text-slate-400 mt-0.5">All open invoices</p>
            </div>
        </div>

        <!-- DUE THIS MONTH -->
        <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-navy-50 text-brand-blue">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Due This Month</p>
                <p class="text-2xl font-extrabold text-navy mt-0.5">₱{{ number_format($dueThisMonth, 2) }}</p>
                <p class="text-xs text-slate-400 mt-0.5">Invoices due this month</p>
            </div>
        </div>

        <!-- OVERDUE -->
        <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-red-50 text-brand-red">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Overdue</p>
                <p class="text-2xl font-extrabold text-brand-red mt-0.5">₱{{ number_format($overdue, 2) }}</p>
                <p class="text-xs text-slate-400 mt-0.5">Past due invoices</p>
            </div>
        </div>

        <!-- ON-TIME PAYMENT RATE -->
        <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-brand-green">
                <i data-lucide="pie-chart" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">On-time Rate</p>
                <p class="text-2xl font-extrabold text-navy mt-0.5">{{ $onTimeRate }}%</p>
                <p class="text-xs text-slate-400 mt-0.5">This month</p>
            </div>
        </div>

    </div>

    <!-- ================= MAIN CONTENT ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ================= PROCESS FLOW ================= -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-card p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-1 h-4 rounded-full bg-brand-green"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-green">Process Flow</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-6 relative">
                    <!-- Step 1 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-navy-600 text-white mb-3">
                            <i data-lucide="file-input" class="w-5 h-5"></i>
                        </div>
                        <p class="text-xs font-bold text-navy mb-1">Invoice Received</p>
                        <p class="text-[11px] text-slate-400 leading-normal">Supplier invoice is recorded</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-navy-600 text-white mb-3">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </div>
                        <p class="text-xs font-bold text-navy mb-1">Review &amp; Verify</p>
                        <p class="text-[11px] text-slate-400 leading-normal">Validate invoice and matching files</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-navy-600 text-white mb-3">
                            <i data-lucide="git-compare" class="w-5 h-5"></i>
                        </div>
                        <p class="text-xs font-bold text-navy mb-1">3-Way Match</p>
                        <p class="text-[11px] text-slate-400 leading-normal">Match PO, goods receipt, and invoice</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-navy-600 text-white mb-3">
                            <i data-lucide="calendar-range" class="w-5 h-5"></i>
                        </div>
                        <p class="text-xs font-bold text-navy mb-1">Schedule</p>
                        <p class="text-[11px] text-slate-400 leading-normal">Set payment date and method</p>
                    </div>

                    <!-- Step 5 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-navy-600 text-white mb-3">
                            <i data-lucide="send" class="w-5 h-5"></i>
                        </div>
                        <p class="text-xs font-bold text-navy mb-1">Payment Sent</p>
                        <p class="text-[11px] text-slate-400 leading-normal">Process payout to vendor</p>
                    </div>

                    <!-- Step 6 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-navy-600 text-white mb-3">
                            <i data-lucide="mail-check" class="w-5 h-5"></i>
                        </div>
                        <p class="text-xs font-bold text-navy mb-1">Remittance</p>
                        <p class="text-[11px] text-slate-400 leading-normal">Send confirmation advice to suppliers</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= AP AGING SUMMARY ================= -->
        <div class="bg-white rounded-2xl shadow-card p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-4 rounded-full bg-brand-green"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-green">AP Aging Summary</span>
            </div>

            <div class="space-y-3.5">
                <div class="flex justify-between items-center py-1">
                    <span class="flex items-center gap-2.5 text-sm text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-green"></span>0 - 30 Days
                    </span>
                    <strong class="text-sm text-navy">₱{{ number_format($aging['0_30'], 2) }}</strong>
                </div>

                <div class="flex justify-between items-center py-1">
                    <span class="flex items-center gap-2.5 text-sm text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>31 - 60 Days
                    </span>
                    <strong class="text-sm text-navy">₱{{ number_format($aging['31_60'], 2) }}</strong>
                </div>

                <div class="flex justify-between items-center py-1">
                    <span class="flex items-center gap-2.5 text-sm text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-orange"></span>61 - 90 Days
                    </span>
                    <strong class="text-sm text-navy">₱{{ number_format($aging['61_90'], 2) }}</strong>
                </div>

                <div class="flex justify-between items-center py-1">
                    <span class="flex items-center gap-2.5 text-sm text-slate-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-red"></span>90+ Days
                    </span>
                    <strong class="text-sm text-navy">₱{{ number_format($aging['90_plus'], 2) }}</strong>
                </div>

                <hr class="border-slate-100 my-2">

                <div class="flex justify-between items-center pt-1">
                    <span class="font-bold text-navy">Total</span>
                    <span class="font-bold text-lg text-navy">₱{{ number_format(array_sum($aging), 2) }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ================= THIRD ROW ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ================= TOP VENDORS ================= -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-card p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-4 rounded-full bg-brand-green"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-green">Top Vendors</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-400">
                            <th class="pb-3 font-semibold">Vendor</th>
                            <th class="pb-3 font-semibold text-right">Total Due</th>
                            <th class="pb-3 font-semibold text-right">Due This Month</th>
                            <th class="pb-3 font-semibold text-right">Overdue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($topVendors->take(4) as $vendor)
                            <tr>
                                <td class="py-3.5 font-medium">{{ $vendor->name }}</td>
                                <td class="py-3.5 text-right font-semibold text-navy">₱{{ number_format($vendor->total_due ?? 0, 2) }}</td>
                                <td class="py-3.5 text-right">₱{{ number_format($vendor->due_this_month ?? 0, 2) }}</td>
                                <td class="py-3.5 text-right {{ ($vendor->overdue ?? 0) > 0 ? 'text-brand-red font-semibold' : '' }}">
                                    ₱{{ number_format($vendor->overdue ?? 0, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 py-8">No vendor activity yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <button type="button" 
                    onclick="openVendorsModal()"
                    class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-brand-green hover:text-brand-greenDark transition">
                View all Vendors <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- ================= RECENT ACTIVITIES ================= -->
        <div class="bg-white rounded-2xl shadow-card p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-4 rounded-full bg-brand-green"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-green">Recent Activities</span>
            </div>

            <div class="space-y-4 max-h-[320px] overflow-y-auto pr-1">
                @php
                    $activityIcons = [
                        'invoice_received' => 'file-text',
                        'invoice_verified' => 'shield-check',
                        'invoice_rejected' => 'circle-x',
                        'three_way_match' => 'git-compare',
                        'payment_scheduled' => 'calendar-days',
                        'payment_approved' => 'check-circle-2',
                        'payment_completed' => 'check',
                    ];
                @endphp

                @forelse ($recentActivities as $activity)
                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-navy-50 text-navy-800">
                            <i data-lucide="{{ $activityIcons[$activity->type] ?? 'info' }}" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-slate-700 font-medium leading-normal break-words">{{ $activity->description }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 py-4 text-center">No recent activity.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    /**
     * Renders all vendors inside the modal backdrop built into app.blade.php
     */
    function openVendorsModal() {
        const vendorRows = [
            @foreach ($topVendors as $vendor)
            `
            <tr class="border-b border-slate-100 last:border-0 text-slate-700">
                <td class="py-3 font-medium">${@json($vendor->name)}</td>
                <td class="py-3 text-right font-semibold text-navy">₱${Number(@json($vendor->total_due ?? 0)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td class="py-3 text-right">₱${Number(@json($vendor->due_this_month ?? 0)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                <td class="py-3 text-right ${Number(@json($vendor->overdue ?? 0)) > 0 ? 'text-brand-red font-semibold' : ''}">₱${Number(@json($vendor->overdue ?? 0)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
            </tr>
            `,
            @endforeach
        ].join('');

        const modalHtml = `
            <div class="p-1">
                <h3 class="text-lg font-bold text-navy mb-4">Top Vendors</h3>
                <div class="max-h-96 overflow-y-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-[11px] uppercase tracking-wider text-slate-400 sticky top-0 bg-white">
                                <th class="pb-3 font-semibold">Vendor</th>
                                <th class="pb-3 font-semibold text-right">Total Due</th>
                                <th class="pb-3 font-semibold text-right">Due This Month</th>
                                <th class="pb-3 font-semibold text-right">Overdue</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${vendorRows || '<tr><td colspan="4" class="text-center py-6 text-slate-400">No vendor records found.</td></tr>'}
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100 mt-4">
                    <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition">
                        Close
                    </button>
                </div>
            </div>
        `;

        AppUI.openModal(modalHtml, 'lg');
    }
</script>
@endpush

@endsection