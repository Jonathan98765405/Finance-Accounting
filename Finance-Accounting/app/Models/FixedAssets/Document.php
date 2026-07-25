<?php

namespace App\Models\FixedAssets;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'fa_documents';

    protected $fillable = [
        'asset_id',
        'file_name',
        'file_path',
        'type',
        'description',
        'uploaded_by',
        'file_size',
    ];

    public function asset()
    {
        return $this->belongsTo(FixedAsset::class, 'asset_id', 'asset_id');
    }

    // Human-readable file size, e.g. "245 KB", "1.2 MB"
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024) . ' KB';
        }
        return $bytes . ' B';
    }
    public function getFileUrlAttribute(): string
    {
        return \Storage::disk('public')->url($this->file_path);
    }
}
