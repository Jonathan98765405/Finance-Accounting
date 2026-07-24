<?php

namespace App\Models\FixedAssets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDepreciationSchedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'schedule_id';
    protected $table = 'fa_asset_depreciation_schedules';

    protected $fillable = [
        'asset_id',
        'period_date',
        'depreciation_expense',
        'accumulated_depreciation',
        'book_value',
    ];

    protected $casts = [
        'period_date' => 'date',
    ];

    public function fixedAsset()
    {
        return $this->belongsTo(FixedAsset::class, 'asset_id', 'asset_id');
    }
}
