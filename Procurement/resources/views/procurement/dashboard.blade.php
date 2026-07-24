@extends('layouts.app')

@section('page-title-heading', 'Procurement Overview')
@section('page-subtitle', 'Monitor pending requisitions, purchase orders, AP sync status, and vendor activity.')

@section('content')
<div class="space-y-8">

    {{-- ================= METRIC CARDS ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        {{-- Pending Requisitions --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-card flex items-center justify-between transition hover:shadow-md">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending Requisitions</span>
                <p class="text-3xl font-extrabold text-navy mt-1">12</p>
                <p class="text-xs text-amber-600 font-medium mt-1 flex items-center gap-1">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> Awaiting approval
                </p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i data-lucide="file-clock" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Approved POs --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-card flex items-center justify-between transition hover:shadow-md">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Approved POs</span>
                <p class="text-3xl font-extrabold text-navy mt-1">8</p>
                <p class="text-xs text-emerald-600 font-medium mt-1 flex items-center gap-1">
                    <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Ready for issuance
                </p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Ready for AP Sync --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-card flex items-center justify-between transition hover:shadow-md">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Ready for AP Sync</span>
                <p class="text-3xl font-extrabold text-navy mt-1">3</p>
                <p class="text-xs text-indigo-600 font-medium mt-1 flex items-center gap-1">
                    <i data-lucide="arrow-right-left" class="w-3.5 h-3.5"></i> Pending ledger export
                </p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <i data-lucide="refresh-cw" class="w-6 h-6"></i>
            </div>
        </div>

        {{-- Active Vendors --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-card flex items-center justify-between transition hover:shadow-md">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Vendors</span>
                <p class="text-3xl font-extrabold text-navy mt-1">24</p>
                <p class="text-xs text-slate-500 font-medium mt-1 flex items-center gap-1">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Verified suppliers
                </p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                <i data-lucide="building-2" class="w-6 h-6"></i>
            </div>
        </div>

    </div>

    {{-- ================= MAIN CONTENT GRID ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: RECENT RECORDS TABLE --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-navy">Recent Procurement Records</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Latest requisitions and purchase orders across departments</p>
                </div>
                <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-navy hover:text-brand-greenDark transition">
                    View All Orders <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-400 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4 rounded-l-xl">Ref Code</th>
                            <th class="py-3 px-4">Vendor / Department</th>
                            <th class="py-3 px-4">Total Amount</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right rounded-r-xl">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        
                        {{-- Row 1 --}}
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 font-bold text-navy">
                                PO-2026-089
                                <span class="block text-[11px] font-normal text-slate-400">Jul 23, 2026</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                Acme Supplies Ltd.
                                <span class="block text-[11px] font-normal text-slate-400">IT Infrastructure</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">$12,450.00</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending PR
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="#" class="p-2 text-slate-400 hover:text-navy inline-block rounded-lg hover:bg-slate-100">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 2 --}}
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 font-bold text-navy">
                                PO-2026-088
                                <span class="block text-[11px] font-normal text-slate-400">Jul 22, 2026</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                Global Logistics Corp
                                <span class="block text-[11px] font-normal text-slate-400">Operations</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">$4,800.00</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="#" class="p-2 text-slate-400 hover:text-navy inline-block rounded-lg hover:bg-slate-100">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 3 --}}
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 font-bold text-navy">
                                PR-2026-042
                                <span class="block text-[11px] font-normal text-slate-400">Jul 21, 2026</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                Office Needs Co.
                                <span class="block text-[11px] font-normal text-slate-400">Administration</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">$1,220.00</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Ready for AP
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="#" class="p-2 text-slate-400 hover:text-navy inline-block rounded-lg hover:bg-slate-100">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Row 4 --}}
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 font-bold text-navy">
                                PO-2026-085
                                <span class="block text-[11px] font-normal text-slate-400">Jul 20, 2026</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                Tech Solutions Inc.
                                <span class="block text-[11px] font-normal text-slate-400">Software & Licensing</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">$8,500.00</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="#" class="p-2 text-slate-400 hover:text-navy inline-block rounded-lg hover:bg-slate-100">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT COLUMN: SIDEBAR PANELS --}}
        <div class="space-y-6">

            {{-- Quick Actions Panel --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6">
                <h3 class="text-base font-bold text-navy mb-4">Quick Actions</h3>
                <div class="space-y-2.5">
                    
                    <a href="{{ route('requisitions.index') }}" 
                       class="flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-brand-green hover:bg-slate-50/80 transition group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-brand-green group-hover:text-white transition">
                                <i data-lucide="file-plus-2" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-navy">New Requisition</p>
                                <p class="text-[11px] text-slate-400">Request goods or services</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-navy transition"></i>
                    </a>

                    <a href="{{ route('purchase-orders.index') }}" 
                       class="flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-navy hover:bg-slate-50/80 transition group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-navy/5 text-navy group-hover:bg-navy group-hover:text-white transition">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-navy">Create Purchase Order</p>
                                <p class="text-[11px] text-slate-400">Issue order to vendor</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-navy transition"></i>
                    </a>

                    <a href="{{ route('vendors.index') }}" 
                       class="flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-amber-500 hover:bg-slate-50/80 transition group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition">
                                <i data-lucide="user-plus" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-navy">Register Vendor</p>
                                <p class="text-[11px] text-slate-400">Add a new supplier</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-navy transition"></i>
                    </a>

                </div>
            </div>

            {{-- Accounts Payable Sync Status Widget --}}
            <div class="bg-navy rounded-2xl p-6 text-white shadow-card relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] uppercase font-bold tracking-wider text-brand-green">Accounts Payable Integration</span>
                        <span class="flex h-2 w-2 rounded-full bg-brand-green"></span>
                    </div>
                    <h4 class="text-lg font-bold">3 Records Ready to Sync</h4>
                    <p class="text-xs text-slate-300 mt-1 mb-4 leading-relaxed">
                        Export approved purchase orders directly into the Accounts Payable system.
                    </p>
                    <button type="button" 
                            onclick="AppUI.showToast('Syncing records to AP...', 'info')"
                            class="w-full py-2.5 px-4 rounded-xl bg-brand-green hover:bg-brand-greenDark text-navy font-extrabold text-sm transition flex items-center justify-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Run AP Sync Now
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection