
<div class="flex flex-col sm:flex-row sm:flex-wrap gap-3 mb-6 no-print">
    <button type="button" onclick="AppUI.openGenerateReportModal()"
        class="flex items-center justify-center sm:justify-start gap-2 rounded-xl bg-navy px-5 py-3 text-sm font-semibold text-white shadow-card hover:bg-navy-700 transition w-full sm:w-auto">
        <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Generate Reports
    </button>
    <button type="button" onclick="AppUI.openExportPdfModal()"
        class="flex items-center justify-center sm:justify-start gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-card hover:bg-slate-50 transition w-full sm:w-auto">
        <i data-lucide="file-text" class="w-4 h-4"></i> Export PDF
    </button>
    <button type="button" onclick="AppUI.openExportExcelModal()"
        class="flex items-center justify-center sm:justify-start gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-card hover:bg-slate-50 transition w-full sm:w-auto">
        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export Excel
    </button>
    <button type="button" onclick="AppUI.openAddAuditModal()"
        class="flex items-center justify-center sm:justify-start gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-card hover:bg-slate-50 transition w-full sm:w-auto">
        <i data-lucide="plus" class="w-4 h-4"></i> Add Audit
    </button>
</div>


<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6 bg-white rounded-2xl shadow-card p-6">
    <?php

        $hs = $headerStats ?? [
            'totalRevenue' => 0, 'totalRevenueTrend' => null,
            'netProfit' => 0, 'netProfitTrend' => null,
            'complianceScore' => 0, 'complianceLabel' => 'N/A',
            'pendingReports' => 0,
        ];

        $trendText = function (?float $pct) {
            if ($pct === null) {
                return 'No prior month data';
            }
            $sign = $pct > 0 ? '+' : '';
            return $sign . number_format($pct, 1) . '% vs Last Month';
        };

        $trendColor = function (?float $pct) {
            if ($pct === null) {
                return 'text-slate-400';
            }
            return $pct >= 0 ? 'text-brand-green' : 'text-brand-red';
        };

        $trendIcon = function (?float $pct) {
            if ($pct === null) {
                return null;
            }
            return $pct >= 0 ? 'arrow-up' : 'arrow-down';
        };

        $stats = [
            [
                'label' => 'Total Revenue',
                'value' => '₱' . number_format($hs['totalRevenue'], 0),
                'trend' => $trendText($hs['totalRevenueTrend']),
                'trendIcon' => $trendIcon($hs['totalRevenueTrend']),
                'trendColor' => $trendColor($hs['totalRevenueTrend']),
                'icon' => 'dollar-sign',
                'iconBg' => 'bg-brand-green',
            ],
            [
                'label' => 'Net Profit',
                'value' => '₱' . number_format($hs['netProfit'], 0),
                'trend' => $trendText($hs['netProfitTrend']),
                'trendIcon' => $trendIcon($hs['netProfitTrend']),
                'trendColor' => $trendColor($hs['netProfitTrend']),
                'icon' => 'trending-up',
                'iconBg' => 'bg-navy',
            ],
            [
                'label' => 'Compliance Score',
                'value' => $hs['complianceScore'] . '%',
                'trend' => $hs['complianceLabel'],
                'trendColor' => match (true) {
                    $hs['complianceScore'] >= 90 => 'text-brand-green',
                    $hs['complianceScore'] >= 75 => 'text-navy-600',
                    $hs['complianceScore'] >= 50 => 'text-brand-orange',
                    default => 'text-brand-red',
                },
                'icon' => 'shield-check',
                'iconBg' => 'bg-brand-orange',
                'badge' => true,
            ],
            [
                'label' => 'Pending Reports',
                'value' => $hs['pendingReports'] . ' ' . ($hs['pendingReports'] === 1 ? 'Report' : 'Reports'),
                'trend' => $hs['pendingReports'] > 0 ? 'Requires Attention' : 'All Clear',
                'trendColor' => $hs['pendingReports'] > 0 ? 'text-brand-orange' : 'text-brand-green',
                'icon' => 'file-clock',
                'iconBg' => 'bg-brand-red',
                'badge' => true,
            ],
        ];
      ?>

    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-start gap-4 <?php echo e($i > 0 ? 'sm:border-l sm:border-slate-100 sm:pl-6' : ''); ?>">
            <div
                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full <?php echo e($stat['iconBg']); ?> text-white shadow-md">
                <i data-lucide="<?php echo e($stat['icon']); ?>" class="w-6 h-6"></i>
            </div>
            <div>
                <div class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">
                    <?php echo e($stat['label']); ?>

                </div>
                <p class="text-2xl font-extrabold text-navy mt-1"><?php echo e($stat['value']); ?></p>

                <?php if(!empty($stat['badge'])): ?>
                    <span
                        class="inline-block mt-1.5 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold <?php echo e($stat['trendColor']); ?>">
                        <?php echo e($stat['trend']); ?>

                    </span>
                <?php else: ?>
                    <p class="flex items-center gap-1 text-xs font-semibold <?php echo e($stat['trendColor']); ?> mt-1">
                        <?php if(!empty($stat['trendIcon'])): ?>
                            <i data-lucide="<?php echo e($stat['trendIcon']); ?>" class="w-3.5 h-3.5"></i>
                        <?php endif; ?>
                        <?php echo e($stat['trend']); ?>

                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="flex flex-wrap gap-3 mb-6 no-print">
    <?php
        $tabs = [
            ['label' => 'Overview', 'route' => 'financial-reports.overview'],
            ['label' => 'Income & Balance', 'route' => 'financial-reports.income-balance'],
            ['label' => 'Cash Flow & Tax', 'route' => 'financial-reports.cashflow-tax'],
        ];
      ?>

    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $tabRouteExists = $tab['route'] && \Illuminate\Support\Facades\Route::has($tab['route']);
            $isActive = $tabRouteExists && request()->routeIs($tab['route']);
        ?>
        <?php if($tabRouteExists): ?>
            <a href="<?php echo e(route($tab['route'])); ?>"
                class="rounded-xl px-5 py-2.5 text-sm font-semibold border transition <?php echo e($isActive ? 'bg-navy text-white border-navy shadow-card' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'); ?>">
                <?php echo e($tab['label']); ?>

            </a>
        <?php else: ?>
            <button type="button" disabled title="Coming soon"
                class="rounded-xl px-5 py-2.5 text-sm font-semibold border bg-white text-slate-300 border-slate-100 cursor-not-allowed">
                <?php echo e($tab['label']); ?>

            </button>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Store permission based on the active role session natively available in blade
        window.canManageFinancialReports = <?php echo json_encode(\App\Models\Role::activeRoleCanManageFinancialReports(), 15, 512) ?>;

        window.AppUI = window.AppUI || {};

        // Helper check function that triggers the pop-up warning
        AppUI.requirePermission = function() {
            if (!window.canManageFinancialReports) {
                AppUI.openModal(`
                    <div class="text-center py-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 text-brand-red mx-auto flex items-center justify-center mb-3">
                            <i data-lucide="shield-alert" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-navy mb-2">Access Denied</h3>
                        <p class="text-sm text-slate-500 mb-5">You don't have permission for this action.</p>
                        <div class="flex justify-center">
                            <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-6 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Understood</button>
                        </div>
                    </div>
                `, 'sm');
                if (typeof lucide !== 'undefined') lucide.createIcons();
                return false;
            }
            return true;
        };

        Object.assign(AppUI, (function () {
            function openGenerateReportModal() {
                if (!AppUI.requirePermission()) return;
                
                const reportSections = [
                    ['cover', 'Cover Page & Summary'],
                    ['statements', 'Financial Statements'],
                    ['notes', 'Notes to Financial Statements'],
                    ['ratios', 'Key Ratios & KPIs'],
                    ['compliance', 'Compliance Checklist'],
                    ['appendix', 'Supporting Appendix'],
                ];

                AppUI.openModal(`
        <h3 class="text-lg font-bold text-navy mb-1">Generate Report</h3>
        <p class="text-sm text-slate-500 mb-5">Configure the report, then generate it as a downloadable file.</p>

        <form id="generate-report-form" class="space-y-5">

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Report Type <span class="text-brand-red">*</span></label>
            <select name="report_type" required
                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
              <option value="income_statement">Income Statement</option>
              <option value="balance_sheet">Balance Sheet</option>
              <option value="cash_flow">Cash Flow Statement</option>
              <option value="tax_summary">Tax Summary</option>
              <option value="budget_actual">Budget vs Actual</option>
              <option value="full_compliance">Full Compliance Report</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Period</label>
            <div class="flex gap-4 text-sm text-slate-600 mb-3">
              <label class="flex items-center gap-2"><input type="radio" name="period_mode" value="month" class="js-period-mode" checked> Single Month</label>
              <label class="flex items-center gap-2"><input type="radio" name="period_mode" value="range" class="js-period-mode"> Date Range</label>
              <label class="flex items-center gap-2"><input type="radio" name="period_mode" value="ytd" class="js-period-mode"> Year to Date</label>
            </div>

            <div id="period-month-fields" class="grid grid-cols-2 gap-3">
              <select name="month" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                ${['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
                        .map((m, i) => `<option value="${i + 1}" ${i === 5 ? 'selected' : ''}>${m}</option>`).join('')}
              </select>
              <select name="year" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option selected>2026</option>
              </select>
            </div>

            <div id="period-range-fields" class="hidden grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">From</label>
                <input type="date" name="date_from" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
              </div>
              <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">To</label>
                <input type="date" name="date_to" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
              </div>
            </div>

            <div id="period-ytd-fields" class="hidden">
              <select name="ytd_year" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option>2024</option><option>2025</option><option selected>2026</option>
              </select>
            </div>
          </div>

          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-sm font-semibold text-slate-700">Include Sections</label>
              <button type="button" id="sections-toggle-all" class="text-xs font-semibold text-navy-600 hover:underline">Select all</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
              ${reportSections.map(([value, label], i) => `
                <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">
                  <input type="checkbox" name="sections[]" value="${value}" class="js-section-check" ${i < 2 ? 'checked' : ''}>
                  ${label}
                </label>
              `).join('')}
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Format</label>
              <div class="flex flex-wrap gap-4 text-sm text-slate-600">
                <label class="flex items-center gap-2"><input type="radio" name="format" value="pdf" checked> PDF</label>
                <label class="flex items-center gap-2"><input type="radio" name="format" value="excel"> Excel</label>
                <label class="flex items-center gap-2"><input type="radio" name="format" value="both"> Both</label>
              </div>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Delivery</label>
              <div class="flex flex-wrap gap-4 text-sm text-slate-600">
                <label class="flex items-center gap-2"><input type="radio" name="delivery" value="download" class="js-delivery" checked> Download now</label>
                <label class="flex items-center gap-2"><input type="radio" name="delivery" value="email" class="js-delivery"> Email it</label>
              </div>
            </div>
          </div>

          <div id="delivery-email-field" class="hidden">
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Recipient Email(s)</label>
            <input type="text" name="recipients" placeholder="finance@company.com, cfo@company.com"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
            <p class="text-xs text-slate-400 mt-1">Separate multiple addresses with commas.</p>
          </div>

          <p id="generate-report-error" class="text-sm text-brand-red hidden"></p>

          <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
            <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel</button>
            <button type="submit" id="generate-report-submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Generate Report</button>
          </div>
        </form>
      `, 'lg');

                const form = document.getElementById('generate-report-form');

                const periodGroups = {
                    month: document.getElementById('period-month-fields'),
                    range: document.getElementById('period-range-fields'),
                    ytd: document.getElementById('period-ytd-fields'),
                };
                form.querySelectorAll('.js-period-mode').forEach((radio) => {
                    radio.addEventListener('change', () => {
                        Object.entries(periodGroups).forEach(([key, el]) => {
                            el.classList.toggle('hidden', key !== radio.value);
                        });
                    });
                });

                const emailField = document.getElementById('delivery-email-field');
                form.querySelectorAll('.js-delivery').forEach((radio) => {
                    radio.addEventListener('change', () => {
                        emailField.classList.toggle('hidden', radio.value !== 'email');
                        const input = emailField.querySelector('input');
                        input.required = radio.value === 'email';
                    });
                });

                const sectionChecks = form.querySelectorAll('.js-section-check');
                const toggleAllBtn = document.getElementById('sections-toggle-all');
                toggleAllBtn.addEventListener('click', () => {
                    const allChecked = Array.from(sectionChecks).every((c) => c.checked);
                    sectionChecks.forEach((c) => (c.checked = !allChecked));
                    toggleAllBtn.textContent = allChecked ? 'Select all' : 'Clear all';
                });

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const errorEl = document.getElementById('generate-report-error');
                    const checkedSections = form.querySelectorAll('.js-section-check:checked');
                    const deliveryEmail = form.querySelector('input[name="delivery"]:checked').value === 'email';
                    const recipients = form.recipients.value.trim();

                    if (checkedSections.length === 0) {
                        errorEl.textContent = 'Select at least one section to include in the report.';
                        errorEl.classList.remove('hidden');
                        return;
                    }
                    if (deliveryEmail && !recipients) {
                        errorEl.textContent = 'Enter at least one recipient email address.';
                        errorEl.classList.remove('hidden');
                        return;
                    }
                    errorEl.classList.add('hidden');

                    const btn = document.getElementById('generate-report-submit');
                    btn.disabled = true;
                    btn.textContent = 'Generating…';

                    const payload = Object.fromEntries(new FormData(form).entries());
                    payload.sections = Array.from(checkedSections).map((c) => c.value);

                    setTimeout(() => {
                        AppUI.closeModal();
                        AppUI.showToast(
                            payload.delivery === 'email'
                                ? `Report generated and sent to ${payload.recipients}.`
                                : 'Report generated — your download will start shortly.',
                            'success'
                        );

                        if (payload.delivery !== 'email') {
                            const blob = new Blob(
                                [`Report Type: ${payload.report_type}\nSections: ${payload.sections.join(', ')}\nFormat: ${payload.format}`],
                                { type: 'text/plain' }
                            );
                            const url = URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `${payload.report_type}-${new Date().toISOString().slice(0, 10)}.txt`;
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            URL.revokeObjectURL(url);
                        }
                    }, 700);
                });
            }

            function openExportPdfModal() {
                if (!AppUI.requirePermission()) return;
                
                AppUI.openModal(`
        <h3 class="text-lg font-bold text-navy mb-1">Export as PDF</h3>
        <p class="text-sm text-slate-500 mb-5">
          Uses your browser's print engine — choose <b>"Save as PDF"</b> as the destination when the print dialog opens.
        </p>

        <form id="export-pdf-form" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Page Size</label>
              <select name="page_size" id="pdf-page-size" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="A4">A4</option>
                <option value="Letter" selected>Letter</option>
                <option value="Legal">Legal</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Orientation</label>
              <select name="orientation" id="pdf-orientation" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="portrait" selected>Portrait</option>
                <option value="landscape">Landscape</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Include</label>
            <div class="space-y-2 text-sm text-slate-600">
              <label class="flex items-center gap-2"><input type="checkbox" name="include_charts" checked> Charts & graphs</label>
              <label class="flex items-center gap-2"><input type="checkbox" name="include_tables" checked> Data tables</label>
              <label class="flex items-center gap-2"><input type="checkbox" name="include_notes"> Notes & annotations</label>
              <label class="flex items-center gap-2"><input type="checkbox" name="include_watermark"> "Confidential" watermark</label>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-2">
            <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel</button>
            <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Continue to Print</button>
          </div>
        </form>
      `, 'sm');

                document.getElementById('export-pdf-form').addEventListener('submit', function (e) {
                    e.preventDefault();
                    confirmExportPdf(new FormData(e.target));
                });
            }

            function confirmExportPdf(formData) {
                const pageSize = formData ? formData.get('page_size') : 'Letter';
                const orientation = formData ? formData.get('orientation') : 'portrait';
                const includeCharts = formData ? formData.get('include_charts') : true;
                const includeTables = formData ? formData.get('include_tables') : true;
                const watermark = formData ? formData.get('include_watermark') : false;

                const styleTag = document.createElement('style');
                styleTag.id = 'pdf-print-overrides';
                styleTag.textContent = `
          @page { size: ${pageSize} ${orientation}; margin: 0.6in; }
          @media print {
            ${includeCharts ? '' : 'canvas, .js-chart { display: none !important; }'}
            ${includeTables ? '' : 'table, .js-exportable-table { display: none !important; }'}
          }
        `;
                document.head.appendChild(styleTag);

                let watermarkEl = null;
                if (watermark) {
                    watermarkEl = document.createElement('div');
                    watermarkEl.textContent = 'CONFIDENTIAL';
                    watermarkEl.style.cssText = `
            position: fixed; top: 45%; left: 0; right: 0; text-align: center;
            font-size: 72px; font-weight: 800; color: rgba(22,38,91,0.12);
            transform: rotate(-25deg); z-index: 9999; pointer-events: none;
          `;
                    watermarkEl.className = 'no-print-remove-after';
                    document.body.appendChild(watermarkEl);
                }

                AppUI.closeModal();
                setTimeout(() => {
                    window.print();
                    setTimeout(() => {
                        styleTag.remove();
                        if (watermarkEl) watermarkEl.remove();
                    }, 500);
                    AppUI.showToast('PDF export ready — check your print dialog.', 'success');
                }, 150);
            }

            function openExportExcelModal() {
                if (!AppUI.requirePermission()) return;
                
                let tables = document.querySelectorAll('.js-exportable-table');

                if (tables.length === 0) {
                    tables = document.querySelectorAll('table:not(template table)');
                    tables = Array.from(tables).filter((t) => t.closest('#app-modal-box') === null);
                }

                const tableCount = tables.length;

                const tableLabel = (table, i) => {
                    if (table.dataset.title) return table.dataset.title;
                    const heading = table.closest('section, div')?.querySelector('h1,h2,h3,h4');
                    return heading ? heading.textContent.trim() : `Table ${i + 1}`;
                };

                const tableCheckboxes = Array.from(tables).map((table, i) => `
          <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">
            <input type="checkbox" class="js-excel-table-check" data-index="${i}" checked>
            ${tableLabel(table, i)}
          </label>
        `).join('');

                AppUI.openModal(`
        <h3 class="text-lg font-bold text-navy mb-1">Export as Excel (CSV)</h3>
        <p class="text-sm text-slate-500 mb-5">
          ${tableCount > 0
                        ? `Found ${tableCount} exportable table${tableCount > 1 ? 's' : ''} on this page. Choose what to include — each downloads as a .csv that opens directly in Excel.`
                        : `No tables were found on this page to export.`}
        </p>

        ${tableCount > 0 ? `
        <form id="export-excel-form" class="space-y-4">
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-sm font-semibold text-slate-700">Tables to Include</label>
              <button type="button" id="excel-toggle-all" class="text-xs font-semibold text-navy-600 hover:underline">Clear all</button>
            </div>
            <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
              ${tableCheckboxes}
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Output</label>
            <div class="flex flex-wrap gap-4 text-sm text-slate-600">
              <label class="flex items-center gap-2"><input type="radio" name="excel_output" value="single" checked> One combined CSV</label>
              <label class="flex items-center gap-2"><input type="radio" name="excel_output" value="separate"> Separate file per table</label>
            </div>
          </div>

          <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="include_headers" checked> Include column headers
          </label>

          <p id="export-excel-error" class="text-sm text-brand-red hidden"></p>

          <div class="flex justify-end gap-3 pt-2">
            <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel</button>
            <button type="submit" id="export-excel-submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Download CSV</button>
          </div>
        </form>
        ` : `
        <div class="flex justify-end gap-3">
          <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Close</button>
        </div>
        `}
      `, 'sm');

                if (tableCount === 0) return;

                AppUI._excelExportTables = tables;

                const checks = document.querySelectorAll('.js-excel-table-check');
                const toggleAllBtn = document.getElementById('excel-toggle-all');
                toggleAllBtn.addEventListener('click', () => {
                    const allChecked = Array.from(checks).every((c) => c.checked);
                    checks.forEach((c) => (c.checked = !allChecked));
                    toggleAllBtn.textContent = allChecked ? 'Select all' : 'Clear all';
                });

                document.getElementById('export-excel-form').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const errorEl = document.getElementById('export-excel-error');
                    const selectedIndexes = Array.from(checks).filter((c) => c.checked).map((c) => Number(c.dataset.index));

                    if (selectedIndexes.length === 0) {
                        errorEl.textContent = 'Select at least one table to export.';
                        errorEl.classList.remove('hidden');
                        return;
                    }
                    errorEl.classList.add('hidden');

                    const includeHeaders = this.include_headers.checked;
                    const outputMode = this.excel_output.value;

                    confirmExportExcel(selectedIndexes, includeHeaders, outputMode);
                });
            }

            function csvEscape(value) {
                const str = String(value ?? '').trim();
                return /[",\n]/.test(str) ? `"${str.replace(/"/g, '""')}"` : str;
            }

            function tableToCsv(table, includeHeaders) {
                let csv = '';
                const rows = Array.from(table.querySelectorAll('tr'));
                rows.forEach((tr) => {
                    const isHeaderRow = tr.querySelectorAll('th').length > 0;
                    if (isHeaderRow && !includeHeaders) return;
                    const cells = Array.from(tr.querySelectorAll('th, td')).map((cell) => csvEscape(cell.innerText));
                    if (cells.length) csv += cells.join(',') + '\n';
                });
                return csv;
            }

            function downloadBlob(content, filename) {
                const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            }

            function confirmExportExcel(selectedIndexes, includeHeaders = true, outputMode = 'single') {
                const tables = AppUI._excelExportTables || document.querySelectorAll('.js-exportable-table');
                const dateStamp = new Date().toISOString().slice(0, 10);

                if (outputMode === 'separate') {
                    selectedIndexes.forEach((i) => {
                        const csv = tableToCsv(tables[i], includeHeaders);
                        downloadBlob(csv, `financial-report-table-${i + 1}-${dateStamp}.csv`);
                    });
                } else {
                    let csv = '';
                    selectedIndexes.forEach((i, pos) => {
                        if (pos > 0) csv += '\n';
                        csv += tableToCsv(tables[i], includeHeaders);
                    });
                    downloadBlob(csv, `financial-report-${dateStamp}.csv`);
                }

                AppUI.closeModal();
                AppUI.showToast(
                    selectedIndexes.length > 1
                        ? `${selectedIndexes.length} tables exported to CSV.`
                        : 'CSV file downloaded.',
                    'success'
                );
            }

            function openAddAuditModal() {
                if (!AppUI.requirePermission()) return;
                
                AppUI.openModal(`
        <h3 class="text-lg font-bold text-navy mb-1">Add Audit</h3>
        <p class="text-sm text-slate-500 mb-5">Schedule a new compliance audit and its follow-up checklist.</p>

        <form id="add-audit-form" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Audit Name <span class="text-brand-red">*</span></label>
            <input type="text" name="name" required placeholder="e.g. Q3 Internal Controls Review"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Type</label>
              <select name="type" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option>Internal</option>
                <option>External</option>
                <option>Regulatory</option>
                <option>Financial</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Priority</label>
              <select name="priority" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Scheduled Date <span class="text-brand-red">*</span></label>
              <input type="date" name="date" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Recurrence</label>
              <select name="recurrence" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                <option value="none" selected>One-time</option>
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="annually">Annually</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Assigned To</label>
            <input type="text" name="assigned_to" placeholder="e.g. Harvie Marcelo"
                   class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Checklist Items</label>
            <div id="audit-checklist-rows" class="space-y-2">
              <div class="flex gap-2">
                <input type="text" name="checklist[]" placeholder="e.g. Reconcile bank statements"
                       class="flex-1 rounded-xl border border-slate-200 px-3 py-2 text-sm">
                <button type="button" class="js-remove-checklist-row shrink-0 h-9 w-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50" title="Remove">
                  <i data-lucide="x" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
            <button type="button" id="audit-add-checklist-row" class="mt-2 flex items-center gap-1.5 text-xs font-semibold text-navy-600 hover:underline">
              <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add checklist item
            </button>
          </div>

          <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="notify" checked> Notify assignee &amp; send a reminder 3 days before
          </label>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Notes</label>
            <textarea name="notes" rows="3" placeholder="Optional scope or context…"
                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm resize-none"></textarea>
          </div>

          <p id="add-audit-error" class="text-sm text-brand-red hidden"></p>

          <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
            <button type="button" onclick="AppUI.closeModal()" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">Cancel</button>
            <button type="submit" id="add-audit-submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700">Save Audit</button>
          </div>
        </form>
      `, 'lg');

                const checklistRows = document.getElementById('audit-checklist-rows');

                function wireRemoveButton(row) {
                    row.querySelector('.js-remove-checklist-row').addEventListener('click', () => {
                        if (checklistRows.children.length > 1) {
                            row.remove();
                        } else {
                            row.querySelector('input').value = '';
                        }
                    });
                }
                checklistRows.querySelectorAll(':scope > div').forEach(wireRemoveButton);

                document.getElementById('audit-add-checklist-row').addEventListener('click', () => {
                    const row = document.createElement('div');
                    row.className = 'flex gap-2';
                    row.innerHTML = `
            <input type="text" name="checklist[]" placeholder="e.g. Review vendor contracts"
                   class="flex-1 rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <button type="button" class="js-remove-checklist-row shrink-0 h-9 w-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50" title="Remove">
              <i data-lucide="x" class="w-4 h-4"></i>
            </button>
          `;
                    checklistRows.appendChild(row);
                    wireRemoveButton(row);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                });

                document.getElementById('add-audit-form').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const form = e.target;
                    const errorEl = document.getElementById('add-audit-error');
                    const name = form.name.value.trim();
                    const date = form.date.value;

                    if (!name || !date) {
                        errorEl.textContent = 'Audit name and scheduled date are required.';
                        errorEl.classList.remove('hidden');
                        return;
                    }
                    errorEl.classList.add('hidden');

                    const btn = document.getElementById('add-audit-submit');
                    btn.disabled = true;
                    btn.textContent = 'Saving…';

                    const checklist = Array.from(form.querySelectorAll('input[name="checklist[]"]'))
                        .map((i) => i.value.trim())
                        .filter(Boolean);

                    const payload = {
                        name,
                        type: form.type.value,
                        priority: form.priority.value,
                        date,
                        recurrence: form.recurrence.value,
                        assigned_to: form.assigned_to.value.trim(),
                        checklist,
                        notify: form.notify.checked,
                        notes: form.notes.value.trim(),
                    };

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    fetch('<?php echo e(route('financial-reports.audits.store')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken ?? '',
                        },
                        body: JSON.stringify(payload),
                    })
                        .then(async (res) => {
                            if (!res.ok) {
                                const body = await res.json().catch(() => ({}));
                                const message = body.errors
                                    ? Object.values(body.errors).flat().join(' ')
                                    : (body.message || 'Failed to save audit.');
                                throw new Error(message);
                            }
                            return res.json();
                        })
                        .then(({ audit }) => {
                            AppUI.closeModal();
                            AppUI.showToast(`Audit "${audit.auditType}" scheduled for ${audit.date}${payload.recurrence !== 'none' ? ` (${payload.recurrence})` : ''}.`, 'success');

                            AppUI.onAuditCreated?.(audit);
                        })
                        .catch((err) => {
                            btn.disabled = false;
                            btn.textContent = 'Save Audit';
                            errorEl.textContent = err.message;
                            errorEl.classList.remove('hidden');
                        });
                });
            }

            return {
                openGenerateReportModal,
                openExportPdfModal, confirmExportPdf,
                openExportExcelModal, confirmExportExcel,
                openAddAuditModal,
            };
        })());
    </script>
<?php $__env->stopPush(); ?><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/financial-reports/header.blade.php ENDPATH**/ ?>