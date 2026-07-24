<?php $__env->startSection('page-title', 'Finance & Accounting | Dashboard'); ?>
<?php $__env->startSection('page-title-heading', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Monitor your financial performance and accounting activities in one place.'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="relative overflow-hidden rounded-2xl bg-navy text-white p-6 sm:p-8 mb-6 shadow-card">
        <div class="relative z-10 max-w-2xl">
            <h3 id="welcome-role" class="text-2xl sm:text-3xl font-extrabold mb-2">
                Welcome Back, <?php echo e(strtoupper(session('active_role_label', 'Administrator'))); ?>!
            </h3>
            <p class="text-slate-200 text-sm sm:text-base leading-relaxed">
                Here's a snapshot of our company's financial health for <?php echo e($currentQuarter ?? 'Q2 · FY 2026'); ?>.
                All systems are in good standing.
            </p>

            <div class="flex flex-wrap gap-3 mt-6 no-print">
                <?php if(Route::has('financial-reports.overview')): ?>
                    <a href="<?php echo e(route('financial-reports.overview')); ?>"
                        class="flex items-center gap-2 rounded-xl bg-brand-green px-5 py-3 text-sm font-semibold text-navy shadow-card hover:bg-brand-greenDark hover:text-white transition">
                        <i data-lucide="clipboard-check" class="w-4 h-4"></i> Financial Reports
                    </a>
                <?php endif; ?>
                <button type="button" onclick="AppUI.openExportSnapshotModal()"
                    class="flex items-center gap-2 rounded-xl bg-white/10 border border-white/20 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20 transition">
                    <i data-lucide="download" class="w-4 h-4"></i> Export Snapshot
                </button>
            </div>
        </div>
        <div class="pointer-events-none absolute -right-10 -bottom-16 w-64 h-64 rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute right-24 -top-16 w-40 h-40 rounded-full bg-white/5"></div>
    </div>

    
    <div class="grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5 mb-6">
        <?php
            $stats = [
                ['label' => 'Total Assets', 'value' => $totalAssets ?? '₱12,345,678', 'trend' => '8.5%', 'icon' => 'coins', 'iconBg' => 'bg-brand-green'],
                ['label' => 'Net Profit', 'value' => $netProfit ?? '₱12,345,678', 'trend' => '8%', 'icon' => 'trending-up', 'iconBg' => 'bg-navy'],
                ['label' => 'Cash on Hand', 'value' => $cashOnHand ?? '₱12,345,678', 'trend' => '8.5%', 'icon' => 'wallet', 'iconBg' => 'bg-brand-orange'],
                ['label' => 'Open Tasks', 'value' => $openTasks ?? '12', 'trend' => '8.5%', 'trendLabel' => 'Overdue', 'trendColor' => 'text-brand-red', 'icon' => 'pie-chart', 'iconBg' => 'bg-brand-red'],
            ];
        ?>

        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl shadow-card p-5 sm:p-6">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-full <?php echo e($stat['iconBg']); ?> text-white shadow-md">
                        <i data-lucide="<?php echo e($stat['icon']); ?>" class="w-5 h-5"></i>
                    </div>
                    <span class="flex items-center gap-1 text-xs font-semibold <?php echo e($stat['trendColor'] ?? 'text-brand-green'); ?>">
                        <i data-lucide="arrow-up" class="w-3.5 h-3.5"></i> <?php echo e($stat['trend']); ?> <?php echo e($stat['trendLabel'] ?? ''); ?>

                    </span>
                </div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1"><?php echo e($stat['label']); ?></p>
                <p class="text-xl sm:text-2xl font-extrabold text-navy"><?php echo e($stat['value']); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
        <?php
            $moduleHref = fn(string $name) => Route::has($name) ? route($name) : null;

            $modules = [
                [
                    'icon' => 'book-text',
                    'iconBg' => 'bg-brand-green/20',
                    'iconColor' => 'text-brand-greenDark',
                    'title' => 'General Ledger',
                    'subtitle' => 'Charts of accounts & journal entry',
                    'value' => $ledgerEntries ?? '12,480',
                    'footnote' => 'Entries this month',
                    'href' => $moduleHref('ledger.index'),
                ],
                [
                    'icon' => 'user',
                    'iconBg' => 'bg-brand-orange/20',
                    'iconColor' => 'text-brand-orange',
                    'title' => 'Account Receivable',
                    'subtitle' => 'Customer invoice & collection',
                    'value' => $accountReceivable ?? '₱320,000',
                    'footnote' => 'Outstanding',
                    'href' => $moduleHref('receivable.dashboard'),
                ],
                [
                    'icon' => 'wallet',
                    'iconBg' => 'bg-brand-red/20',
                    'iconColor' => 'text-brand-red',
                    'title' => 'Account Payable',
                    'subtitle' => 'Customer bills & payments',
                    'value' => $accountPayable ?? '₱210,000',
                    'footnote' => 'Due in 30 days',
                    'href' => $moduleHref('ap.dashboard'),
                ],
                [
                    'icon' => 'box',
                    'iconBg' => 'bg-brand-green/20',
                    'iconColor' => 'text-brand-greenDark',
                    'title' => 'Fixed Assets',
                    'subtitle' => 'Property, equipment, depreciation',
                    'value' => $fixedAssets ?? '₱1,250,000',
                    'footnote' => 'Net book value',
                    'href' => $moduleHref('fixed-assets.index'),
                ],
                [
                    'icon' => 'clipboard-check',
                    'iconBg' => 'bg-brand-blue/20',
                    'iconColor' => 'text-brand-blue',
                    'title' => 'Financial Reports',
                    'subtitle' => 'Statements & compliance',
                    'value' => $complianceScore ?? '98%',
                    'footnote' => 'Compliance score',
                    'href' => $moduleHref('financial-reports.overview'),
                ],
                [
                    'icon' => 'trending-up',
                    'iconBg' => 'bg-brand-orange/20',
                    'iconColor' => 'text-brand-orange',
                    'title' => 'Budget Forecasting',
                    'subtitle' => 'Budget vs. actual spend',
                    'value' => $budgetTotal ?? '₱12,480,000',
                    'footnote' => 'Total budget allocated',
                    'href' => $moduleHref('budget.view'),
                ],
            ];
        ?>

        <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($mod['href']): ?>
                <a href="<?php echo e($mod['href']); ?>"
                    class="group block bg-white rounded-2xl shadow-card p-5 sm:p-6 border border-transparent hover:border-navy-100 hover:shadow-lg transition">
                    <div class="flex items-start justify-between mb-5">
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-xl <?php echo e($mod['iconBg']); ?> <?php echo e($mod['iconColor']); ?>">
                            <i data-lucide="<?php echo e($mod['icon']); ?>" class="w-6 h-6"></i>
                        </div>
                        <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-300 group-hover:text-navy transition-colors"></i>
                    </div>
                    <h4 class="font-bold text-navy text-base sm:text-lg"><?php echo e($mod['title']); ?></h4>
                    <p class="text-slate-400 text-xs sm:text-sm mt-0.5"><?php echo e($mod['subtitle']); ?></p>
                    <p class="text-lg sm:text-xl font-extrabold text-navy mt-4"><?php echo e($mod['value']); ?></p>
                    <p class="text-slate-400 text-xs mt-0.5 uppercase tracking-wide"><?php echo e($mod['footnote']); ?></p>
                </a>
            <?php else: ?>
                <div
                    class="block bg-white rounded-2xl shadow-card p-5 sm:p-6 border border-transparent opacity-60 cursor-not-allowed">
                    <div class="flex items-start justify-between mb-5">
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-xl <?php echo e($mod['iconBg']); ?> <?php echo e($mod['iconColor']); ?>">
                            <i data-lucide="<?php echo e($mod['icon']); ?>" class="w-6 h-6"></i>
                        </div>
                    </div>
                    <h4 class="font-bold text-navy text-base sm:text-lg"><?php echo e($mod['title']); ?></h4>
                    <p class="text-slate-400 text-xs sm:text-sm mt-0.5"><?php echo e($mod['subtitle']); ?></p>
                    <p class="text-lg sm:text-xl font-extrabold text-navy mt-4"><?php echo e($mod['value']); ?></p>
                    <p class="text-slate-400 text-xs mt-0.5 uppercase tracking-wide"><?php echo e($mod['footnote']); ?> · route not set up</p>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/dashboard/dashboard.blade.php ENDPATH**/ ?>