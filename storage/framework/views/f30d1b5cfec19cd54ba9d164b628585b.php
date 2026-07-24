<?php $__env->startSection('title', 'Edit Asset'); ?>
<?php $__env->startSection('active', 'Fixed Assets'); ?>

<?php $__env->startSection('page-title','Fixed Assets'); ?>
<?php $__env->startSection('page-title-heading','Fixed Assets'); ?>
<?php $__env->startSection('page-subtitle','Manage company fixed assets.'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-3xl font-bold" style="color:#173A66;">Fixed Assets</h1>
            <p class="text-gray-500 mt-1">Manage, track, and maintain all company assets.</p>
        </div>
        <a href="<?php echo e(url('/fixed-assets/assignment/' . $asset->asset_id)); ?>"
           class="px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 bg-white shadow-sm hover:bg-gray-50">
            Back to Asset Details
        </a>
    </div>

    
    <div>
        <h2 class="text-lg font-bold" style="color:#173A66;">Edit Asset</h2>
        <p class="text-gray-500 text-sm mt-0.5">Update the details of <?php echo e($asset->asset_name); ?></p>
    </div>

    <?php if($errors->any()): ?>
        <div class="rounded-md p-3" style="background:#FEE2E2;color:#DC2626;">
            <strong>May mga kulang o maling laman sa form:</strong>
            <ul class="list-disc ml-5 mt-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(url('/fixed-assets/edit/' . $asset->asset_id)); ?>" method="POST" class="space-y-5 max-w-4xl">
        <?php echo csrf_field(); ?>

        
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                <i class="fa-solid fa-file-pen"></i> Asset Information
            </h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Asset ID</label>
                    <input type="text" value="<?php echo e($asset->asset_tag); ?>" disabled
                           class="w-full px-3 py-2 rounded-md border border-gray-200 bg-gray-100 text-sm text-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Asset Name <span class="text-red-500">*</span></label>
                    <input type="text" name="asset_name" value="<?php echo e(old('asset_name', $asset->asset_name)); ?>" required
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                            class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->category_id); ?>" <?php echo e(old('category_id', $asset->category_id) == $cat->category_id ? 'selected' : ''); ?>>
                                <?php echo e($cat->category_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Serial Number</label>
                    <input type="text" name="serial_number" value="<?php echo e(old('serial_number', $asset->serial_number)); ?>"
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Purchase Date <span class="text-red-500">*</span></label>
                    <input type="date" name="acquisition_date" value="<?php echo e(old('acquisition_date', $asset->acquisition_date->format('Y-m-d'))); ?>" required
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Purchase Cost <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="acquisition_cost" value="<?php echo e(old('acquisition_cost', $asset->acquisition_cost)); ?>" required
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Location</label>
                    <input type="text" name="location" value="<?php echo e(old('location', $asset->location)); ?>"
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Warranty (years)</label>
                    <input type="number" name="warranty_years" value="<?php echo e(old('warranty_years', $asset->warranty_years)); ?>"
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1.5">Accumulated Depreciation</label>
                     <input type="number" step="0.01" name="accumulated_depreciation" value="<?php echo e(old('accumulated_depreciation', $asset->accumulated_depreciation)); ?>"
                            class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                <i class="fa-solid fa-circle-check"></i> Status &amp; Condition
            </h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" required
                            class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <?php $currentStatus = old('status', $asset->status); ?>
                        <option value="active" <?php echo e($currentStatus == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="under_maintenance" <?php echo e($currentStatus == 'under_maintenance' ? 'selected' : ''); ?>>Under Maintenance</option>
                        <option value="disposed" <?php echo e($currentStatus == 'disposed' ? 'selected' : ''); ?>>Disposed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Condition</label>
                    <select name="condition"
                            class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <?php $currentCondition = old('condition', $asset->condition); ?>
                        <option value="New" <?php echo e($currentCondition == 'New' ? 'selected' : ''); ?>>New</option>
                        <option value="Good" <?php echo e($currentCondition == 'Good' ? 'selected' : ''); ?>>Good</option>
                        <option value="Fair" <?php echo e($currentCondition == 'Fair' ? 'selected' : ''); ?>>Fair</option>
                        <option value="Poor" <?php echo e($currentCondition == 'Poor' ? 'selected' : ''); ?>>Poor</option>
                    </select>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                <i class="fa-solid fa-file-lines"></i> Description / Notes
            </h3>
            <textarea name="description" rows="3"
                      class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100"><?php echo e(old('description', $asset->description)); ?></textarea>
        </div>

        
        <div class="flex justify-end gap-3">
            <a href="<?php echo e(url('/fixed-assets/assignment/' . $asset->asset_id)); ?>"
               class="px-5 py-2.5 rounded-md text-sm font-semibold border border-gray-300 text-gray-700 bg-white">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-md text-sm font-semibold text-white shadow" style="background:#22B57A;">
                Update Asset
            </button>
        </div>
    </form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/fixed-assets/edit.blade.php ENDPATH**/ ?>