<?php

namespace App\Models\FixedAssets;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'fa_activity_logs';

    protected $fillable = [
        'asset_id',
        'action',
        'description',
        'performed_by',
    ];

    public function asset()
    {
        return $this->belongsTo(FixedAsset::class, 'asset_id', 'asset_id');
    }
}