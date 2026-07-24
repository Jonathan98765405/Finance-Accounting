@extends('layouts.app')

@section('page-title-heading', 'Purchase Requisitions')
@section('page-subtitle', 'Create and manage internal purchase requests.')

@section('content')
<div class="space-y-8">

    {{-- ================= CREATE REQUISITION FORM ================= --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-navy/5 text-navy">
                <i data-lucide="file-plus-2" class="w-5 h-5"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-navy">Create New Requisition</h2>
                <p class="text-xs text-slate-500">Provide a clear justification and specify required line items.</p>
            </div>
        </div>

        <form action="{{ route('requisitions.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Purpose / Justification --}}
            <div>
                <label for="purpose" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                    Purpose / Justification <span class="text-brand-red">*</span>
                </label>
                <input type="text" id="purpose" name="purpose"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-navy focus:ring-1 focus:ring-navy outline-none transition"
                    placeholder="e.g. Office Hardware Refresh Q3" required>
                @error('purpose')
                    <span class="text-xs text-brand-red mt-1 block font-semibold">{{ $message }}</span>
                @enderror
            </div>

            {{-- Item Details Header --}}
            <div class="border-t border-slate-100 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Item Details</h3>
                    <button type="button" id="add-item-btn" class="inline-flex items-center gap-1.5 text-xs font-bold text-navy hover:text-brand-greenDark transition">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Another Item
                    </button>
                </div>

                <div id="items-container" class="space-y-3">
                    <div class="item-row grid grid-cols-1 sm:grid-cols-12 gap-3 items-center bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                        <div class="sm:col-span-6">
                            <label class="block text-[11px] font-semibold text-slate-500 sm:hidden mb-1">Description</label>
                            <input type="text" name="items[0][description]" placeholder="Item Description (e.g., Ergonomic Chair)"
                                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-navy focus:outline-none" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-semibold text-slate-500 sm:hidden mb-1">Quantity</label>
                            <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" value="1"
                                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:border-navy focus:outline-none" required>
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-[11px] font-semibold text-slate-500 sm:hidden mb-1">Unit Price ($)</label>
                            <input type="number" step="0.01" name="items[0][unit_price]" placeholder="Unit Price ($)"
                                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-navy focus:outline-none" required>
                        </div>
                        <div class="sm:col-span-1 text-right">
                            <button type="button" class="remove-row-btn hidden text-slate-400 hover:text-brand-red p-1 rounded-lg transition" title="Remove item">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="flex justify-end pt-2">
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-navy hover:bg-navy-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-sm transition">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Submit Requisition</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ================= REQUISITIONS TABLE ================= --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-bold text-navy">Requisition History</h3>
                <p class="text-xs text-slate-500 mt-0.5">Track status and manage submitted purchase requests</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 uppercase text-xs font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">REQ #</th>
                        <th class="px-6 py-3.5">Purpose</th>
                        <th class="px-6 py-3.5">Total Amount</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requisitions as $requisition)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-4 font-bold text-navy">
                                {{ $requisition->requisition_number }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ $requisition->purpose }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                ${{ number_format($requisition->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'pending_approval' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'rejected' => 'bg-red-50 text-brand-red border-red-200',
                                        'ordered' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    ];
                                    $dotClasses = [
                                        'approved' => 'bg-emerald-500',
                                        'pending_approval' => 'bg-amber-500',
                                        'rejected' => 'bg-brand-red',
                                        'ordered' => 'bg-indigo-500',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusClasses[$requisition->status] ?? 'bg-slate-50 text-slate-600 border-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses[$requisition->status] ?? 'bg-slate-400' }}"></span>
                                    {{ ucfirst(str_replace('_', ' ', $requisition->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($requisition->status === 'pending_approval' && auth()->user()?->can('approve-items') && $requisition->user_id !== auth()->id())
                                    <div class="inline-flex items-center gap-2">
                                        <form action="{{ route('approvals.process') }}" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="approvable_type" value="requisition">
                                            <input type="hidden" name="approvable_id" value="{{ $requisition->id }}">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3 py-1.5 rounded-lg shadow-sm transition">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('approvals.process') }}" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="approvable_type" value="requisition">
                                            <input type="hidden" name="approvable_id" value="{{ $requisition->id }}">
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold px-3 py-1.5 rounded-lg shadow-sm transition">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    </div>
                                @elseif($requisition->status === 'pending_approval')
                                    <span class="text-xs text-slate-400">Awaiting approval</span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="file-x" class="w-10 h-10 stroke-1 mb-2 text-slate-300"></i>
                                    <p class="font-medium text-slate-500 text-sm">No requisitions found.</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Submit your first purchase request using the form above.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let itemIndex = 1;
        const container = document.getElementById('items-container');
        const addBtn = document.getElementById('add-item-btn');

        if (addBtn && container) {
            addBtn.addEventListener('click', function () {
                const newRow = document.createElement('div');
                newRow.className = 'item-row grid grid-cols-1 sm:grid-cols-12 gap-3 items-center bg-slate-50/70 p-3.5 rounded-xl border border-slate-100';
                newRow.innerHTML = `
                    <div class="sm:col-span-6">
                        <label class="block text-[11px] font-semibold text-slate-500 sm:hidden mb-1">Description</label>
                        <input type="text" name="items[${itemIndex}][description]" placeholder="Item Description"
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-navy focus:outline-none" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-slate-500 sm:hidden mb-1">Quantity</label>
                        <input type="number" name="items[${itemIndex}][quantity]" placeholder="Qty" min="1" value="1"
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:border-navy focus:outline-none" required>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-semibold text-slate-500 sm:hidden mb-1">Unit Price ($)</label>
                        <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" placeholder="Unit Price ($)"
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-navy focus:outline-none" required>
                    </div>
                    <div class="sm:col-span-1 text-right">
                        <button type="button" class="remove-row-btn text-slate-400 hover:text-brand-red p-1 rounded-lg transition" title="Remove item">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                `;
                container.appendChild(newRow);
                itemIndex++;
                lucide.createIcons();
                updateRemoveButtons();
            });

            container.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.remove-row-btn');
                if (removeBtn) {
                    const row = removeBtn.closest('.item-row');
                    if (row) {
                        row.remove();
                        updateRemoveButtons();
                    }
                }
            });

            function updateRemoveButtons() {
                const rows = container.querySelectorAll('.item-row');
                rows.forEach((row) => {
                    const btn = row.querySelector('.remove-row-btn');
                    if (btn) {
                        if (rows.length > 1) {
                            btn.classList.remove('hidden');
                        } else {
                            btn.classList.add('hidden');
                        }
                    }
                });
            }
        }
    });
</script>
@endpush
