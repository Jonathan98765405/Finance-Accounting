<?php $__env->startSection('page-title', 'Finance & Accounting | Record Supplier Invoice'); ?>
<?php $__env->startSection('page-title-heading', 'Record Incoming Supplier Invoice'); ?>
<?php $__env->startSection('page-subtitle', 'Record supplier invoices before verification and payment processing.'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-6">

    <?php if(session('success')): ?>
        <div class="flex items-center gap-2.5 rounded-xl bg-emerald-50 border border-brand-green/30 text-emerald-800 p-4 text-sm font-medium shadow-sm">
            <i data-lucide="circle-check-big" class="w-5 h-5 text-brand-green"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="rounded-xl bg-red-50 border border-brand-red/20 text-brand-red p-4 text-sm font-medium shadow-sm">
            <div class="flex items-center gap-2 mb-2 font-bold text-red-800">
                <i data-lucide="circle-alert" class="w-5 h-5 text-brand-red"></i>
                <span>Please fix the following issues:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs text-red-700">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('ap.record.store')); ?>" id="invoiceForm" class="space-y-6">
        <?php echo csrf_field(); ?>

        <div class="bg-white rounded-2xl shadow-card p-6">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-1 h-4 rounded-full bg-brand-green"></span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-brand-green">Supplier Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                
                <div class="lg:col-span-2 flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider" for="supplierSelect">
                        Supplier Name <span class="text-brand-red">*</span>
                    </label>
                    <select class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition" 
                            name="supplier_id" id="supplierSelect" required>
                        <option value="" selected disabled>Select Supplier</option>
                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($supplier->id); ?>" <?php echo e(old('supplier_id') == $supplier->id ? 'selected' : ''); ?>>
                                <?php echo e($supplier->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Invoice Number <span class="text-brand-red">*</span>
                    </label>
                    <input type="text" 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition"
                           name="invoice_number" 
                           id="invoiceNumberInput"
                           value="<?php echo e(old('invoice_number')); ?>" 
                           placeholder="INV-2026-001" 
                           required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Invoice Date <span class="text-brand-red">*</span>
                    </label>
                    <input type="date" 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition"
                           name="invoice_date" 
                           value="<?php echo e(old('invoice_date')); ?>" 
                           required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Due Date <span class="text-brand-red">*</span>
                    </label>
                    <input type="date" 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition"
                           name="due_date" 
                           value="<?php echo e(old('due_date')); ?>" 
                           required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex justify-between items-center">
                        <span>Purchase Order No.</span>
                        <a href="<?php echo e(route('ap.po.create')); ?>" class="text-[11px] text-brand-blue hover:text-navy font-bold normal-case inline-flex items-center gap-0.5 transition">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> New PO
                        </a>
                    </label>
                    <select class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition" 
                            name="po_number" id="poSelect">
                        <option value="" <?php echo e(old('po_number') ? '' : 'selected'); ?>>No PO / Not applicable</option>
                        <?php $__currentLoopData = $purchaseOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($po->po_number); ?>"
                                    data-supplier-id="<?php echo e($po->supplier_id); ?>"
                                    <?php echo e(old('po_number') == $po->po_number ? 'selected' : ''); ?>>
                                <?php echo e($po->po_number); ?> &mdash; <?php echo e($po->supplier->name); ?> (₱<?php echo e(number_format($po->total_amount, 2)); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-[10px] text-slate-400 mt-0.5 leading-normal" id="poHelpText">Select a supplier to see their purchase orders.</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Payment Terms
                    </label>
                    <select class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition" 
                            name="payment_terms">
                        <option value="Net 30" <?php echo e(old('payment_terms', 'Net 30') == 'Net 30' ? 'selected' : ''); ?>>Net 30</option>
                        <option value="Net 15" <?php echo e(old('payment_terms') == 'Net 15' ? 'selected' : ''); ?>>Net 15</option>
                        <option value="Net 45" <?php echo e(old('payment_terms') == 'Net 45' ? 'selected' : ''); ?>>Net 45</option>
                        <option value="Cash on Delivery" <?php echo e(old('payment_terms') == 'Cash on Delivery' ? 'selected' : ''); ?>>Cash on Delivery</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Currency
                    </label>
                    <select class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition" 
                            name="currency">
                        <option value="PHP" <?php echo e(old('currency', 'PHP') == 'PHP' ? 'selected' : ''); ?>>PHP</option>
                        <option value="USD" <?php echo e(old('currency') == 'USD' ? 'selected' : ''); ?>>USD</option>
                        <option value="EUR" <?php echo e(old('currency') == 'EUR' ? 'selected' : ''); ?>>EUR</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Invoice Status
                    </label>
                    <input type="text" 
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-400 font-medium cursor-not-allowed" 
                           value="Pending Verification" 
                           disabled>
                    <p class="text-[10px] text-slate-400 mt-0.5 leading-normal">New invoices always start as Pending Verification.</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Department
                    </label>
                    <input type="text" 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition"
                           name="department" 
                           value="<?php echo e(old('department')); ?>" 
                           placeholder="Finance Department">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Supplier Reference
                    </label>
                    <input type="text" 
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition"
                           name="supplier_reference" 
                           value="<?php echo e(old('supplier_reference')); ?>" 
                           placeholder="Reference Number">
                </div>

            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full bg-brand-green"></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-brand-green">Invoice Details</h3>
                </div>
                <button type="button" id="addItemBtn" 
                        class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-navy-600 hover:bg-navy-700 text-white px-4 py-2.5 text-sm font-semibold shadow-sm transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] uppercase tracking-wider text-slate-400 font-semibold">
                            <th class="py-3 px-4" style="width: 45%;">Description</th>
                            <th class="py-3 px-4 text-center" style="width: 12%;">Quantity</th>
                            <th class="py-3 px-4 text-right" style="width: 18%;">Unit Price (₱)</th>
                            <th class="py-3 px-4 text-right" style="width: 18%;">Amount (₱)</th>
                            <th class="py-3 px-4 text-center" style="width: 7%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody" class="divide-y divide-slate-100">
                        <?php for($i = 0; $i < 3; $i++): ?>
                            <tr class="hover:bg-slate-50/20 transition">
                                <td class="py-3.5 px-4">
                                    <input type="text" 
                                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition item-desc"
                                           name="items[<?php echo e($i); ?>][description]" 
                                           placeholder="Item Description">
                                </td>
                                <td class="py-3.5 px-4">
                                    <input type="number" 
                                           class="w-20 mx-auto rounded-lg border border-slate-200 px-3 py-2 text-sm text-center text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition item-qty"
                                           name="items[<?php echo e($i); ?>][quantity]" 
                                           value="1" 
                                           min="0" 
                                           step="1">
                                </td>
                                <td class="py-3.5 px-4">
                                    <input type="number" 
                                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition item-price"
                                           name="items[<?php echo e($i); ?>][unit_price]" 
                                           placeholder="0.00" 
                                           min="0" 
                                           step="0.01">
                                </td>
                                <td class="py-3.5 px-4">
                                    <input type="number" 
                                           class="w-full rounded-lg border-0 bg-slate-50 px-3 py-2 text-sm text-right text-slate-500 font-semibold cursor-not-allowed item-amount"
                                           placeholder="0.00" 
                                           readonly>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button type="button" 
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-brand-red hover:bg-red-50 hover:border-brand-red/30 remove-item transition">
                                        <i data-lucide="trash" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl shadow-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-1 h-4 rounded-full bg-brand-green"></span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-brand-green">Remarks</h3>
                    </div>
                    <textarea class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition"
                              name="remarks" 
                              rows="4" 
                              placeholder="Enter additional notes or remarks about this invoice..."><?php echo e(old('remarks')); ?></textarea>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1 h-4 rounded-full bg-brand-green"></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-brand-green">Financial Summary</h3>
                </div>

                <div class="space-y-3.5 mt-2">
                    <div class="flex justify-between items-center py-1">
                        <span class="text-sm text-slate-500 font-medium">Subtotal</span>
                        <span class="text-sm font-semibold text-navy" id="subtotalDisplay">₱0.00</span>
                    </div>

                    <div class="flex justify-between items-center py-1">
                        <span class="text-sm text-slate-500 font-medium">VAT (12%)</span>
                        <span class="text-sm font-semibold text-navy" id="vatDisplay">₱0.00</span>
                    </div>

                    <div class="flex justify-between items-center py-1">
                        <span class="text-sm text-slate-500 font-medium">Discount</span>
                        <span class="text-sm font-semibold text-navy">₱0.00</span>
                    </div>

                    <hr class="border-slate-100 my-2">

                    <div class="flex justify-between items-center pt-1">
                        <span class="font-bold text-navy text-base">TOTAL</span>
                        <span class="font-bold text-xl text-brand-greenDark" id="totalDisplay">₱0.00</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex items-center justify-end gap-3.5 pt-4">
            <button type="reset" 
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 hover:text-slate-800 px-5 py-3 text-sm font-semibold shadow-sm transition">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Reset
            </button>

            <button type="submit" 
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-brand-green hover:bg-brand-greenDark text-white px-5 py-3 text-sm font-semibold shadow-sm transition">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Invoice
            </button>
        </div>

    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    let itemIndex = document.querySelectorAll('#itemsBody tr').length;

    function recalcRow(row) {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        row.querySelector('.item-amount').value = (qty * price).toFixed(2);
        recalcTotals();
    }

    function formatPeso(amount) {
        return '₱' + amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalcTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-amount').forEach(function (el) {
            subtotal += parseFloat(el.value) || 0;
        });
        const vat = subtotal * 0.12;
        const total = subtotal + vat;

        document.getElementById('subtotalDisplay').textContent = formatPeso(subtotal);
        document.getElementById('vatDisplay').textContent = formatPeso(vat);
        document.getElementById('totalDisplay').textContent = formatPeso(total);
    }

    function bindRow(row) {
        row.querySelectorAll('.item-qty, .item-price').forEach(function (input) {
            input.addEventListener('input', function () {
                recalcRow(row);
            });
        });

        const removeBtn = row.querySelector('.remove-item');
        removeBtn.addEventListener('click', function () {
            if (document.querySelectorAll('#itemsBody tr').length > 1) {
                row.remove();
                recalcTotals();
            }
        });
    }

    document.querySelectorAll('#itemsBody tr').forEach(bindRow);

    document.getElementById('addItemBtn').addEventListener('click', function () {
        const tbody = document.getElementById('itemsBody');
        const row = document.createElement('tr');
        row.className = "hover:bg-slate-50/20 transition";

        row.innerHTML = `
            <td class="py-3.5 px-4">
                <input type="text" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition item-desc" name="items[${itemIndex}][description]" placeholder="Item Description">
            </td>
            <td class="py-3.5 px-4">
                <input type="number" class="w-20 mx-auto rounded-lg border border-slate-200 px-3 py-2 text-sm text-center text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition item-qty" name="items[${itemIndex}][quantity]" value="1" min="0" step="1">
            </td>
            <td class="py-3.5 px-4">
                <input type="number" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-right text-slate-800 focus:border-brand-blue focus:outline-none focus:ring-1 focus:ring-brand-blue transition item-price" name="items[${itemIndex}][unit_price]" placeholder="0.00" min="0" step="0.01">
            </td>
            <td class="py-3.5 px-4">
                <input type="number" class="w-full rounded-lg border-0 bg-slate-50 px-3 py-2 text-sm text-right text-slate-500 font-semibold cursor-not-allowed item-amount" placeholder="0.00" readonly>
            </td>
            <td class="py-3.5 px-4 text-center">
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-brand-red hover:bg-red-50 hover:border-brand-red/30 remove-item transition">
                    <i data-lucide="trash" class="w-4 h-4"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);
        bindRow(row);
        
        // Re-initialize dynamic Lucide icons for added row
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        itemIndex++;
    });

    recalcTotals();

    // ---------------- Filter the PO dropdown by selected supplier ----------------

    const supplierSelect = document.getElementById('supplierSelect');
    const poSelect = document.getElementById('poSelect');
    const poHelpText = document.getElementById('poHelpText');
    const invoiceNumberInput = document.getElementById('invoiceNumberInput');
    const poOptions = Array.from(poSelect.options).filter(opt => opt.value !== '');

    // ---------------- Mirror the Invoice Number to the selected PO number ----------------
    // The <option value> for a PO IS its po_number, so poSelect.value already
    // gives us the exact string to reuse as the invoice number.
    function syncInvoiceNumberWithPo() {
        if (poSelect.value) {
            invoiceNumberInput.value = poSelect.value;
            invoiceNumberInput.readOnly = true;
            invoiceNumberInput.classList.add('bg-slate-50', 'cursor-not-allowed');
        } else {
            invoiceNumberInput.readOnly = false;
            invoiceNumberInput.classList.remove('bg-slate-50', 'cursor-not-allowed');
        }
    }

    poSelect.addEventListener('change', syncInvoiceNumberWithPo);
    syncInvoiceNumberWithPo();

    function filterPoOptions() {
        const supplierId = supplierSelect.value;
        let visibleCount = 0;

        poOptions.forEach(function (opt) {
            const matches = !supplierId || opt.dataset.supplierId === supplierId;
            opt.hidden = !matches;
            opt.disabled = !matches;
            if (matches) visibleCount++;
        });

        // If the currently selected PO no longer belongs to this supplier, reset to "No PO"
        const selected = poSelect.selectedOptions[0];
        if (selected && selected.value !== '' && selected.hidden) {
            poSelect.value = '';
        }

        poHelpText.textContent = supplierId
            ? (visibleCount + ' PO(s) found for this supplier. Only POs recorded in the system can be matched later.')
            : 'Select a supplier to see their purchase orders. Only POs recorded in the system can be matched later.';
    }

    supplierSelect.addEventListener('change', filterPoOptions);
    filterPoOptions();
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/accounts-payable/record-invoice.blade.php ENDPATH**/ ?>