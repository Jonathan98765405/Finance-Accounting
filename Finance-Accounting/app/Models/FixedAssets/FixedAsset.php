<?php

namespace App\Models\FixedAssets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
    use HasFactory;

    protected $table = 'fa_fixed_assets';
    protected $primaryKey = 'asset_id';

    protected $fillable = [
        'asset_tag',
        'asset_name',
        'category_id',
        'acquisition_date',
        'acquisition_cost',
        'salvage_value',
        'useful_life_years',
        'depreciation_method',
        'accumulated_depreciation',
        'book_value',
        'location',
        'status',
        'serial_number',
        'warranty_years',
        'description',
        'condition',
        'disposal_date',
        'disposal_value',
        'disposal_reason',
        'gain_loss',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'acquisition_cost' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'accumulated_depreciation' => 'decimal:2',
        'book_value' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'category_id', 'category_id');
    }

    public function depreciationSchedules()
    {
        return $this->hasMany(AssetDepreciationSchedule::class, 'asset_id', 'asset_id');
    }
}