@extends('layouts.app')

@section('page-title', 'Three-Way Match')
@section('page-title-heading', 'Three-Way Match')
@section('page-subtitle', 'Match the vendor invoice with the related Purchase Order (PO) and Goods Receipt (GRN) to ensure accuracy approval.')

@section('content')
<div class="max-w-[1600px] mx-auto space-y-6">

    {{-- Session Alerts --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl bg-brand-green/10 border border-brand-green/20 text-brand-greenDark px-4 py-3.5 text-sm font-semibold shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('warning'))
        <div class="flex items-center gap-3 rounded-xl bg-brand-orange/10 border border-brand-orange/20 text-brand-orange px-4 py-3.5 text-sm font-semibold shadow-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    {{-- Subheader / Quick Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-2xl border border-slate-100 p-4 shadow-card">
        <a href="{{ route('ap.match.pending') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-navy hover:text-navy-600 transition">
            <i data-lucide="list-checks" class="w-4 h-4"></i>
            View all pending matches
        </a>

        <div class="flex items-start gap-3 bg-brand-green/10 border border-brand-green/15 rounded-xl p-3 max-w-md">
            <i data-lucide="info" class="w-5 h-5 text-brand-greenDark shrink-0 mt-0.5"></i>
            <p class="text-xs text-slate-600 leading-relaxed">
                Three-way match ensures that the invoice, purchase order, and goods receipt match in amount, quantity, and items before payment is approved.
            </p>
        </div>
    </div>

    {{-- Invoice Header Card --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
        <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4">
            <h3 class="text-sm font-bold text-navy uppercase tracking-wider">Invoice to Match</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-center md:text-left">
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Invoice Number</span>
                    <span class="text-sm font-bold text-navy">{{ $invoice->invoice_number }}</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Supplier</span>
                    <span class="text-sm font-medium text-slate-700">{{ $invoice->supplier->name }}</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Invoice Date</span>
                    <span class="text-sm font-medium text-slate-600">{{ $invoice->invoice_date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Invoice Amount</span>
                    <span class="text-sm font-extrabold text-navy">₱{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="col-span-2 md:col-span-1 flex flex-col justify-center items-center md:items-start">
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Status</span>
                    <span class="inline-flex items-center rounded-full bg-navy-50 px-2.5 py-1 text-xs font-bold text-navy-600 border border-navy-100 uppercase tracking-wide">
                        {{ ucwords(str_replace('_', ' ', $invoice->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- 3-Way Match Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-card flex flex-col overflow-hidden">
            <div class="bg-slate-50/70 border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                <span class="text-sm font-bold text-navy">1. Purchase Order (PO)</span>
                @if($poMatched)
                    <span class="inline-flex items-center gap-1 rounded-full bg-brand-green/10 border border-brand-green/20 px-2.5 py-0.5 text-xs font-bold text-brand-greenDark">
                        <i data-lucide="check" class="w-3 h-3"></i> Matched
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-brand-red/10 border border-brand-red/20 px-2.5 py-0.5 text-xs font-bold text-brand-red">
                        <i data-lucide="x" class="w-3 h-3"></i> Not Matched
                    </span>
                @endif
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">PO Number</span>
                            <span class="text-sm font-semibold text-slate-800">
                                {{ $invoice->purchaseOrder?->po_number ?? 'No PO linked' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">PO Date</span>
                            <span class="text-sm font-semibold text-slate-600">
                                {{ $invoice->purchaseOrder?->po_date?->format('M d, Y') ?? '—' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="text-xs font-bold text-slate-500 uppercase">PO Total Amount</span>
                        <span class="text-sm font-extrabold text-navy">
                            ₱{{ number_format($invoice->purchaseOrder?->total_amount ?? 0, 2) }}
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 space-y-4">
                    <form method="POST" action="{{ route('ap.match.link-po', $invoice) }}" class="space-y-3">
                        @csrf
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            {{ $invoice->purchaseOrder ? 'Change linked PO' : 'Link an existing PO' }}
                        </label>
                        <div class="flex gap-2">
                            <select name="purchase_order_id" class="flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-navy-600/20">
                                <option value="" {{ $invoice->purchaseOrder ? '' : 'selected' }} disabled>Select a Purchase Order</option>
                                @foreach ($purchaseOrders as $po)
                                    <option value="{{ $po->id }}" {{ $invoice->purchase_order_id == $po->id ? 'selected' : '' }}>
                                        {{ $po->po_number }} (₱{{ number_format($po->total_amount, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-navy text-white px-3 py-2 text-xs font-bold shadow-sm hover:bg-navy-700 transition shrink-0">
                                {{ $invoice->purchaseOrder ? 'Change' : 'Link' }}
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400 leading-normal">
                            Amount must match invoice to pass. Missing a PO? 
                            <a href="{{ route('ap.po.create') }}" class="text-navy hover:underline font-semibold">Create one</a>.
                        </p>
                    </form>

                    @if ($invoice->purchaseOrder)
                        <form method="POST" action="{{ route('ap.match.link-po', $invoice) }}">
                            @csrf
                            <input type="hidden" name="purchase_order_id" value="">
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 w-full rounded-xl border border-brand-red/30 text-brand-red hover:bg-brand-red/5 py-2 text-xs font-bold transition">
                                <i data-lucide="link-2-off" class="w-3.5 h-3.5"></i>
                                Unlink PO
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-card flex flex-col overflow-hidden">
            <div class="bg-slate-50/70 border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                <span class="text-sm font-bold text-navy">2. Goods Receipt (GRN)</span>
                @if($grnMatched)
                    <span class="inline-flex items-center gap-1 rounded-full bg-brand-green/10 border border-brand-green/20 px-2.5 py-0.5 text-xs font-bold text-brand-greenDark">
                        <i data-lucide="check" class="w-3 h-3"></i> Matched
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-brand-red/10 border border-brand-red/20 px-2.5 py-0.5 text-xs font-bold text-brand-red">
                        <i data-lucide="x" class="w-3 h-3"></i> Not Matched
                    </span>
                @endif
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">GRN Number</span>
                            <span class="text-sm font-semibold text-slate-800">
                                {{ $goodsReceipt?->grn_number ?? 'No GRN found' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Receipt Date</span>
                            <span class="text-sm font-semibold text-slate-600">
                                {{ $goodsReceipt?->receipt_date?->format('M d, Y') ?? '—' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="text-xs font-bold text-slate-500 uppercase">GRN Total Amount</span>
                        <span class="text-sm font-extrabold text-navy">
                            ₱{{ number_format($goodsReceipt?->total_amount ?? 0, 2) }}
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    @if ($invoice->purchaseOrder && ! $goodsReceipt)
                        <div class="space-y-2">
                            <p class="text-xs text-slate-500 leading-normal mb-2">No linked Goods Receipt was found for the current PO.</p>
                            <a href="{{ route('ap.grn.create', $invoice->purchaseOrder) }}" class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-brand-green hover:bg-brand-greenDark text-white py-2.5 text-xs font-bold shadow-sm transition">
                                <i data-lucide="package-plus" class="w-3.5 h-3.5"></i>
                                Record Goods Receipt
                            </a>
                        </div>
                    @elseif (! $invoice->purchaseOrder)
                        <div class="flex items-center gap-2 rounded-xl bg-slate-50 p-3.5 text-[11px] font-semibold text-slate-500 border border-slate-100">
                            <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                            <span>Link a PO first to record or view a goods receipt.</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 rounded-xl bg-brand-green/10 p-3.5 text-[11px] font-semibold text-brand-greenDark border border-brand-green/20">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 shrink-0"></i>
                            <span>Goods receipt documented and synced.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-card flex flex-col overflow-hidden">
            <div class="bg-slate-50/70 border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                <span class="text-sm font-bold text-navy">3. Vendor Invoice</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-brand-green/10 border border-brand-green/20 px-2.5 py-0.5 text-xs font-bold text-brand-greenDark">
                    <i data-lucide="file-check-2" class="w-3 h-3"></i> On File
                </span>
            </div>

            <div class="p-5 flex-1 flex flex-col justify-between space-y-5">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Invoice #</span>
                            <span class="text-sm font-semibold text-slate-800">{{ $invoice->invoice_number }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Due Date</span>
                            <span class="text-sm font-semibold text-slate-600">{{ $invoice->due_date->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-slate-100 rounded-xl">
                        <table class="w-full text-left text-[11px] border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase tracking-wider font-bold">
                                    <th class="p-2.5">Item</th>
                                    <th class="p-2.5 text-center">Qty</th>
                                    <th class="p-2.5 text-right">Price</th>
                                    <th class="p-2.5 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                                @foreach ($invoice->items as $item)
                                    <tr>
                                        <td class="p-2.5 truncate max-w-[100px] text-slate-800 font-bold">{{ $item->description }}</td>
                                        <td class="p-2.5 text-center">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                                        <td class="p-2.5 text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="p-2.5 text-right font-semibold text-slate-700">₱{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <span class="text-xs font-bold text-slate-500 uppercase">Total Amount</span>
                    <span class="text-sm font-extrabold text-navy">
                        ₱{{ number_format($invoice->total_amount, 2) }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- Matching Summary Cards --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
        <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4">
            <h3 class="text-sm font-bold text-navy uppercase tracking-wider">Matching Summary</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- PO Matching Status --}}
                <div class="rounded-xl border p-4 flex flex-col items-center justify-center text-center {{ $poMatched ? 'bg-brand-green/5 border-brand-green/20 text-brand-greenDark' : 'bg-brand-red/5 border-brand-red/20 text-brand-red' }}">
                    <i data-lucide="{{ $poMatched ? 'check-circle-2' : 'x-circle' }}" class="w-8 h-8 mb-2"></i>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Purchase Order</span>
                    <span class="text-xs font-bold tracking-wider uppercase">{{ $poMatched ? 'Matched' : 'Not Matched' }}</span>
                </div>

                {{-- GRN Matching Status --}}
                <div class="rounded-xl border p-4 flex flex-col items-center justify-center text-center {{ $grnMatched ? 'bg-brand-green/5 border-brand-green/20 text-brand-greenDark' : 'bg-brand-red/5 border-brand-red/20 text-brand-red' }}">
                    <i data-lucide="{{ $grnMatched ? 'check-circle-2' : 'x-circle' }}" class="w-8 h-8 mb-2"></i>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Goods Receipt</span>
                    <span class="text-xs font-bold tracking-wider uppercase">{{ $grnMatched ? 'Matched' : 'Not Matched' }}</span>
                </div>

                {{-- Invoice Matching Status --}}
                <div class="rounded-xl border p-4 flex flex-col items-center justify-center text-center bg-brand-green/5 border-brand-green/20 text-brand-greenDark">
                    <i data-lucide="check-circle-2" class="w-8 h-8 mb-2"></i>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Vendor Invoice</span>
                    <span class="text-xs font-bold tracking-wider uppercase">On File</span>
                </div>

                {{-- Overall Verdict Status --}}
                @php $overallMatched = $poMatched && $grnMatched; @endphp
                <div class="rounded-xl border p-4 flex flex-col items-center justify-center text-center {{ $overallMatched ? 'bg-brand-green/10 border-brand-green/30 text-brand-greenDark' : 'bg-brand-red/10 border-brand-red/30 text-brand-red' }}">
                    <i data-lucide="{{ $overallMatched ? 'shield-check' : 'shield-alert' }}" class="w-8 h-8 mb-2"></i>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Overall Result</span>
                    <span class="text-sm font-extrabold tracking-wider uppercase">{{ $overallMatched ? 'Approved' : 'Discrepancy' }}</span>
                </div>

            </div>
        </div>
    </div>

    {{-- Match Notes Component --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
        <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4">
            <h3 class="text-sm font-bold text-navy uppercase tracking-wider">Match Notes</h3>
        </div>
        <div class="p-6">
            <textarea name="match_notes" 
                      rows="3" 
                      form="match-actions-form" 
                      placeholder="Add notes about this match — discrepancies, questions for the supplier, anything worth flagging..." 
                      class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/20 text-slate-700 placeholder:text-slate-400">{{ old('match_notes', $invoice->remarks) }}</textarea>
        </div>
    </div>

    {{-- Bottom Action Buttons --}}
    <form id="match-actions-form" method="POST" action="{{ route('ap.match.approve', $invoice) }}">
        @csrf

        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4">
            
            <button type="submit"
                    formaction="{{ route('ap.match.clarify', $invoice) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-brand-orange text-brand-orange hover:bg-brand-orange/5 px-6 py-3 text-sm font-bold transition w-full sm:w-auto">
                <i data-lucide="help-circle" class="w-4 h-4 shrink-0"></i>
                Request Clarification
            </button>

            <button type="submit"
                    formaction="{{ route('ap.match.draft', $invoice) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 px-6 py-3 text-sm font-bold transition w-full sm:w-auto">
                <i data-lucide="save" class="w-4 h-4 shrink-0"></i>
                Save Draft
            </button>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-green hover:bg-brand-greenDark text-white px-6 py-3 text-sm font-bold shadow-sm transition w-full sm:w-auto disabled:opacity-50 disabled:pointer-events-none"
                    {{ ($poMatched && $grnMatched) ? '' : 'disabled' }}>
                <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0"></i>
                Approve Match
            </button>

        </div>
    </form>

</div>
@endsection