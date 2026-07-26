<?php

namespace App\Models\FixedAssets;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'fa_assignments';

    protected $fillable = [
        'asset_id',
        'assigned_to',
        'department',
        'location',
        'date_assigned',
        'cost_center',
        'remarks',
    ];

    protected $casts = [
        'date_assigned' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(FixedAsset::class, 'asset_id', 'asset_id');
    }

    // Pang-avatar initials gaya ng "JD" para kay Juan Dela Cruz
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->assigned_to));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
        return $initials ?: '?';
    }
}