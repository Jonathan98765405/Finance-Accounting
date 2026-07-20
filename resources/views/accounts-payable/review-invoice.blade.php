@extends('layouts.app')

@section('page-title', 'Review & Verify Invoice')
@section('page-title-heading', 'Review & Verify Invoice')
@section('page-subtitle', 'Review supplier invoices before approving them for Three-Way Matching.')

@section('content')
<div class="max-w-[1600px] mx-auto">

    {{-- Success Alert --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl bg-brand-green/10 border border-brand-green/20 text-brand-greenDark px-4 py-3.5 text-sm font-semibold shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('ap.review.verify', $invoice) }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <div class="lg:col-span-2 space-y-6">

                {{-- Invoice Information --}}
                <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6 sm:p-8">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-navy">Invoice Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Supplier Name</label>
                            <input type="text" 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 focus:outline-none" 
                                   value="{{ $invoice->supplier->name }}" 
                                   readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Invoice Number</label>
                            <input type="text" 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 focus:outline-none" 
                                   value="{{ $invoice->invoice_number }}" 
                                   readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Purchase Order No.</label>
                            <input type="text" 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 focus:outline-none" 
                                   value="{{ $invoice->purchaseOrder->po_number ?? 'N/A' }}" 
                                   readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Invoice Date</label>
                            <input type="text" 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 focus:outline-none" 
                                   value="{{ $invoice->invoice_date->format('F d, Y') }}" 
                                   readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Due Date</label>
                            <input type="text" 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 focus:outline-none" 
                                   value="{{ $invoice->due_date->format('F d, Y') }}" 
                                   readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Invoice Amount</label>
                            <input type="text" 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 focus:outline-none font-medium text-navy" 
                                   value="₱{{ number_format($invoice->total_amount, 2) }}" 
                                   readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Payment Terms</label>
                            <input type="text" 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-600 focus:outline-none" 
                                   value="{{ $invoice->payment_terms }}" 
                                   readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                            <span class="inline-flex items-center rounded-xl bg-brand-orange/10 px-4 py-2.5 text-sm font-bold text-brand-orange border border-brand-orange/20 w-full justify-center">
                                {{ ucwords(str_replace('_', ' ', $invoice->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Supporting Documents --}}
                <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6 sm:p-8">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-navy">Supporting Documents</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase text-[11px] tracking-wider">
                                    <th class="pb-3 pr-4">Document</th>
                                    <th class="pb-3 px-4">File Name</th>
                                    <th class="pb-3 px-4">Size</th>
                                    <th class="pb-3 px-4">Status</th>
                                    <th class="pb-3 pl-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-slate-600">
                                @forelse ($invoice->documents as $doc)
                                    <tr>
                                        <td class="py-3.5 pr-4 font-medium text-slate-800 flex items-center gap-2">
                                            <i data-lucide="file-text" class="w-4 h-4 text-brand-red"></i>
                                            {{ $doc->document_type }}
                                        </td>
                                        <td class="py-3.5 px-4 font-mono text-xs">{{ $doc->file_name }}</td>
                                        <td class="py-3.5 px-4">{{ $doc->file_size_human }}</td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center rounded-full bg-brand-green/10 px-2.5 py-1 text-xs font-bold text-brand-greenDark">
                                                {{ ucfirst($doc->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 pl-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> View
                                                </button>
                                                <button type="button" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                                                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Download
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-400 py-8">No supporting documents uploaded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Verification Checklist --}}
                <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6 sm:p-8">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-navy">Verification Checklist</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <label class="flex items-start gap-3 rounded-xl border border-slate-100 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <input type="checkbox" class="rounded text-brand-green focus:ring-brand-green w-4 h-4 mt-0.5 border-slate-300" checked>
                                <span class="text-sm font-medium text-slate-700">Supplier information verified.</span>
                            </label>

                            <label class="flex items-start gap-3 rounded-xl border border-slate-100 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <input type="checkbox" class="rounded text-brand-green focus:ring-brand-green w-4 h-4 mt-0.5 border-slate-300" checked>
                                <span class="text-sm font-medium text-slate-700">Invoice number is unique.</span>
                            </label>

                            <label class="flex items-start gap-3 rounded-xl border border-slate-100 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <input type="checkbox" class="rounded text-brand-green focus:ring-brand-green w-4 h-4 mt-0.5 border-slate-300" checked>
                                <span class="text-sm font-medium text-slate-700">Purchase Order exists.</span>
                            </label>
                        </div>

                        <div class="space-y-3">
                            <label class="flex items-start gap-3 rounded-xl border border-slate-100 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <input type="checkbox" class="rounded text-brand-green focus:ring-brand-green w-4 h-4 mt-0.5 border-slate-300">
                                <span class="text-sm font-medium text-slate-700">Invoice amount matches Purchase Order.</span>
                            </label>

                            <label class="flex items-start gap-3 rounded-xl border border-slate-100 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <input type="checkbox" class="rounded text-brand-green focus:ring-brand-green w-4 h-4 mt-0.5 border-slate-300">
                                <span class="text-sm font-medium text-slate-700">Supporting documents are complete.</span>
                            </label>

                            <label class="flex items-start gap-3 rounded-xl border border-slate-100 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <input type="checkbox" class="rounded text-brand-green focus:ring-brand-green w-4 h-4 mt-0.5 border-slate-300">
                                <span class="text-sm font-medium text-slate-700">Ready for Three-Way Match.</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Remarks --}}
                <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6 sm:p-8">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-navy">Verification Remarks</h3>
                    </div>

                    <textarea name="verification_remarks" 
                              rows="4" 
                              placeholder="Enter comments, discrepancies, or verification notes..." 
                              class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/20 text-slate-700 placeholder:text-slate-400">{{ old('verification_remarks', $invoice->verification_remarks) }}</textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3.5 pt-2">
                    <button type="submit"
                            formaction="{{ route('ap.review.reject', $invoice) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-brand-red text-brand-red hover:bg-brand-red/5 px-6 py-3 text-sm font-bold transition"
                            onclick="return confirm('Reject this invoice?');">
                        <i data-lucide="x-circle" class="w-4 h-4 shrink-0"></i>
                        Reject Invoice
                    </button>

                    <button type="submit" 
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-green hover:bg-brand-greenDark text-white px-6 py-3 text-sm font-bold shadow-sm transition">
                        <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                        Send to Three-Way Match
                    </button>
                </div>
            </div>

            <div class="space-y-6">

                {{-- Verification Status Header Widget --}}
                <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-brand-green/10 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="file-check-2" class="w-8 h-8 text-brand-greenDark"></i>
                    </div>
                    <h4 class="text-lg font-extrabold text-navy mb-1.5">Verification Pending</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Carefully review and audit all invoice information and supporting files before sending this record to the Three-Way Match process.
                    </p>
                </div>

                {{-- Recent Activity --}}
                <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-6">
                    <div class="border-b border-slate-100 pb-3 mb-4 flex items-center justify-between">
                        <h4 class="text-[15px] font-bold text-navy flex items-center gap-2">
                            <i data-lucide="history" class="w-4 h-4 text-slate-400"></i>
                            Recent Activity
                        </h4>
                    </div>

                    <div class="space-y-4">
                        @forelse ($invoice->activities()->latest()->get() as $activity)
                            <div class="flex items-start justify-between gap-3 text-xs {{ !$loop->last ? 'border-b border-slate-50 pb-3' : '' }}">
                                <div class="space-y-0.5">
                                    <p class="font-semibold text-slate-700 leading-snug">{{ $activity->description }}</p>
                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                        {{ ucwords(str_replace('_', ' ', $activity->type)) }}
                                    </p>
                                </div>
                                <span class="text-slate-400 text-right shrink-0 whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm text-center py-4">No activity recorded yet for this invoice.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection