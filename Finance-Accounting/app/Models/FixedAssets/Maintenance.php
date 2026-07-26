<?php

namespace App\Models\FixedAssets;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $table = 'fa_maintenance_records';

    protected $fillable = [
        'asset_id',
        'maintenance_type',
        'technician',
        'priority',
        'estimated_cost',
        'actual_cost',
        'scheduled_date',
        'completed_date',
        'description',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(FixedAsset::class, 'asset_id', 'asset_id');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
