<?php $__env->startSection('title', 'Asset Assignment & Maintenance'); ?>
<?php $__env->startSection('active', 'Fixed Assets'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="flex items-start justify-between">
        <div class="flex gap-3">
            <a href="<?php echo e(url('/fixed-assets/edit/' . $assetData['asset_id'])); ?>"
               class="px-4 py-2 rounded-md text-white text-sm font-semibold shadow" style="background:#173A66;">
                <i class="fa-solid fa-pen mr-1.5"></i> Edit Asset
            </a>
            <form action="<?php echo e(url('/fixed-assets/delete/' . $assetData['asset_id'])); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this asset? This cannot be undone.');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="px-4 py-2 rounded-md text-white text-sm font-semibold shadow" style="background:#DC2626;">
                    <i class="fa-solid fa-trash mr-1.5"></i> Delete Asset
                </button>
            </form>
            <a href="<?php echo e(url('/fixed-assets')); ?>"
               class="px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 bg-white shadow-sm hover:bg-gray-50">
                Back to Asset List
            </a>
        </div>
    </div>

    
    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-lg font-bold" style="color:#173A66;">Asset Assignment &amp; Maintenance</h2>
            <p class="text-gray-500 text-sm mt-0.5">View asset details, assign to employee or department, and manage maintenance schedule and history.</p>
        </div>
        <div class="flex gap-3 shrink-0">
            <a href="<?php echo e(url('/fixed-assets/register')); ?>"
               class="px-5 py-2 rounded-md text-sm font-semibold border" style="border-color:#22B57A;color:#22B57A;background:#fff;">
                Registration
            </a>
            <button class="px-5 py-2 rounded-md text-white text-sm font-semibold shadow" style="background:#22B57A;">
                Assignment
            </button>
        </div>
    </div>

    
    <div class="grid grid-cols-3 gap-5">
        <div class="col-span-2 bg-white rounded-lg border border-gray-200 p-5 flex gap-5">
            <div class="w-24 h-24 rounded-md flex items-center justify-center shrink-0" style="background:#F3F5F9;">
                <i class="fa-solid fa-laptop text-4xl text-gray-300"></i>
            </div>
            <div class="flex-1 grid grid-cols-2 gap-4">
                <div class="space-y-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold" style="color:#173A66;"><?php echo e($assetData['name']); ?></span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium" style="background:#D6F5DF;color:#16A34A;"><?php echo e($assetData['status']); ?></span>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400">Asset ID</div>
                        <div class="text-sm font-medium text-gray-700"><?php echo e($assetData['tag']); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400">Category</div>
                        <div class="text-sm font-medium text-gray-700"><?php echo e($assetData['category']); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400">Serial Number</div>
                        <div class="text-sm font-medium text-gray-700"><?php echo e($assetData['serial_number']); ?></div>
                    </div>
                </div>
                <div class="space-y-3 pt-7">
                    <div>
                        <div class="text-xs text-gray-400">Purchase Date</div>
                        <div class="text-sm font-medium text-gray-700"><?php echo e($assetData['purchase_date']); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400">Purchase Cost</div>
                        <div class="text-sm font-medium text-gray-700"><?php echo e($assetData['purchase_cost']); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400">Warranty</div>
                        <div class="text-sm font-medium text-gray-700"><?php echo e($assetData['warranty']); ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400">Useful Life</div>
                        <div class="text-sm font-medium text-gray-700"><?php echo e($assetData['useful_life']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                <i class="fa-solid fa-circle-check"></i> Current Status
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#D6F5DF;color:#16A34A;"><?php echo e($assetData['status']); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Condition</span>
                    <span class="font-medium text-gray-700"><?php echo e($assetData['condition']); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Location</span>
                    <span class="font-medium text-gray-700"><?php echo e($assetData['location']); ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Last Maintenance</span>
                    <span class="font-medium text-gray-700">June 20, 2025</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Next Maintenance</span>
                    <span class="font-medium text-gray-700">December 20, 2025</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-3 gap-5">

        
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="flex items-center gap-2 font-bold" style="color:#173A66;">
                    <i class="fa-solid fa-user-group"></i> Assignment Information
                </h3>
                <button type="button" onclick="document.getElementById('assignmentModal').classList.remove('hidden')"
                        class="text-xs font-medium px-2.5 py-1 rounded-md border border-gray-200 text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-plus mr-1"></i> add assignment
                </button>
            </div>
            <div class="space-y-3 text-sm">
                <div>
                    <div class="text-xs text-gray-400 mb-1">Assigned To</div>
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background:#3B82F6;">JD</span>
                        <span class="font-medium text-gray-700">Juan Dela Cruz</span>
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-400">Department</div>
                    <div class="font-medium text-gray-700">IT Department</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400">Location</div>
                    <div class="font-medium text-gray-700"><?php echo e($assetData['location']); ?></div>
                </div>
                <div>
                    <div class="text-xs text-gray-400">Date Assigned</div>
                    <div class="font-medium text-gray-700"><?php echo e($assetData['purchase_date']); ?></div>
                </div>
                <div>
                    <div class="text-xs text-gray-400">Cost Center</div>
                    <div class="font-medium text-gray-700">IT-100</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400">Remarks</div>
                    <div class="font-medium text-gray-700"><?php echo e($assetData['description']); ?></div>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                <i class="fa-solid fa-screwdriver-wrench"></i> Maintenance Schedule
            </h3>

            <div class="rounded-md p-3 mb-4" style="background:#FFF4E5;border:1px solid #FBD9A5;">
                <div class="text-xs font-semibold" style="color:#B45309;">Next Maintenance</div>
                <div class="font-bold mb-2" style="color:#173A66;">December 20, 2025</div>
                <div class="grid grid-cols-2 gap-y-1.5 text-xs text-gray-600">
                    <div>Maintenance Type</div><div class="text-right font-medium text-gray-700">Preventive Maintenance</div>
                    <div>Technician</div><div class="text-right font-medium text-gray-700">Mel Paul Torres</div>
                    <div>Priority</div><div class="text-right font-medium text-gray-700">Medium</div>
                    <div>Estimated Cost</div><div class="text-right font-medium text-gray-700">₱45.00</div>
                </div>
            </div>

            <div class="text-xs font-semibold text-gray-500 mb-2">Upcoming Schedules</div>
            <ul class="space-y-2">
                <?php
                    $upcoming = [
                        ['date' => 'June 20, 2025', 'type' => 'Preventive Maintenance'],
                        ['date' => 'August 8, 2025', 'type' => 'Preventive Maintenance'],
                        ['date' => 'December 15, 2025', 'type' => 'Preventive Maintenance'],
                    ];
                ?>
                <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex items-center gap-2 text-xs">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center shrink-0" style="background:#DBEAFE;">
                            <i class="fa-solid fa-calendar-days text-blue-500" style="font-size:9px;"></i>
                        </span>
                        <span class="font-medium text-gray-700"><?php echo e($u['date']); ?></span>
                        <span class="text-gray-400"><?php echo e($u['type']); ?></span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="flex items-center gap-2 font-bold mb-4" style="color:#173A66;">
                <i class="fa-solid fa-clock-rotate-left"></i> Maintenance History
            </h3>
            <div class="space-y-3">
                <?php
                    $history = [
                        ['date' => 'June 20, 2025', 'type' => 'Preventive Maintenance', 'tech' => 'Mel Paul Torres', 'desc' => 'System check and optimization', 'cost' => '₱50.00', 'status' => 'Completed'],
                        ['date' => 'September 12, 2024', 'type' => 'Repaired Part', 'tech' => 'Mel Paul Torres', 'desc' => 'General check up', 'cost' => '₱1,200.00', 'status' => 'Completed'],
                        ['date' => 'March 5, 2024', 'type' => 'Setup', 'tech' => 'Mel Paul Torres', 'desc' => 'Initial setup and configuration', 'cost' => '₱450.00', 'status' => 'Completed'],
                    ];
                ?>
                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-xs border-b border-gray-50 pb-2.5">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-700"><?php echo e($h['date']); ?></span>
                            <span class="px-2 py-0.5 rounded-full font-medium" style="background:#D6F5DF;color:#16A34A;"><?php echo e($h['status']); ?></span>
                        </div>
                        <div class="text-gray-500 mt-0.5"><?php echo e($h['type']); ?> &middot; <?php echo e($h['tech']); ?></div>
                        <div class="text-gray-400"><?php echo e($h['desc']); ?></div>
                        <div class="text-gray-700 font-medium mt-0.5"><?php echo e($h['cost']); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="mt-3 flex items-center justify-between rounded-md px-3 py-2 text-sm font-semibold" style="background:#EEF0FA;color:#173A66;">
                <span>Total Maintenance Cost</span>
                <span>₱1,700.00</span>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 gap-5">

        
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="flex items-center gap-2 font-bold" style="color:#173A66;">
                    <i class="fa-solid fa-timeline"></i> Asset Timeline
                </h3>
                <button type="button" onclick="document.getElementById('timelineModal').classList.remove('hidden')" class="text-xs font-medium" style="color:#3B82F6;">View All</button>
            </div>
            <ul class="space-y-4">
                <?php
                    $timeline = [
                        ['date' => $assetData['purchase_date'], 'title' => 'Asset Registered', 'desc' => $assetData['name'] . ' added to inventory', 'done' => true],
                        ['date' => 'June 15, 2024', 'title' => 'Assigned to IT Department', 'desc' => 'Assigned to Juan Dela Cruz', 'done' => true],
                        ['date' => 'October 15, 2024', 'title' => 'Preventive Maintenance', 'desc' => 'System cleaning and optimization', 'done' => true],
                        ['date' => '', 'title' => 'Upcoming Maintenance', 'desc' => 'Next preventive maintenance schedule', 'done' => false],
                    ];
                ?>
                <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5"
                              style="background: <?php echo e($t['done'] ? '#22B57A' : '#F5A623'); ?>;">
                            <i class="fa-solid <?php echo e($t['done'] ? 'fa-check' : 'fa-clock'); ?> text-white" style="font-size:9px;"></i>
                        </span>
                        <div class="text-sm">
                            <div class="font-medium text-gray-700"><?php echo e($t['title']); ?></div>
                            <?php if($t['date']): ?>
                                <div class="text-xs text-gray-400"><?php echo e($t['date']); ?></div>
                            <?php endif; ?>
                            <div class="text-xs text-gray-500 mt-0.5"><?php echo e($t['desc']); ?></div>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="flex items-center gap-2 font-bold" style="color:#173A66;">
                    <i class="fa-solid fa-folder-open"></i> Asset Document
                </h3>
                <button type="button" onclick="document.getElementById('documentModal').classList.remove('hidden')" class="text-xs font-medium" style="color:#3B82F6;">View All</button>
            </div>
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-gray-100">
                        <th class="py-2 font-medium">File Name</th>
                        <th class="py-2 font-medium">Type</th>
                        <th class="py-2 font-medium">Uploaded By</th>
                        <th class="py-2 font-medium">Date Uploaded</th>
                        <th class="py-2 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $docs = [
                            ['name' => 'Purchase Receipt.pdf', 'type' => 'Purchase', 'by' => 'Admin User', 'date' => 'May 15, 2024'],
                            ['name' => 'Warranty Card.pdf', 'type' => 'Warranty', 'by' => 'Admin User', 'date' => 'May 15, 2024'],
                            ['name' => 'User Manual.pdf', 'type' => 'Manual', 'by' => 'Admin User', 'date' => 'May 15, 2024'],
                            ['name' => 'Maintenance checklist.pdf', 'type' => 'Maintenance', 'by' => 'Admin User', 'date' => 'June 20, 2024'],
                        ];
                        $docColors = [
                            'Purchase' => 'background:#DBEAFE;color:#2563EB;',
                            'Warranty' => 'background:#EDE9FE;color:#7C3AED;',
                            'Manual' => 'background:#D6F5DF;color:#16A34A;',
                            'Maintenance' => 'background:#FFF4D6;color:#B45309;',
                        ];
                    ?>
                    <?php $__currentLoopData = $docs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-50">
                            <td class="py-2 font-medium text-gray-700"><?php echo e($d['name']); ?></td>
                            <td class="py-2">
                                <span class="px-2 py-0.5 rounded-full font-medium" style="<?php echo e($docColors[$d['type']]); ?>"><?php echo e($d['type']); ?></span>
                            </td>
                            <td class="py-2 text-gray-500"><?php echo e($d['by']); ?></td>
                            <td class="py-2 text-gray-500"><?php echo e($d['date']); ?></td>
                            <td class="py-2 text-right">
                                <i class="fa-solid fa-download text-gray-400 hover:text-gray-600 mr-2 cursor-pointer"></i>
                                <i class="fa-solid fa-trash text-red-400 hover:text-red-600 cursor-pointer"></i>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div id="assignmentModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4"
         onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-2xl font-bold" style="color:#173A66;">Assign Asset</h2>
                    <p class="text-sm text-gray-500 mt-1">Fill the assignment details and click assign to save the change.</p>
                </div>
                <button type="button" onclick="document.getElementById('assignmentModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 bg-white shadow-sm hover:bg-gray-50">
                    Close
                </button>
            </div>

            <div class="grid gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <label class="block text-xs font-semibold text-gray-500">Assign to</label>
                    <label class="block text-xs font-semibold text-gray-500">Department</label>
                    <input type="text" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm" placeholder="Employee name" />
                    <input type="text" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm" placeholder="Department" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <label class="block text-xs font-semibold text-gray-500">Location</label>
                    <label class="block text-xs font-semibold text-gray-500">Date Assigned</label>
                    <input type="text" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm" placeholder="Location" />
                    <input type="date" class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2">Remarks</label>
                    <textarea class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm" rows="4" placeholder="Enter assignment notes"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('assignmentModal').classList.add('hidden')"
                            class="rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" onclick="document.getElementById('assignmentModal').classList.add('hidden')"
                            class="rounded-md bg-[#22B57A] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#1f965f]">
                        Assign
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div id="timelineModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4"
         onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[85vh] overflow-y-auto p-6">

            <div class="text-sm mb-2">
                <a href="<?php echo e(url('/fixed-assets/assignment')); ?>" class="font-medium" style="color:#3B82F6;">Asset Assignment &amp; Maintenance</a>
                <span class="text-gray-400 mx-1.5">&gt;</span>
                <span class="text-gray-500">Asset Timeline</span>
            </div>

            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold" style="color:#173A66;">Asset Timeline</h2>
                    <p class="text-gray-500 text-sm mt-1">view the complete history and important of the selected asset.</p>
                </div>
                <button type="button" onclick="document.getElementById('timelineModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 bg-white shadow-sm hover:bg-gray-50 whitespace-nowrap">
                    Back to Asset Assignment
                </button>
            </div>

            <div class="rounded-lg border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600" style="background:#EEF0FA;">
                            <th class="px-4 py-3 font-medium">Date/Time</th>
                            <th class="px-4 py-3 font-medium">Event</th>
                            <th class="px-4 py-3 font-medium">Description</th>
                            <th class="px-4 py-3 font-medium">Performed By</th>
                            <th class="px-4 py-3 font-medium">Reference No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $timelineFull = [
                                ['date' => 'January 15, 2024',  'event' => 'Asset Registered',        'desc' => 'Dell Latitude 5400 was added to the system.', 'by' => 'Admin User',   'ref' => 'AST-2024-0001'],
                                ['date' => 'June 15, 2024',      'event' => 'Assign to Department',    'desc' => 'Assigned to Juan Dela Cruz.',                 'by' => 'Admin User',   'ref' => 'ASN-2024-0012'],
                                ['date' => 'October 15, 2024',   'event' => 'Relocation',              'desc' => 'Upcoming Maintenance',                        'by' => 'Admin User',   'ref' => 'REL-2024-0010'],
                                ['date' => 'March 05, 2024',     'event' => 'Inspection',              'desc' => 'Routine inspection Completed',                 'by' => 'Tech Solution','ref' => 'INS-2024-0015'],
                                ['date' => 'June 20, 2024',      'event' => 'Preventive Maintenance',  'desc' => 'System cleaning and optimization completed',  'by' => 'Tech Solution','ref' => 'MTN-2024-0045'],
                                ['date' => 'July 12, 2024',      'event' => 'Reassigned',              'desc' => 'Reassigned to Mark Anthony',                   'by' => 'Admin User',   'ref' => 'ASN-2024-0088'],
                                ['date' => 'September 03, 2024', 'event' => 'Repair',                  'desc' => 'Battery replaced due to performance issue',    'by' => 'Tech Solution','ref' => 'REP-2024-0111'],
                                ['date' => 'November 18, 2024',  'event' => 'Information Update',      'desc' => 'Warranty information updated',                 'by' => 'Admin User',   'ref' => 'UPD-2024-0092'],
                            ];
                        ?>
                        <?php $__currentLoopData = $timelineFull; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium" style="color:#173A66;"><?php echo e($t['date']); ?></td>
                                <td class="px-4 py-3 text-gray-700"><?php echo e($t['event']); ?></td>
                                <td class="px-4 py-3 text-gray-500"><?php echo e($t['desc']); ?></td>
                                <td class="px-4 py-3 text-gray-500"><?php echo e($t['by']); ?></td>
                                <td class="px-4 py-3 text-gray-500"><?php echo e($t['ref']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <div class="flex items-center justify-between px-4 py-3 text-xs text-gray-400">
                    <span>Showing 1 to 13 of 13 entries</span>
                    <div class="flex gap-1">
                        <button class="w-7 h-7 rounded-md text-white text-xs" style="background:#173A66;">1</button>
                        <button class="w-7 h-7 rounded-md border border-gray-200 text-xs">2</button>
                        <button class="w-7 h-7 rounded-md border border-gray-200 text-xs">3</button>
                        <span class="px-1">...</span>
                        <button class="w-7 h-7 rounded-md border border-gray-200 text-xs">13</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="documentModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4"
         onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl max-h-[85vh] overflow-y-auto p-6">

            <div class="text-sm mb-2">
                <a href="<?php echo e(url('/fixed-assets/assignment')); ?>" class="font-medium" style="color:#3B82F6;">Asset Assignment &amp; Maintenance</a>
                <span class="text-gray-400 mx-1.5">&gt;</span>
                <span class="text-gray-500">Asset Document</span>
            </div>

            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold" style="color:#173A66;">Asset Document</h2>
                    <p class="text-gray-500 text-sm mt-1">view the complete history and important of the selected asset.</p>
                </div>
                <button type="button" onclick="document.getElementById('documentModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 bg-white shadow-sm hover:bg-gray-50 whitespace-nowrap">
                    Back to Asset Assignment
                </button>
            </div>

            <div class="rounded-lg border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600" style="background:#EEF0FA;">
                            <th class="px-4 py-3 font-medium">File Name</th>
                            <th class="px-4 py-3 font-medium">Type</th>
                            <th class="px-4 py-3 font-medium">Description</th>
                            <th class="px-4 py-3 font-medium">Uploaded By</th>
                            <th class="px-4 py-3 font-medium">File Size</th>
                            <th class="px-4 py-3 font-medium text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $docsFull = [
                                ['name' => 'Purchase Receipt.pdf',    'type' => 'Purchase',            'desc' => 'Official receipt for the purchase of the asset.', 'by' => 'Admin User',    'size' => '245 KB'],
                                ['name' => 'Warranty Card.pdf',       'type' => 'Warranty',            'desc' => 'Warranty card for the asset.',                     'by' => 'Admin User',    'size' => '180 KB'],
                                ['name' => 'User Manual.pdf',         'type' => 'Manual',               'desc' => 'User Manual and setup guide.',                     'by' => 'Admin User',    'size' => '1.2 MB'],
                                ['name' => 'Maintenance Checklist.pdf','type' => 'Maintenance',          'desc' => 'Maintenance checklist form.',                      'by' => 'Admin User',    'size' => '320 KB'],
                                ['name' => 'Repair Report.pdf',       'type' => 'Maintenance',          'desc' => 'Repair report after preventive maintenance.',     'by' => 'Tech Solution', 'size' => '512 KB'],
                                ['name' => 'Deprecation.pdf',         'type' => 'Depreciation',         'desc' => 'Depreciation schedule for this asset.',           'by' => 'Admin User',    'size' => '78 KB'],
                                ['name' => 'Insurance Policy.pdf',    'type' => 'Insurance',            'desc' => 'Insurance Policy document.',                       'by' => 'Admin User',    'size' => '425 KB'],
                                ['name' => 'Transfer Form.pdf',       'type' => 'Asset transfer form',  'desc' => 'Asset transfet form.',                             'by' => 'Admin User',    'size' => '95 KB'],
                            ];
                            $docFullColors = [
                                'Purchase' => 'background:#DBEAFE;color:#2563EB;',
                                'Warranty' => 'background:#EDE9FE;color:#7C3AED;',
                                'Manual' => 'background:#D6F5DF;color:#16A34A;',
                                'Maintenance' => 'background:#FFF4D6;color:#B45309;',
                                'Depreciation' => 'background:#FEE2E2;color:#DC2626;',
                                'Insurance' => 'background:#E0E7FF;color:#4338CA;',
                                'Asset transfer form' => 'background:#F3E8FF;color:#9333EA;',
                            ];
                        ?>
                        <?php $__currentLoopData = $docsFull; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-700"><?php echo e($d['name']); ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium" style="<?php echo e($docFullColors[$d['type']] ?? 'background:#F3F4F6;color:#374151;'); ?>"><?php echo e($d['type']); ?></span>
                                </td>
                                <td class="px-4 py-3 text-gray-500"><?php echo e($d['desc']); ?></td>
                                <td class="px-4 py-3 text-gray-500"><?php echo e($d['by']); ?></td>
                                <td class="px-4 py-3 text-gray-500"><?php echo e($d['size']); ?></td>
                                <td class="px-4 py-3 text-right">
                                    <i class="fa-solid fa-download text-gray-400 hover:text-gray-600 mr-3 cursor-pointer"></i>
                                    <i class="fa-solid fa-trash text-red-400 hover:text-red-600 cursor-pointer"></i>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <div class="flex items-center justify-between px-4 py-3 text-xs text-gray-400">
                    <span>Showing 1 to 13 of 13 entries</span>
                    <div class="flex gap-1">
                        <button class="w-7 h-7 rounded-md text-white text-xs" style="background:#173A66;">1</button>
                        <button class="w-7 h-7 rounded-md border border-gray-200 text-xs">2</button>
                        <button class="w-7 h-7 rounded-md border border-gray-200 text-xs">3</button>
                        <span class="px-1">...</span>
                        <button class="w-7 h-7 rounded-md border border-gray-200 text-xs">13</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Finance-Accounting\resources\views/fixed-assets/assignment.blade.php ENDPATH**/ ?>