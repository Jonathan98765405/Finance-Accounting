<?php $__env->startSection('page-title', 'All Journal Entries'); ?>
<?php $__env->startSection('page-title-heading', 'All Journal Entries'); ?>
<?php $__env->startSection('page-subtitle', 'Complete history of every recorded transaction.'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="bg-brand-green/10 border border-brand-green/20 text-brand-greenDark px-6 py-4 rounded-2xl mb-8 flex items-center shadow-sm">
        <div class="bg-brand-green/20 p-2 rounded-full mr-4">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
        </div>
        <span class="font-medium"><?php echo e(session('success')); ?></span>
    </div>
<?php endif; ?>


<a href="<?php echo e(route('ledger.index')); ?>" class="inline-flex items-center gap-2 text-slate-500 hover:text-navy font-semibold text-sm mb-6 transition-colors group">
    <div class="w-8 h-8 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center group-hover:bg-navy group-hover:text-white group-hover:border-navy transition-all">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
    </div>
    Back to General Ledger
</a>


<form method="GET" action="<?php echo e(route('ledger.alljournal')); ?>" class="mb-6">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_24px_-8px_rgba(15,23,42,0.05)] p-4 flex flex-col xl:flex-row xl:items-center gap-3">

        
        <div class="flex flex-wrap gap-3">
            <div class="relative">
                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="appearance-none border border-slate-200 rounded-xl pl-4 pr-9 py-2.5 text-sm font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-navy-600/10 focus:border-navy transition-all cursor-pointer">
                    <option value="all">All Records</option>
                    <option value="Posted" <?php echo e(request('status') == 'Posted' ? 'selected' : ''); ?>>Posted</option>
                    <option value="Draft" <?php echo e(request('status') == 'Draft' ? 'selected' : ''); ?>>Draft</option>
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>

            <div class="relative">
                <select
                    name="account_id"
                    onchange="this.form.submit()"
                    class="appearance-none border border-slate-200 rounded-xl pl-4 pr-9 py-2.5 text-sm font-semibold text-slate-600 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-navy-600/10 focus:border-navy transition-all cursor-pointer">
                    <option value="">All Accounts</option>
                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option
                            value="<?php echo e($account->id); ?>"
                            <?php echo e(request('account_id') == $account->id ? 'selected' : ''); ?>>
                            <?php echo e($account->account_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>
        </div>

        
        <div class="flex gap-3 xl:ml-auto">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search reference or description..."
                    class="border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 w-72 text-sm bg-slate-50 focus:outline-none focus:ring-4 focus:ring-navy-600/10 focus:border-navy focus:bg-white transition-all">
            </div>

            <button class="bg-navy hover:bg-navy-700 text-white px-6 py-2.5 rounded-xl font-semibold text-sm shadow-[0_4px_10px_rgba(22,38,91,0.2)] hover:shadow-[0_6px_15px_rgba(22,38,91,0.3)] transition-all active:scale-95">
                Search
            </button>
        </div>

    </div>
</form>


<div class="bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(15,23,42,0.05)] border border-slate-100 overflow-hidden">
    <div class="flex justify-between items-center px-8 py-6 border-b border-slate-100 bg-white">
        <div>
            <h2 class="text-xl font-black text-navy tracking-tight flex items-center gap-2">
                <div class="p-2 bg-navy/5 rounded-xl text-navy">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </div>
                All Journal Entries
            </h2>
            <p class="text-slate-500 text-sm mt-1 ml-11">
                <?php if(method_exists($entries, 'total')): ?>
                    <?php echo e($entries->total()); ?> total transactions
                <?php else: ?>
                    <?php echo e($entries->count()); ?> total transactions
                <?php endif; ?>
            </p>
        </div>
        <a href="<?php echo e(route('ledger.create')); ?>" class="bg-navy hover:bg-navy-700 text-white px-6 py-2.5 rounded-2xl font-semibold shadow-[0_4px_10px_rgba(22,38,91,0.2)] hover:shadow-[0_6px_15px_rgba(22,38,91,0.3)] transition-all active:scale-95 flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> <span class="hidden sm:inline">New Entry</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50/80 text-xs uppercase font-extrabold tracking-wider text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5">Date</th>
                    <th class="px-8 py-5">Reference</th>
                    <th class="px-8 py-5">Description</th>
                    <th class="px-8 py-5">Account</th>
                    <th class="px-8 py-5 text-right">Debit</th>
                    <th class="px-8 py-5 text-right">Credit</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $__currentLoopData = $entry->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50 transition-colors duration-200 group">
                            <td class="px-8 py-5 whitespace-nowrap text-slate-500 font-medium"><?php echo e(\Carbon\Carbon::parse($entry->entry_date)->format('Y-m-d')); ?></td>
                            <td class="px-8 py-5 whitespace-nowrap font-bold text-navy"><?php echo e($entry->reference); ?></td>
                            <td class="px-8 py-5 font-medium text-slate-700"><?php echo e($entry->description); ?></td>
                            <td class="px-8 py-5 font-semibold text-slate-600">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                    <?php echo e($line->account->account_name); ?>

                                </div>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-brand-blue">
                                <?php if($line->debit > 0): ?> ₱<?php echo e(number_format($line->debit, 2)); ?> <?php else: ?> - <?php endif; ?>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-brand-red">
                                <?php if($line->credit > 0): ?> ₱<?php echo e(number_format($line->credit, 2)); ?> <?php else: ?> - <?php endif; ?>
                            </td>
                            <td class="px-8 py-5 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo e(route('ledger.show', $entry->id)); ?>" class="text-brand-blue hover:text-white hover:bg-brand-blue transition-all bg-brand-blue/10 w-9 h-9 flex items-center justify-center rounded-full">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="<?php echo e(route('ledger.edit', $entry->id)); ?>" class="text-brand-orange hover:text-white hover:bg-brand-orange transition-all bg-brand-orange/10 w-9 h-9 flex items-center justify-center rounded-full">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form action="<?php echo e(route('ledger.delete', $entry->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button onclick="return confirm('Delete this entry?')" class="text-brand-red hover:text-white hover:bg-brand-red transition-all bg-brand-red/10 w-9 h-9 flex items-center justify-center rounded-full">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-16 text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 flex items-center justify-center rounded-full mb-4">
                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                                </div>
                                <p class="text-lg font-bold text-slate-600">No Journal Entries Found</p>
                                <p class="text-sm mt-1">Try adjusting your filters or search terms.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if(method_exists($entries, 'links') && $entries->hasPages()): ?>
        <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
            <?php echo e($entries->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/general-ledger/all-journal.blade.php ENDPATH**/ ?>