<?php

namespace App\Http\Controllers;

use App\Models\FixedAssets\FixedAsset;
use App\Models\FixedAssets\AssetCategory;
use App\Models\FixedAssets\ActivityLog;
use App\Services\GeneralLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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

        // Dynamic Category Breakdown (base sa totoong datos)
        $totalCount = max($allAssets->count(), 1);
        $categoryBreakdown = $allAssets
            ->groupBy(fn($a) => $a->category->category_name ?? 'Uncategorized')
            ->map(fn($group, $name) => [
                'label' => $name,
                'percent' => round($group->count() / $totalCount * 100),
            ])
            ->sortByDesc('percent')
            ->values();

        // Dynamic Status Breakdown (base sa totoong datos, valid statuses lang)
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

        // ✅ Real Recent Activities (mula sa fa_activity_logs table)
        $iconMap = [
            'created'  => ['icon' => 'plus',      'color' => '#1F2937'],
            'updated'  => ['icon' => 'pencil',    'color' => '#3B82F6'],
            'deleted'  => ['icon' => 'trash-2',   'color' => '#EF4444'],
            'disposed' => ['icon' => 'archive',   'color' => '#F5A623'],
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
            'action' => 'created',
            'description' => "New asset {$asset->asset_name} added",
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

        return view('fixed-assets.assignment', compact('assetData'));
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
            'action' => 'updated',
            'description' => "Asset {$asset->asset_name} updated",
            'performed_by' => $this->actor(),
        ]);

        return redirect('/fixed-assets/assignment/' . $asset->asset_id)->with('success', 'Asset successfully updated!');
    }

    public function destroy($id)
    {
        $asset = FixedAsset::findOrFail($id);
        $assetName = $asset->asset_name;

        // Remove every GL entry ever posted for this asset (acquisition,
        // any depreciation periods, and disposal) so the GL doesn't keep
        // balances for an asset that no longer exists.
        $this->gl->reverseAssetEntries($asset);

        $asset->delete();

        ActivityLog::create([
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

        // Gain/Loss = Disposal Value - Book Value (at time of disposal)
        $gainLoss = $validated['disposal_value'] - $asset->book_value;

        $asset->update([
            'status'          => 'disposed',
            'disposal_date'   => $validated['disposal_date'],
            'disposal_value'  => $validated['disposal_value'],
            'disposal_reason' => $validated['disposal_reason'],
            'gain_loss'       => $gainLoss,
        ]);

        // Auto-post the disposal to the General Ledger
        $this->gl->postDisposal($asset->fresh());

        ActivityLog::create([
            'action' => 'disposed',
            'description' => "Asset {$asset->asset_name} disposed",
            'performed_by' => $this->actor(),
        ]);

        return redirect('/fixed-assets')->with('success', 'Asset successfully disposed!');
    }
}