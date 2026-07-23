<?php

namespace App\Http\Controllers;

use App\Models\FixedAssets\FixedAsset;
use App\Models\FixedAssets\AssetCategory;
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

    public function index()
{
    if (Schema::hasTable('fa_fixed_assets')) {
        $fixedAssets = FixedAsset::with('category')->get();
    } else {
        $fixedAssets = collect();
    }

    $statusMap = [
        'active' => 'Active',
        'disposed' => 'Disposed',
        'under_maintenance' => 'Under Maintenance',
    ];

    $stats = [
        ['label' => 'Total Assets', 'value' => $fixedAssets->count(), 'icon' => 'fa-warehouse', 'color' => '#22B57A'],
        ['label' => 'Total Assets Value', 'value' => '₱' . number_format($fixedAssets->sum('acquisition_cost'), 2), 'icon' => 'fa-dollar-sign', 'color' => '#22B57A'],
        ['label' => 'Accumulated Depreciation', 'value' => '₱' . number_format($fixedAssets->sum('accumulated_depreciation'), 2), 'icon' => 'fa-chart-line', 'color' => '#22B57A'],
        ['label' => 'Under Maintenance', 'value' => $fixedAssets->where('status', 'under_maintenance')->count(), 'icon' => 'fa-screwdriver-wrench', 'color' => '#F5A623'],
    ];

    $assets = $fixedAssets->map(function ($asset) use ($statusMap) {
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

    return view('fixed-assets.index', compact('stats', 'assets'));
}
    public function create()
    {
        $categories = AssetCategory::all();

        $lastAsset = FixedAsset::orderBy('id', 'desc')->first();
        $nextNumber = $lastAsset ? $lastAsset->id + 1 : 1;

        $tag = 'FA-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        
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

        $nextNumber = FixedAsset::count() + 1;
        $tag = 'FA-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $asset = FixedAsset::create([
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

        // Auto-post the acquisition to the General Ledger
        $this->gl->postAssetAcquisition($asset);

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

        return redirect('/fixed-assets/assignment/' . $asset->asset_id)->with('success', 'Asset successfully updated!');
    }

    public function destroy($id)
    {
        $asset = FixedAsset::findOrFail($id);

        // Remove every GL entry ever posted for this asset (acquisition,
        // any depreciation periods, and disposal) so the GL doesn't keep
        // balances for an asset that no longer exists.
        $this->gl->reverseAssetEntries($asset);

        $asset->delete();

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

    return redirect('/fixed-assets')->with('success', 'Asset successfully disposed!');
}
}