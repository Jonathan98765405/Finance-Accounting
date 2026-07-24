@extends('layouts.app')

@section('page-title-heading', 'Vendor Directory')
@section('page-subtitle', 'Manage suppliers and payment terms.')

@section('content')
<div class="space-y-6">

    <!-- Create Vendor Form Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6">
        <div class="mb-5">
            <h3 class="text-lg font-bold text-navy">Add Vendor</h3>
            <p class="text-xs text-slate-500 mt-0.5">Enter supplier details to register a new vendor in the system</p>
        </div>

        <form action="{{ route('vendors.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Vendor Name</label>
                <input type="text" name="name" placeholder="Vendor Name" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-navy focus:ring-1 focus:ring-navy outline-none transition" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Vendor Code</label>
                <input type="text" name="code" placeholder="Vendor Code (e.g. VEND-001)" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-navy focus:ring-1 focus:ring-navy outline-none transition" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Payment Terms</label>
                <select name="payment_terms" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:bg-white focus:border-navy focus:ring-1 focus:ring-navy outline-none transition">
                    <option value="NET30">NET30</option>
                    <option value="NET60">NET60</option>
                    <option value="Due on Receipt">Due on Receipt</option>
                </select>
            </div>

            <div>
                <button type="submit" 
                        class="w-full inline-flex items-center justify-center gap-2 bg-navy hover:bg-navy-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Save Vendor</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Vendors List Table Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-navy">All Vendors</h3>
            <p class="text-xs text-slate-500 mt-0.5">Directory of registered suppliers and payment term configurations</p>
        </div>

        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-400 uppercase text-xs font-semibold border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3.5">Code</th>
                    <th class="px-6 py-3.5">Name</th>
                    <th class="px-6 py-3.5">Payment Terms</th>
                    <th class="px-6 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($vendors as $vendor)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-6 py-4 font-mono font-bold text-navy">
                            {{ $vendor->code }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $vendor->name }}
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-600">
                            {{ $vendor->payment_terms }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $status = strtolower($vendor->status ?? 'active');
                                $isInactive = $status === 'inactive';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $isInactive ? 'bg-slate-100 text-slate-600 border-slate-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isInactive ? 'bg-slate-400' : 'bg-emerald-500' }}"></span>
                                {{ ucfirst($vendor->status ?? 'Active') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="users" class="w-10 h-10 stroke-1 mb-2 text-slate-300"></i>
                                <p class="font-medium text-slate-500 text-sm">No vendors added yet.</p>
                                <p class="text-xs text-slate-400 mt-0.5">Use the form above to register your first supplier.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection