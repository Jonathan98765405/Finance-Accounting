<?php $__env->startSection('page-title', 'Create Purchase Order'); ?>

<?php $__env->startSection('page-title-heading', 'Create Purchase Order'); ?>

<?php $__env->startSection('page-subtitle', 'Register a purchase order so future invoices and goods receipts can be matched against it.'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <?php if($errors->any()): ?>
        <div class="bg-red-50 border border-red-100 text-brand-red rounded-2xl p-4 shadow-sm flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
            <div>
                <strong class="font-bold text-sm block mb-1">Please fix the following:</strong>
                <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('ap.po.store')); ?>" id="poForm" class="space-y-6">
        <?php echo csrf_field(); ?>

        <div class="bg-white border border-slate-100 rounded-2xl shadow-card p-6 sm:p-8">
            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-navy-50 text-navy-600">
                    <i data-lucide="file-signature" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-navy">Purchase Order Details</h3>
                    <p class="text-slate-400 text-xs">Define general details and assign a supplier partner.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Supplier</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-xl border border-slate-200 bg-white py-2.5 pl-4 pr-10 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition" 
                                name="supplier_id" 
                                required>
                            <option value="" selected disabled>Select Supplier</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->id); ?>" <?php echo e(old('supplier_id') == $supplier->id ? 'selected' : ''); ?>>
                                    <?php echo e($supplier->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">PO Number</label>
                    <input
                        type="text"
                        class="w-full rounded-xl border border-slate-100 bg-slate-50 py-2.5 px-4 text-sm shadow-sm focus:outline-none text-slate-600 cursor-not-allowed"
                        name="po_number"
                        id="poNumberInput"
                        value="<?php echo e(old('po_number')); ?>"
                        readonly
                        required>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        Auto-generated. This same number is later reused as the GRN and Invoice number for this order.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">PO Date</label>
                    <input
                        type="date"
                        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 px-4 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition text-slate-700"
                        name="po_date"
                        value="<?php echo e(old('po_date')); ?>"
                        required>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl shadow-card p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-50 text-slate-600">
                        <i data-lucide="list-ordered" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-navy">Order Items</h3>
                        <p class="text-slate-400 text-xs">Add line items to calculate purchase order totals.</p>
                    </div>
                </div>
                <button type="button" 
                        id="addItemBtn" 
                        class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-semibold text-white bg-brand-greenDark hover:bg-brand-greenDark/90 shadow-sm transition self-start sm:self-auto">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70">
                            <th class="py-3 px-4 text-xs font-bold text-navy uppercase tracking-wider w-[35%]">Description</th>
                            <th class="py-3 px-4 text-xs font-bold text-navy uppercase tracking-wider w-[12%] text-center">Quantity</th>
                            <th class="py-3 px-4 text-xs font-bold text-navy uppercase tracking-wider w-[18%] text-right">Unit Price</th>
                            <th class="py-3 px-4 text-xs font-bold text-navy uppercase tracking-wider w-[18%] text-right">Amount</th>
                            <th class="py-3 px-4 text-xs font-bold text-navy uppercase tracking-wider w-[17%] text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody" class="divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50/30 transition">
                            <td class="py-4 px-4">
                                <input type="text" 
                                       class="item-desc w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition" 
                                       name="items[0][description]" 
                                       placeholder="Item Description">
                            </td>
                            <td class="py-4 px-4">
                                <input type="number" 
                                       class="item-qty w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition text-center" 
                                       name="items[0][quantity]" 
                                       value="1" 
                                       min="0" 
                                       step="1">
                            </td>
                            <td class="py-4 px-4">
                                <input type="number" 
                                       class="item-price w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition text-right" 
                                       name="items[0][unit_price]" 
                                       placeholder="0.00" 
                                       min="0" 
                                       step="0.01">
                            </td>
                            <td class="py-4 px-4">
                                <input type="number" 
                                       class="item-amount w-full rounded-lg border border-slate-100 bg-slate-50 py-2 px-3 text-sm text-right font-medium text-navy cursor-not-allowed" 
                                       placeholder="0.00" 
                                       readonly>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <button type="button" 
                                        class="remove-item inline-flex items-center justify-center h-9 w-9 rounded-lg border border-red-100 text-brand-red hover:bg-red-50 hover:border-red-200 transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <div class="lg:col-start-3 bg-white border border-slate-100 rounded-2xl shadow-card p-6">
                <h3 class="text-sm font-bold text-navy mb-4 pb-2 border-b border-slate-100">Financial Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-500">Subtotal</span>
                        <span class="font-semibold text-slate-800" id="subtotalDisplay">₱0.00</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-500">VAT (12%)</span>
                        <span class="font-semibold text-slate-800" id="vatDisplay">₱0.00</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-dashed border-slate-200">
                        <span class="text-sm font-bold text-navy">TOTAL</span>
                        <span class="text-lg font-bold text-brand-greenDark" id="totalDisplay">₱0.00</span>
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 leading-normal mt-4">
                    This total is what invoices against this PO will need to match.
                </p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mb-6">
            <a href="<?php echo e(route('ap.po.index')); ?>" 
               class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-200 bg-white hover:bg-slate-50 transition">
                Cancel
            </a>

            <button type="submit" 
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-navy hover:bg-navy-700 shadow-sm transition">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Purchase Order
            </button>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---------------- Auto-generate a randomized PO number ----------------
    // This number is reused verbatim as the GRN number (goods-receipt-create)
    // and the Invoice number (record-invoice) so all three documents for the
    // same order can be tied together at a glance.
    function generatePoNumber() {
        const year = new Date().getFullYear();
        const random = Math.random().toString(36).substring(2, 8).toUpperCase();
        return `PO-${year}-${random}`;
    }

    const poNumberInput = document.getElementById('poNumberInput');
    if (poNumberInput && !poNumberInput.value) {
        poNumberInput.value = generatePoNumber();
    }

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

        row.querySelector('.remove-item').addEventListener('click', function () {
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
        row.className = 'hover:bg-slate-50/30 transition';

        row.innerHTML = `
            <td class="py-4 px-4">
                <input type="text" class="item-desc w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition" name="items[${itemIndex}][description]" placeholder="Item Description">
            </td>
            <td class="py-4 px-4">
                <input type="number" class="item-qty w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition text-center" name="items[${itemIndex}][quantity]" value="1" min="0" step="1">
            </td>
            <td class="py-4 px-4">
                <input type="number" class="item-price w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-600/30 transition text-right" name="items[${itemIndex}][unit_price]" placeholder="0.00" min="0" step="0.01">
            </td>
            <td class="py-4 px-4">
                <input type="number" class="item-amount w-full rounded-lg border border-slate-100 bg-slate-50 py-2 px-3 text-sm text-right font-medium text-navy cursor-not-allowed" placeholder="0.00" readonly>
            </td>
            <td class="py-4 px-4 text-center">
                <button type="button" class="remove-item inline-flex items-center justify-center h-9 w-9 rounded-lg border border-red-100 text-brand-red hover:bg-red-50 hover:border-red-200 transition">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);
        
        // Re-init lucide icons on the newly appended trash button
        if (typeof lucide !== 'undefined') {
            lucide.createIcons({
                attrs: {
                    class: 'w-4 h-4'
                }
            });
        }
        
        bindRow(row);
        itemIndex++;
    });

    recalcTotals();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/accounts-payable/purchase-order-create.blade.php ENDPATH**/ ?>