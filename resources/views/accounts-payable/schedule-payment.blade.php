@extends('layouts.app')

@section('page-title', 'Track Due Dates & Schedule Payments')

@section('page-title-heading', 'Track Due Dates & Schedule Payments')
@section('page-subtitle', 'Monitor invoice due dates and schedule payments to ensure timely vendor payments.')

@section('content')

<div class="space-y-6 sm:space-y-8">

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl bg-brand-green text-white px-4 py-3 shadow-card text-sm font-medium">
            <i data-lucide="circle-check-big" class="w-5 h-5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="flex flex-col gap-2 rounded-xl bg-brand-red text-white px-4 py-3 shadow-card text-sm font-medium">
            <div class="flex items-center gap-3">
                <i data-lucide="circle-alert" class="w-5 h-5 shrink-0"></i>
                <strong>Please fix the following:</strong>
            </div>
            <ul class="list-disc pl-8 mt-1 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================= SUMMARY CARDS ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Total AP --}}
        <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-5 flex items-center gap-4 transition hover:-translate-y-0.5 duration-200">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-navy-50 text-navy">
                <i data-lucide="wallet" class="w-7 h-7"></i>
            </div>
            <div class="leading-tight">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total AP</p>
                <p class="text-2xl font-extrabold text-navy mt-1">₱{{ number_format($totalOutstanding, 2) }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Outstanding Balance</p>
            </div>
        </div>

        {{-- Due This Week --}}
        <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-5 flex items-center gap-4 transition hover:-translate-y-0.5 duration-200">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-orange/10 text-brand-orange">
                <i data-lucide="calendar-range" class="w-7 h-7"></i>
            </div>
            <div class="leading-tight">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Due This Week</p>
                <p class="text-2xl font-extrabold text-navy mt-1">₱{{ number_format($dueThisWeek, 2) }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $dueThisWeekCount }} Invoice{{ $dueThisWeekCount === 1 ? '' : 's' }}</p>
            </div>
        </div>

        {{-- Due This Month --}}
        <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-5 flex items-center gap-4 transition hover:-translate-y-0.5 duration-200">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-blue/10 text-brand-blue">
                <i data-lucide="calendar-days" class="w-7 h-7"></i>
            </div>
            <div class="leading-tight">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Due This Month</p>
                <p class="text-2xl font-extrabold text-navy mt-1">₱{{ number_format($dueThisMonth, 2) }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $dueThisMonthCount }} Invoice{{ $dueThisMonthCount === 1 ? '' : 's' }}</p>
            </div>
        </div>

        {{-- Overdue --}}
        <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-5 flex items-center gap-4 transition hover:-translate-y-0.5 duration-200">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-brand-red/10 text-brand-red">
                <i data-lucide="alert-circle" class="w-7 h-7"></i>
            </div>
            <div class="leading-tight">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Overdue</p>
                <p class="text-2xl font-extrabold text-brand-red mt-1">₱{{ number_format($overdue, 2) }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $overdueCount }} Invoice{{ $overdueCount === 1 ? '' : 's' }}</p>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

        {{-- Left: Upcoming Payments Table --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-card border border-slate-100 p-5 sm:p-6 flex flex-col gap-6 h-full">
            <div class="flex items-center justify-between shrink-0">
                <h3 class="text-lg font-bold text-navy">Upcoming Payments</h3>
            </div>

            {{-- Table Filters --}}
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 shrink-0">
                <div class="sm:col-span-6 relative">
                    <input type="text" id="paymentSearch" placeholder="Search supplier or invoice..."
                        class="w-full rounded-xl border border-slate-200 bg-white py-2 pl-4 pr-10 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2"></i>
                </div>

                <div class="sm:col-span-3">
                    <select id="statusFilter" class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                        <option value="">All Statuses</option>
                        <option value="overdue">Overdue</option>
                        <option value="due">Awaiting Schedule</option>
                        <option value="scheduled">Scheduled</option>
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <select id="priorityFilter" class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
            </div>

            {{-- Table Wrapper --}}
            <div class="overflow-x-auto overflow-y-auto rounded-xl border border-slate-100 flex-1">
                <table class="w-full text-left text-sm border-collapse" id="paymentsTable">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-navy uppercase tracking-wider">
                            <th class="py-3 px-4">Invoice</th>
                            <th class="py-3 px-4">Supplier</th>
                            <th class="py-3 px-4">Due Date</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Priority</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-50">
                        @php
                            $statusLabels = ['due' => 'Awaiting Schedule', 'scheduled' => 'Scheduled', 'overdue' => 'Overdue'];
                            $priorityLabels = ['high' => 'High', 'medium' => 'Medium', 'low' => 'Low'];

                            $rows = collect();

                            foreach ($readyInvoices as $invoice) {
                                $status = $invoice->due_date && $invoice->due_date->isPast() ? 'overdue' : 'due';

                                $rows->push([
                                    'invoice_number' => $invoice->invoice_number,
                                    'supplier' => $invoice->supplier->name,
                                    'date' => $invoice->due_date,
                                    'amount' => $invoice->total_amount,
                                    'priority' => null,
                                    'status' => $status,
                                    'schedule_invoice_id' => $invoice->id,
                                ]);
                            }

                            foreach ($scheduledPayments as $payment) {
                                $rows->push([
                                    'invoice_number' => $payment->invoice->invoice_number,
                                    'supplier' => $payment->invoice->supplier->name,
                                    'date' => $payment->payment_date ?? $payment->invoice->due_date,
                                    'amount' => $payment->amount,
                                    'priority' => $payment->priority,
                                    'status' => 'scheduled',
                                    'schedule_invoice_id' => null,
                                ]);
                            }

                            $rows = $rows->sortBy('date')->values();
                            $visibleLimit = 7;
                            $visibleRows = $rows->take($visibleLimit);
                        @endphp

                        @forelse ($visibleRows as $row)
                            <tr class="payment-row hover:bg-slate-50/50 transition-colors"
                                data-status="{{ $row['status'] }}"
                                data-priority="{{ $row['priority'] ?? '' }}"
                                data-search="{{ strtolower($row['invoice_number'] . ' ' . $row['supplier']) }}">

                                <td class="py-3.5 px-4 font-semibold text-navy">{{ $row['invoice_number'] }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $row['supplier'] }}</td>
                                <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">{{ $row['date']?->format('M d, Y') ?? '—' }}</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-700">₱{{ number_format($row['amount'], 2) }}</td>

                                <td class="py-3.5 px-4">
                                    @if ($row['priority'] === 'high')
                                        <span class="inline-flex items-center rounded-full bg-brand-red/10 px-2.5 py-1 text-xs font-semibold text-brand-red">{{ $priorityLabels[$row['priority']] }}</span>
                                    @elseif ($row['priority'] === 'medium')
                                        <span class="inline-flex items-center rounded-full bg-brand-orange/10 px-2.5 py-1 text-xs font-semibold text-brand-orange">{{ $priorityLabels[$row['priority']] }}</span>
                                    @elseif ($row['priority'] === 'low')
                                        <span class="inline-flex items-center rounded-full bg-brand-green/10 px-2.5 py-1 text-xs font-semibold text-brand-green">{{ $priorityLabels[$row['priority']] }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4">
                                    @if ($row['status'] === 'overdue')
                                        <span class="inline-flex items-center rounded-full bg-brand-red/10 px-2.5 py-1 text-xs font-semibold text-brand-red">Overdue</span>
                                    @elseif ($row['status'] === 'scheduled')
                                        <span class="inline-flex items-center rounded-full bg-brand-green/10 px-2.5 py-1 text-xs font-semibold text-brand-green">Scheduled</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">Awaiting Schedule</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    @if ($row['schedule_invoice_id'])
                                        <button type="button"
                                            class="schedule-btn inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-navy px-3 py-1.5 transition shadow-sm"
                                            data-invoice-id="{{ $row['schedule_invoice_id'] }}">
                                            <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                                            Schedule
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-xs font-medium">Scheduled</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-slate-400 py-10">
                                    No invoices are awaiting payment right now.
                                </td>
                            </tr>
                        @endforelse

                        <tr id="noResultsRow" class="hidden">
                            <td colspan="7" class="text-center text-slate-400 py-10">No payments match your search/filters.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- View All trigger --}}
            @if ($rows->count() > $visibleLimit)
                <div class="shrink-0 text-center border-t border-slate-50 pt-4">
                    <button type="button" id="openViewAllModal"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy hover:text-brand-green transition">
                        View All ({{ $rows->count() }} total)
                        <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- Right Column (Calendar and Progress Metrics) --}}
        <div class="space-y-6 sm:space-y-8 flex flex-col h-full">

            {{-- Calendar Component --}}
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-5 sm:p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-navy">Payment Calendar</h3>
                    <span class="inline-flex items-center rounded-lg bg-brand-green/10 px-2.5 py-1 text-xs font-bold text-brand-greenDark">
                        {{ $calendarMonth->format('F Y') }}
                    </span>
                </div>

                <table class="w-full text-sm text-center border-collapse">
                    <thead>
                        <tr class="text-slate-400 font-semibold text-xs border-b border-slate-50">
                            <th class="pb-2 w-10">Sun</th>
                            <th class="pb-2 w-10">Mon</th>
                            <th class="pb-2 w-10">Tue</th>
                            <th class="pb-2 w-10">Wed</th>
                            <th class="pb-2 w-10">Thu</th>
                            <th class="pb-2 w-10">Fri</th>
                            <th class="pb-2 w-10">Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (array_chunk($calendarCells, 7) as $week)
                            <tr class="border-b border-slate-50/50 last:border-0">
                                @foreach ($week as $cell)
                                    <td class="py-2">
                                        @if ($cell)
                                            @if ($cell['priority'] === 'high')
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-brand-red text-white text-xs font-bold shadow-sm shadow-brand-red/30">{{ $cell['day'] }}</span>
                                            @elseif ($cell['priority'] === 'medium')
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-brand-orange text-white text-xs font-bold shadow-sm shadow-brand-orange/30">{{ $cell['day'] }}</span>
                                            @elseif ($cell['priority'] === 'low')
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-brand-green text-white text-xs font-bold shadow-sm shadow-brand-green/30">{{ $cell['day'] }}</span>
                                            @elseif ($cell['isToday'])
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-navy text-white text-xs font-bold">{{ $cell['day'] }}</span>
                                            @else
                                                <span class="text-slate-600 text-xs font-medium">{{ $cell['day'] }}</span>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr class="border-slate-100">

                <div class="grid grid-cols-3 gap-2 text-xs font-semibold text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-brand-red shrink-0"></span>
                        High Priority
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-brand-orange shrink-0"></span>
                        Medium
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-brand-green shrink-0"></span>
                        Low Priority
                    </div>
                </div>
            </div>

            {{-- Progress Summary Module --}}
            <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-5 sm:p-6 space-y-5 flex-1">
                <h3 class="text-lg font-bold text-navy">Payment Summary</h3>

                @php
                    $pct = fn ($amount) => $totalOutstanding > 0 ? min(100, round($amount / $totalOutstanding * 100)) : 0;
                @endphp

                <div class="space-y-4 text-sm">
                    {{-- Total Scheduled --}}
                    <div>
                        <div class="flex justify-between font-medium text-slate-600 mb-1.5">
                            <span>Total Scheduled</span>
                            <span class="font-bold text-navy">₱{{ number_format($totalScheduled, 2) }}</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-green rounded-full" style="width:{{ $pct($totalScheduled) }}%"></div>
                        </div>
                    </div>

                    {{-- Due This Week --}}
                    <div>
                        <div class="flex justify-between font-medium text-slate-600 mb-1.5">
                            <span>Due This Week</span>
                            <span class="font-bold text-navy">₱{{ number_format($dueThisWeek, 2) }}</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-orange rounded-full" style="width:{{ $pct($dueThisWeek) }}%"></div>
                        </div>
                    </div>

                    {{-- Due This Month --}}
                    <div>
                        <div class="flex justify-between font-medium text-slate-600 mb-1.5">
                            <span>Due This Month</span>
                            <span class="font-bold text-navy">₱{{ number_format($dueThisMonth, 2) }}</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-blue rounded-full" style="width:{{ $pct($dueThisMonth) }}%"></div>
                        </div>
                    </div>

                    {{-- Overdue --}}
                    <div>
                        <div class="flex justify-between font-medium text-slate-600 mb-1.5">
                            <span class="text-brand-red font-semibold">Overdue</span>
                            <span class="font-bold text-brand-red">₱{{ number_format($overdue, 2) }}</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-red rounded-full" style="width:{{ $pct($overdue) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= SCHEDULING FORM CARD ================= --}}
    <div class="bg-white rounded-2xl shadow-card border border-slate-100 p-5 sm:p-6" id="scheduleFormCard">
        <div class="mb-5">
            <h3 class="text-lg font-bold text-navy">Schedule New Payment</h3>
            <p class="text-sm text-slate-500 mt-1">Pick an approved invoice and complete the form below to schedule its payment.</p>
        </div>

        @if ($readyInvoices->isEmpty())
            <div class="flex items-center gap-3 rounded-xl bg-brand-orange/10 text-brand-orange px-4 py-3 text-sm font-semibold">
                <i data-lucide="info" class="w-5 h-5 shrink-0"></i>
                <span>
                    No approved invoices are ready to schedule. Invoices need to pass
                    <a href="{{ route('ap.match') }}" class="underline hover:text-brand-orange/80">Three-Way Match</a> first.
                </span>
            </div>
        @else
            <form method="POST" action="{{ route('ap.schedule.store', $readyInvoices->first()) }}" id="scheduleForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    {{-- Form Left --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Invoice</label>
                            <select class="w-full rounded-xl border border-slate-200 bg-white py-2.5 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30" id="scheduleInvoiceSelect" required>
                                <option value="" selected disabled>Select Invoice</option>
                                @foreach ($readyInvoices as $invoice)
                                    <option
                                        value="{{ $invoice->id }}"
                                        data-action="{{ route('ap.schedule.store', $invoice) }}"
                                        data-supplier="{{ $invoice->supplier->name }}"
                                        data-amount="₱{{ number_format($invoice->total_amount, 2) }}"
                                        data-due="{{ $invoice->due_date->format('M d, Y') }}">
                                        {{ $invoice->invoice_number }} &mdash; {{ $invoice->supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Supplier</label>
                            <input type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3 text-sm text-slate-500 focus:outline-none" id="scheduleSupplierDisplay" value="" readonly placeholder="Select an invoice first">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Amount</label>
                                <input type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3 text-sm text-slate-500 focus:outline-none" id="scheduleAmountDisplay" value="" readonly placeholder="Select an invoice first">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Due Date</label>
                                <input type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3 text-sm text-slate-500 focus:outline-none" id="scheduleDueDisplay" value="" readonly placeholder="Select an invoice first">
                            </div>
                        </div>
                    </div>

                    {{-- Form Right --}}
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Payment Date</label>
                                <input type="date" name="payment_date" class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30" value="{{ old('payment_date') }}" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Payment Method</label>
                                <select class="w-full rounded-xl border border-slate-200 bg-white py-2.5 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30" name="payment_method" required>
                                    <option value="Bank Transfer" {{ old('payment_method', 'Bank Transfer') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Priority</label>
                                <select class="w-full rounded-xl border border-slate-200 bg-white py-2.5 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30" name="priority" required>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Notes</label>
                            <textarea name="remarks" class="w-full rounded-xl border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 resize-none" rows="4" placeholder="Enter remarks or payment instructions...">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-slate-100">

                <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                    <button type="reset" class="w-full sm:w-auto rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold text-slate-600 px-6 py-2.5 transition">
                        Reset Form
                    </button>
                    <button type="submit" class="w-full sm:w-auto rounded-xl bg-brand-green hover:bg-brand-greenDark text-sm font-semibold text-white px-8 py-2.5 shadow-md shadow-brand-green/20 transition disabled:opacity-50 disabled:cursor-not-allowed" id="scheduleSubmitBtn" disabled>
                        Confirm Schedule
                    </button>
                </div>
            </form>
        @endif
    </div>

</div>

{{-- ================= VIEW ALL MODAL ================= --}}
<div id="viewAllModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col">

        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-bold text-navy">All Upcoming Payments</h3>
            <button type="button" id="closeViewAllModalX" class="text-slate-400 hover:text-slate-600 transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="overflow-y-auto px-6 py-4 flex-1">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="sticky top-0 bg-white">
                    <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-2.5 pr-4">Invoice</th>
                        <th class="py-2.5 pr-4">Supplier</th>
                        <th class="py-2.5 pr-4">Due Date</th>
                        <th class="py-2.5 pr-4">Amount</th>
                        <th class="py-2.5 pr-4">Priority</th>
                        <th class="py-2.5 pr-4">Status</th>
                        <th class="py-2.5 pr-0 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($rows as $row)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 pr-4 font-semibold text-navy whitespace-nowrap">{{ $row['invoice_number'] }}</td>
                            <td class="py-3 pr-4 text-slate-600 whitespace-nowrap">{{ $row['supplier'] }}</td>
                            <td class="py-3 pr-4 text-slate-600 whitespace-nowrap">{{ $row['date']?->format('M d, Y') ?? '—' }}</td>
                            <td class="py-3 pr-4 font-semibold text-slate-700 whitespace-nowrap">₱{{ number_format($row['amount'], 2) }}</td>

                            <td class="py-3 pr-4">
                                @if ($row['priority'] === 'high')
                                    <span class="inline-flex items-center rounded-full bg-brand-red/10 px-2.5 py-1 text-xs font-semibold text-brand-red">{{ $priorityLabels[$row['priority']] }}</span>
                                @elseif ($row['priority'] === 'medium')
                                    <span class="inline-flex items-center rounded-full bg-brand-orange/10 px-2.5 py-1 text-xs font-semibold text-brand-orange">{{ $priorityLabels[$row['priority']] }}</span>
                                @elseif ($row['priority'] === 'low')
                                    <span class="inline-flex items-center rounded-full bg-brand-green/10 px-2.5 py-1 text-xs font-semibold text-brand-green">{{ $priorityLabels[$row['priority']] }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            <td class="py-3 pr-4">
                                @if ($row['status'] === 'overdue')
                                    <span class="inline-flex items-center rounded-full bg-brand-red/10 px-2.5 py-1 text-xs font-semibold text-brand-red">Overdue</span>
                                @elseif ($row['status'] === 'scheduled')
                                    <span class="inline-flex items-center rounded-full bg-brand-green/10 px-2.5 py-1 text-xs font-semibold text-brand-green">Scheduled</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">Awaiting Schedule</span>
                                @endif
                            </td>

                            <td class="py-3 pr-0 text-center">
                                @if ($row['schedule_invoice_id'])
                                    <button type="button"
                                        class="modal-schedule-btn inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-navy px-3 py-1.5 transition shadow-sm"
                                        data-invoice-id="{{ $row['schedule_invoice_id'] }}">
                                        <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                                        Schedule
                                    </button>
                                @else
                                    <span class="text-slate-400 text-xs font-medium">Scheduled</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 shrink-0">
            <button type="button" id="closeViewAllModalBtn"
                class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold text-slate-600 px-6 py-2.5 transition">
                Close
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---- Schedule form: invoice select drives the form action + display fields ----
    const invoiceSelect = document.getElementById('scheduleInvoiceSelect');
    const scheduleForm = document.getElementById('scheduleForm');
    const submitBtn = document.getElementById('scheduleSubmitBtn');

    function applyInvoiceSelection(select) {
        const opt = select.options[select.selectedIndex];
        if (!opt || !opt.value) return;

        scheduleForm.action = opt.getAttribute('data-action');
        document.getElementById('scheduleSupplierDisplay').value = opt.getAttribute('data-supplier') || '';
        document.getElementById('scheduleAmountDisplay').value = opt.getAttribute('data-amount') || '';
        document.getElementById('scheduleDueDisplay').value = opt.getAttribute('data-due') || '';
        submitBtn.disabled = false;
    }

    if (invoiceSelect) {
        invoiceSelect.addEventListener('change', function () {
            applyInvoiceSelection(invoiceSelect);
        });
    }

    function selectInvoiceAndScroll(id) {
        if (invoiceSelect) {
            invoiceSelect.value = id;
            applyInvoiceSelection(invoiceSelect);
        }
        document.getElementById('scheduleFormCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ---- "Schedule" button on a main-table row ----
    document.querySelectorAll('.schedule-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            selectInvoiceAndScroll(btn.getAttribute('data-invoice-id'));
        });
    });

    // ---- Search + filters on the Upcoming Payments table (top 5 rows) ----
    const searchInput = document.getElementById('paymentSearch');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const rows = Array.from(document.querySelectorAll('#paymentsTable tbody tr.payment-row'));
    const noResultsRow = document.getElementById('noResultsRow');

    function applyFilters() {
        const term = (searchInput?.value || '').toLowerCase().trim();
        const status = statusFilter?.value || '';
        const priority = priorityFilter?.value || '';
        let visibleCount = 0;

        rows.forEach(function (row) {
            const matchesSearch = !term || row.getAttribute('data-search').includes(term);
            const matchesStatus = !status || row.getAttribute('data-status') === status;
            const matchesPriority = !priority || row.getAttribute('data-priority') === priority;
            const visible = matchesSearch && matchesStatus && matchesPriority;

            row.classList.toggle('hidden', !visible);
            if (visible) visibleCount++;
        });

        if (noResultsRow) {
            noResultsRow.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }
    }

    [searchInput, statusFilter, priorityFilter].forEach(function (el) {
        if (el) el.addEventListener('input', applyFilters);
    });

    // ---- View All modal ----
    const openViewAllBtn = document.getElementById('openViewAllModal');
    const viewAllModal = document.getElementById('viewAllModal');
    const closeViewAllModalX = document.getElementById('closeViewAllModalX');
    const closeViewAllModalBtn = document.getElementById('closeViewAllModalBtn');

    function openModal() {
        viewAllModal.classList.remove('hidden');
        viewAllModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        viewAllModal.classList.add('hidden');
        viewAllModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    if (openViewAllBtn) openViewAllBtn.addEventListener('click', openModal);
    if (closeViewAllModalX) closeViewAllModalX.addEventListener('click', closeModal);
    if (closeViewAllModalBtn) closeViewAllModalBtn.addEventListener('click', closeModal);

    // Click on the dark backdrop closes the modal
    if (viewAllModal) {
        viewAllModal.addEventListener('click', function (e) {
            if (e.target === viewAllModal) closeModal();
        });
    }

    // Esc key closes the modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !viewAllModal.classList.contains('hidden')) closeModal();
    });

    // "Schedule" button inside the modal: close modal, then jump to the form
    document.querySelectorAll('.modal-schedule-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = btn.getAttribute('data-invoice-id');
            closeModal();
            selectInvoiceAndScroll(id);
        });
    });

});
</script>

@endsection