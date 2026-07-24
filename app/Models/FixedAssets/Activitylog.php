<?php

namespace App\Models\FixedAssets;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'fa_activity_logs';

    protected $fillable = [
        'action',
        'description',
        'performed_by',
    ];
}