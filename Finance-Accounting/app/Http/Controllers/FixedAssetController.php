<?php

namespace App\Http\Controllers;

use App\Models\FixedAssets\FixedAsset;
use App\Models\FixedAssets\AssetCategory;
use App\Models\FixedAssets\ActivityLog;
use App\Models\FixedAssets\Document;
use App\Models\FixedAssets\Assignment;
use App\Models\FixedAssets\Maintenance;
use App\Services\GeneralLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class FixedAssetController extends Controller
{
    protected GeneralLedgerService $gl;

    public function __construct(GeneralLedgerService $gl)
    {
        $this->gl = $gl;
    }

    private function actor(): string
    {
        return auth()->check() ? auth()->user()->name : 'Admin User';
    }

    public function index(Request $request)
    {
        if (Schema::hasTable('fa_fixed_assets')) {
            $allAssets = FixedAsset::with('category')->get();

            $query = FixedAsset::with('category');
            if ($request->filled('category')) {
                $query->whereHas('category', fn($q) => $q->where('category_name', $request->category));
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('search')) {
                $query->where('asset_name', 'like', '%' . $request->search . '%');
            }
            $paginated = $query->orderByDesc('asset_id')->paginate(5)->withQueryString();
        } else {
            $allAssets = collect();
            $paginated = new \Illuminate\Pagination\LengthAwarePaginator(collect(), 0, 5);
        }

        $statusMap = [
            'active' => 'Active',
            'disposed' => 'Disposed',
            'under_maintenance' => 'Under Maintenance',
            'fully_depreciated' => 'Fully Depreciated',
        ];

        $stats = [
            ['label' => 'Total Assets', 'value' => $allAssets->count(), 'icon' => 'fa-warehouse', 'color' => '#22B57A'],
            ['label' => 'Total Assets Value', 'value' => '₱' . number_format($allAssets->sum('acquisition_cost'), 2), 'icon' => 'fa-dollar-sign', 'color' => '#22B57A'],
            ['label' => 'Accumulated Depreciation', 'value' => '₱' . number_format($allAssets->sum('accumulated_depreciation'), 2), 'icon' => 'fa-chart-line', 'color' => '#22B57A'],
            ['label' => 'Under Maintenance', 'value' => $allAssets->where('status', 'under_maintenance')->count(), 'icon' => 'fa-screwdriver-wrench', 'color' => '#F5A623'],
        ];

        $totalCount = max($allAssets->count(), 1);
        $categoryBreakdown = $allAssets
            ->groupBy(fn($a) => $a->category->category_name ?? 'Uncategorized')
            ->map(fn($group, $name) => [
                'label' => $name,
                'percent' => round($group->count() / $totalCount * 100),
            ])
            ->sortByDesc('percent')
            ->values();

        $statusBreakdown = collect($statusMap)->map(function ($label, $key) use ($allAssets, $totalCount) {
            return [
                'label' => $label,
                'percent' => round($allAssets->where('status', $key)->count() / $totalCount * 100),
            ];
        })->filter(fn($s) => $s['percent'] > 0)->values();

        $assets = $paginated->through(function ($asset) use ($statusMap) {
            return [
                'asset_id' => $asset->asset_id,
                'id' => $asset->asset_tag,
                'name' => $asset->asset_name,
                'category' => $asset->category->category_name ?? 'Uncategorized',
                'location' => $asset->location,
                'date' => $asset->acquisition_date->format('M d, Y'),
                'cost' => '₱' . number_format($asset->acquisition_cost, 2),
                'status' => $statusMap[$asset->status] ?? ucfirst($asset->status),
            ];
        });

        $iconMap = [
            'created'  => ['icon' => 'plus',      'color' => '#1F2937'],
            'updated'  => ['icon' => 'pencil',    'color' => '#3B82F6'],
            'deleted'  => ['icon' => 'trash-2',   'color' => '#EF4444'],
            'disposed' => ['icon' => 'archive',   'color' => '#F5A623'],
            'assigned' => ['icon' => 'user-plus', 'color' => '#3B82F6'],
        ];

        $recentActivities = Schema::hasTable('fa_activity_logs')
            ? ActivityLog::orderByDesc('created_at')->limit(5)->get()->map(function ($log) use ($iconMap) {
                $meta = $iconMap[$log->action] ?? ['icon' => 'info', 'color' => '#6B7280'];
                return [
                    'icon' => $meta['icon'],
                    'color' => $meta['color'],
                    'text' => $log->description,
                    'time' => $log->created_at->diffForHumans(),
                ];
            })
            : collect();

        return view('fixed-assets.index', compact('stats', 'assets', 'categoryBreakdown', 'statusBreakdown', 'recentActivities'));
    }

    public function create()
    {
        $categories = AssetCategory::all();

        $year = date('Y');
        $lastAsset = FixedAsset::where('asset_tag', 'like', "FA-{$year}-%")
            ->orderByDesc('asset_id')
            ->first();
        $lastNumber = $lastAsset ? (int) substr($lastAsset->asset_tag, -3) : 0;
        $tag = 'FA-' . $year . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return view('fixed-assets.register', compact('categories', 'tag'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_name' => 'required|string|max:150',
            'category_id' => 'required|exists:fa_asset_categories,category_id',
            'acquisition_date' => 'required|date',
            'acquisition_cost' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:100',
            'status' => 'nullable|in:active,disposed,under_maintenance,fully_depreciated',
            'serial_number' => 'nullable|string|max:100',
            'warranty_years' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'condition' => 'nullable|in:New,Good,Fair,Poor',
        ]);

        $asset = \DB::transaction(function () use ($validated) {
            $year = date('Y');

            $lastAsset = FixedAsset::where('asset_tag', 'like', "FA-{$year}-%")
                ->lockForUpdate()
                ->orderByDesc('asset_id')
                ->first();

            $lastNumber = $lastAsset ? (int) substr($lastAsset->asset_tag, -3) : 0;
            $tag = 'FA-' . $year . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            return FixedAsset::create([
                'asset_tag' => $tag,
                'asset_name' => $validated['asset_name'],
                'category_id' => $validated['category_id'],
                'acquisition_date' => $validated['acquisition_date'],
                'acquisition_cost' => $validated['acquisition_cost'],
                'salvage_value' => 0,
                'useful_life_years' => 5,
                'depreciation_method' => 'straight_line',
                'accumulated_depreciation' => 0,
                'book_value' => $validated['acquisition_cost'],
                'location' => $validated['location'] ?? null,
                'status' => $validated['status'] ?? 'active',
                'serial_number' => $validated['serial_number'] ?? null,
                'warranty_years' => $validated['warranty_years'] ?? null,
                'description' => $validated['description'] ?? null,
                'condition' => $validated['condition'] ?? 'Good',
            ]);
        });

        $this->gl->postAssetAcquisition($asset);

        ActivityLog::create([
            'asset_id' => $asset->asset_id,
            'action' => 'created',
            'description' => "{$asset->asset_name} added to inventory",
            'performed_by' => $this->actor(),
        ]);

        return redirect('/fixed-assets')->with('success', 'Asset successfully registered!');
    }

    public function assignment($id = null)
    {
        if ($id) {
            $asset = FixedAsset::with('category')->findOrFail($id);
        } else {
            $asset = FixedAsset::with('category')->first();
        }

        $statusMap = [
            'active' => 'Active',
            'disposed' => 'Disposed',
            'under_maintenance' => 'Under Maintenance',
            'fully_depreciated' => 'Active',
        ];

        $assetData = [
            'asset_id' => $asset->asset_id,
            'tag' => $asset->asset_tag,
            'name' => $asset->asset_name,
            'category' => $asset->category->category_name ?? 'Uncategorized',
            'status' => $statusMap[$asset->status] ?? ucfirst($asset->status),
            'purchase_date' => $asset->acquisition_date->format('M d, Y'),
            'purchase_cost' => '₱' . number_format($asset->acquisition_cost, 2),
            'useful_life' => $asset->useful_life_years . ' Year',
            'location' => $asset->location ?? '-',
            'condition' => $asset->condition ?? 'Good',
            'serial_number' => $asset->serial_number ?? '-',
            'warranty' => $asset->warranty_years ? $asset->warranty_years . ' Year(s)' : '-',
            'description' => $asset->description ?? '-',
        ];

        // ✅ Totoong Maintenance records ng asset na ito
        $maintenanceRecords = Schema::hasTable('fa_maintenance_records')
            ? Maintenance::where('asset_id', $asset->asset_id)->get()
            : collect();

        $upcomingMaintenance = $maintenanceRecords->where('status', 'scheduled')
            ->sortBy('scheduled_date')
            ->values();

        $maintenanceHistory = $maintenanceRecords->where('status', 'completed')
            ->sortByDesc('completed_date')
            ->values();

        $nextMaintenance = $upcomingMaintenance->first();
        $lastMaintenance = $maintenanceHistory->first();

        $totalMaintenanceCost = $maintenanceHistory->sum(fn ($m) => $m->actual_cost ?? $m->estimated_cost ?? 0);

        $assetData['last_maintenance'] = $lastMaintenance ? $lastMaintenance->completed_date->format('M d, Y') : '-';
        $assetData['next_maintenance'] = $nextMaintenance ? $nextMaintenance->scheduled_date->format('M d, Y') : '-';

        $documents = Schema::hasTable('fa_documents')
            ? Document::where('asset_id', $asset->asset_id)->orderByDesc('created_at')->get()
            : collect();

        $timelineIconMap = [
            'created'  => ['icon' => 'check', 'color' => '#22B57A', 'done' => true],
            'updated'  => ['icon' => 'pen',   'color' => '#3B82F6', 'done' => true],
            'deleted'  => ['icon' => 'xmark', 'color' => '#EF4444', 'done' => true],
            'disposed' => ['icon' => 'box',   'color' => '#F5A623', 'done' => true],
            'assigned' => ['icon' => 'user',  'color' => '#3B82F6', 'done' => true],
        ];

        $timeline = Schema::hasTable('fa_activity_logs')
            ? ActivityLog::where('asset_id', $asset->asset_id)
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($log) use ($timelineIconMap) {
                    $meta = $timelineIconMap[$log->action] ?? ['icon' => 'info', 'color' => '#6B7280', 'done' => true];
                    return [
                        'date' => $log->created_at->format('M d, Y'),
                        'title' => ucfirst($log->action),
                        'desc' => $log->description,
                        'icon' => $meta['icon'],
                        'color' => $meta['color'],
                        'done' => $meta['done'],
                    ];
                })
            : collect();

        // ✅ Totoong pinakahuling Assignment record ng asset na ito
        $assignment = Schema::hasTable('fa_assignments')
            ? Assignment::where('asset_id', $asset->asset_id)->orderByDesc('date_assigned')->first()
            : null;

        return view('fixed-assets.assignment', compact(
            'asset', 'assetData', 'documents', 'timeline', 'assignment',
            'upcomingMaintenance', 'maintenanceHistory', 'nextMaintenance', 'totalMaintenanceCost'
        ));
    }

    public function storeAssignment(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id);

        $validated = $request->validate([
            'assigned_to' => 'required|string|max:150',
            'department' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'date_assigned' => 'required|date',
            'cost_center' => 'nullable|string|max:50',
            'remarks' => 'nullable|string|max:255',
        ]);

        Assignment::create([
            'asset_id' => $asset->asset_id,
            'assigned_to' => $validated['assigned_to'],
            'department' => $validated['department'] ?? null,
            'location' => $validated['location'] ?? null,
            'date_assigned' => $validated['date_assigned'],
            'cost_center' => $validated['cost_center'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        ActivityLog::create([
            'asset_id' => $asset->asset_id,
            'action' => 'assigned',
            'description' => "{$asset->asset_name} assigned to {$validated['assigned_to']}",
            'performed_by' => $this->actor(),
        ]);

        return redirect('/fixed-assets/assignment/' . $asset->asset_id)->with('success', 'Asset assigned successfully!');
    }

    public function storeMaintenance(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id);

        $validated = $request->validate([
            'maintenance_type' => 'required|string|max:100',
            'technician' => 'nullable|string|max:150',
            'priority' => 'nullable|in:Low,Medium,High',
            'estimated_cost' => 'nullable|numeric|min:0',
            'scheduled_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        Maintenance::create([
            'asset_id' => $asset->asset_id,
            'maintenance_type' => $validated['maintenance_type'],
            'technician' => $validated['technician'] ?? null,
            'priority' => $validated['priority'] ?? 'Medium',
            'estimated_cost' => $validated['estimated_cost'] ?? null,
            'scheduled_date' => $validated['scheduled_date'],
            'description' => $validated['description'] ?? null,
            'status' => 'scheduled',
        ]);

        ActivityLog::create([
            'asset_id' => $asset->asset_id,
            'action' => 'updated',
            'description' => "Maintenance scheduled for {$asset->asset_name}",
            'performed_by' => $this->actor(),
        ]);

        return redirect('/fixed-assets/assignment/' . $asset->asset_id)->with('success', 'Maintenance scheduled successfully!');
    }

    public function completeMaintenance(Request $request, $maintenanceId)
    {
        $maintenance = Maintenance::findOrFail($maintenanceId);

        $validated = $request->validate([
            'actual_cost' => 'nullable|numeric|min:0',
        ]);

        $maintenance->update([
            'status' => 'completed',
            'completed_date' => now()->format('Y-m-d'),
            'actual_cost' => $validated['actual_cost'] ?? $maintenance->estimated_cost,
        ]);

        ActivityLog::create([
            'asset_id' => $maintenance->asset_id,
            'action' => 'updated',
            'description' => "Maintenance ({$maintenance->maintenance_type}) marked as completed",
            'performed_by' => $this->actor(),
        ]);

        return redirect('/fixed-assets/assignment/' . $maintenance->asset_id)->with('success', 'Maintenance marked as completed!');
    }

    public function edit($id)
    {
        $asset = FixedAsset::findOrFail($id);
        $categories = AssetCategory::all();

        return view('fixed-assets.edit', compact('asset', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id);

        $validated = $request->validate([
            'asset_name' => 'required|string|max:150',
            'category_id' => 'required|exists:fa_asset_categories,category_id',
            'acquisition_date' => 'required|date',
            'acquisition_cost' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:active,disposed,under_maintenance,fully_depreciated',
            'serial_number' => 'nullable|string|max:100',
            'warranty_years' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'condition' => 'nullable|in:New,Good,Fair,Poor',
            'accumulated_depreciation' => 'nullable|numeric|min:0',
        ]);

        $bookValue = $validated['acquisition_cost'] - ($validated['accumulated_depreciation'] ?? 0);

        $asset->update([
            'asset_name' => $validated['asset_name'],
            'category_id' => $validated['category_id'],
            'acquisition_date' => $validated['acquisition_date'],
            'acquisition_cost' => $validated['acquisition_cost'],
            'location' => $validated['location'] ?? null,
            'status' => $validated['status'],
            'serial_number' => $validated['serial_number'] ?? null,
            'warranty_years' => $validated['warranty_years'] ?? null,
            'description' => $validated['description'] ?? null,
            'condition' => $validated['condition'] ?? 'Good',
            'accumulated_depreciation' => $validated['accumulated_depreciation'] ?? 0,
            'book_value' => $bookValue,
        ]);

        ActivityLog::create([
            'asset_id' => $asset->asset_id,
            'action' => 'updated',
            'description' => "{$asset->asset_name} details updated",
            'performed_by' => $this->actor(),
        ]);

        return redirect('/fixed-assets/assignment/' . $asset->asset_id)->with('success', 'Asset successfully updated!');
    }

    public function destroy($id)
    {
        $asset = FixedAsset::findOrFail($id);
        $assetName = $asset->asset_name;

        $this->gl->reverseAssetEntries($asset);

        $asset->delete();

        ActivityLog::create([
            'asset_id' => null,
            'action' => 'deleted',
            'description' => "Asset {$assetName} deleted",
            'performed_by' => $this->actor(),
        ]);

        return redirect('/fixed-assets')->with('success', 'Asset successfully deleted!');
    }

    public function disposeForm($id)
    {
        $asset = FixedAsset::findOrFail($id);
        return view('fixed-assets.dispose', compact('asset'));
    }

    public function dispose(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id);

        $validated = $request->validate([
            'disposal_date'   => 'required|date',
            'disposal_value'  => 'required|numeric|min:0',
            'disposal_reason' => 'required|in:sold,scrapped,donated,lost',
        ]);

        $gainLoss = $validated['disposal_value'] - $asset->book_value;

        $asset->update([
            'status'          => 'disposed',
            'disposal_date'   => $validated['disposal_date'],
            'disposal_value'  => $validated['disposal_value'],
            'disposal_reason' => $validated['disposal_reason'],
            'gain_loss'       => $gainLoss,
        ]);

        $this->gl->postDisposal($asset->fresh());

        ActivityLog::create([
            'asset_id' => $asset->asset_id,
            'action' => 'disposed',
            'description' => "{$asset->asset_name} disposed ({$validated['disposal_reason']})",
            'performed_by' => $this->actor(),
        ]);

        return redirect('/fixed-assets')->with('success', 'Asset successfully disposed!');
    }

    // ============ DOCUMENT UPLOAD / DOWNLOAD / DELETE ============

    public function uploadDocument(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id);

        $validated = $request->validate([
            'file' => 'required|file|max:10240',
            'type' => 'required|in:Purchase,Warranty,Manual,Maintenance,Depreciation,Insurance,Asset transfer form,Other',
            'description' => 'nullable|string|max:255',
        ]);

        $uploaded = $request->file('file');
        $path = $uploaded->store('asset-documents', 'public');

        Document::create([
            'asset_id' => $asset->asset_id,
            'file_name' => $uploaded->getClientOriginalName(),
            'file_path' => $path,
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'uploaded_by' => $this->actor(),
            'file_size' => $uploaded->getSize(),
        ]);

        return redirect('/fixed-assets/assignment/' . $asset->asset_id)->with('success', 'Document uploaded!');
    }

    public function downloadDocument($documentId)
    {
        $document = Document::findOrFail($documentId);

        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function deleteDocument($documentId)
    {
        $document = Document::findOrFail($documentId);
        $assetId = $document->asset_id;

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();

        return redirect('/fixed-assets/assignment/' . $assetId)->with('success', 'Document deleted.');
    }
}