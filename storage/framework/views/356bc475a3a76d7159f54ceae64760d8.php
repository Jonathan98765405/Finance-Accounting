<?php $__env->startSection('page-title', 'Schedule Payment'); ?>
<?php $__env->startSection('page-title-heading', 'Schedule Payment'); ?>
<?php $__env->startSection('page-subtitle', 'Plan upcoming vendor payments and track what\'s due, scheduled, or overdue.'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1600px] mx-auto space-y-6">

    
    <?php if(session('success')): ?>
        <div class="flex items-center gap-3 rounded-xl bg-brand-green/10 border border-brand-green/20 text-brand-greenDark px-4 py-3.5 text-sm font-semibold shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="rounded-xl bg-brand-red/10 border border-brand-red/20 text-brand-red px-4 py-3.5 text-sm font-semibold shadow-sm">
            <ul class="list-disc list-inside space-y-0.5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5">

        <div class="bg-white rounded-2xl shadow-card p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Outstanding</p>
            <p class="text-xl font-extrabold text-navy mt-1">₱<?php echo e(number_format($totalOutstanding, 2)); ?></p>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Due This Week</p>
            <p class="text-xl font-extrabold text-navy mt-1">₱<?php echo e(number_format($dueThisWeek, 2)); ?></p>
            <p class="text-[11px] text-slate-400 mt-0.5"><?php echo e($dueThisWeekCount); ?> invoice(s)</p>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Due This Month</p>
            <p class="text-xl font-extrabold text-navy mt-1">₱<?php echo e(number_format($dueThisMonth, 2)); ?></p>
            <p class="text-[11px] text-slate-400 mt-0.5"><?php echo e($dueThisMonthCount); ?> invoice(s)</p>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Overdue</p>
            <p class="text-xl font-extrabold text-brand-red mt-1">₱<?php echo e(number_format($overdue, 2)); ?></p>
            <p class="text-[11px] text-slate-400 mt-0.5"><?php echo e($overdueCount); ?> invoice(s)</p>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Scheduled</p>
            <p class="text-xl font-extrabold text-brand-greenDark mt-1">₱<?php echo e(number_format($totalScheduled, 2)); ?></p>
            <p class="text-[11px] text-slate-400 mt-0.5"><?php echo e($scheduledPayments->count()); ?> payment(s)</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
            <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4">
                <h3 class="text-sm font-bold text-navy uppercase tracking-wider">Invoices Ready to Schedule</h3>
            </div>

            <div class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $readyInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                            <div>
                                <p class="text-sm font-bold text-navy"><?php echo e($invoice->invoice_number); ?></p>
                                <p class="text-xs text-slate-500"><?php echo e($invoice->supplier->name ?? '—'); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-extrabold text-navy">₱<?php echo e(number_format($invoice->amount, 2)); ?></p>
                                <p class="text-[11px] <?php echo e($invoice->due_date && $invoice->due_date->isPast() ? 'text-brand-red font-semibold' : 'text-slate-400'); ?>">
                                    Due <?php echo e($invoice->due_date?->format('M d, Y') ?? '—'); ?>

                                </p>
                            </div>
                        </div>

                        <form method="POST" action="<?php echo e(route('ap.schedule.store', $invoice)); ?>" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                            <?php echo csrf_field(); ?>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Payment Date</label>
                                <input type="date" name="payment_date" required
                                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-navy-600/20">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Payment Method</label>
                                <select name="payment_method" required
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-navy-600/20">
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Priority</label>
                                <select name="priority" required
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-navy-600/20">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-green hover:bg-brand-greenDark text-white px-3 py-2 text-xs font-bold shadow-sm transition">
                                <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                                Schedule
                            </button>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-slate-400 py-10 text-center">No invoices ready to schedule.</p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
            <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4">
                <h3 class="text-sm font-bold text-navy uppercase tracking-wider"><?php echo e($calendarMonth->format('F Y')); ?></h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-7 gap-1.5 text-center text-[10px] font-bold text-slate-400 uppercase mb-2">
                    <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                </div>
                <div class="grid grid-cols-7 gap-1.5">
                    <?php
                        $priorityColor = [
                            'low' => 'bg-brand-green/70',
                            'medium' => 'bg-brand-orange/80',
                            'high' => 'bg-brand-red/80',
                        ];
                    ?>
                    <?php $__currentLoopData = $calendarCells; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="aspect-square rounded-lg flex flex-col items-center justify-center text-[11px]
                            <?php echo e($cell ? ($cell['isToday'] ? 'bg-navy-600 text-white font-bold' : 'bg-slate-50 text-slate-600') : ''); ?>">
                            <?php if($cell): ?>
                                <span><?php echo e($cell['day']); ?></span>
                                <?php if($cell['priority']): ?>
                                    <span class="w-1.5 h-1.5 rounded-full mt-0.5 <?php echo e($priorityColor[$cell['priority']] ?? 'bg-slate-300'); ?>"></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="flex items-center gap-4 mt-4 pt-4 border-t border-slate-100 text-[10px] text-slate-500">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-brand-green/70"></span>Low</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-brand-orange/80"></span>Medium</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-brand-red/80"></span>High</span>
                </div>
            </div>
        </div>

    </div>

    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden">
        <div class="bg-slate-50/70 border-b border-slate-100 px-6 py-4">
            <h3 class="text-sm font-bold text-navy uppercase tracking-wider">Scheduled Payments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-3 font-semibold">Reference</th>
                        <th class="px-6 py-3 font-semibold">Supplier</th>
                        <th class="px-6 py-3 font-semibold">Payment Date</th>
                        <th class="px-6 py-3 font-semibold">Priority</th>
                        <th class="px-6 py-3 font-semibold text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php $__empty_1 = true; $__currentLoopData = $scheduledPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-3.5 font-semibold text-navy"><?php echo e($payment->reference_number); ?></td>
                            <td class="px-6 py-3.5"><?php echo e($payment->invoice->supplier->name ?? '—'); ?></td>
                            <td class="px-6 py-3.5"><?php echo e($payment->payment_date?->format('M d, Y') ?? '—'); ?></td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase
                                    <?php echo e($payment->priority === 'high' ? 'bg-brand-red/10 text-brand-red' : ($payment->priority === 'low' ? 'bg-brand-green/10 text-brand-greenDark' : 'bg-brand-orange/10 text-brand-orange')); ?>">
                                    <?php echo e($payment->priority); ?>

                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right font-semibold">₱<?php echo e(number_format($payment->amount, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-8">No payments scheduled yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/accounts-payable/schedule-payment.blade.php ENDPATH**/ ?>