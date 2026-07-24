<?php

namespace App\Models\FixedAssets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    use HasFactory;

    protected $table = 'fa_asset_categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'description',
        'default_useful_life',
    ];

    public function fixedAssets()
    {
        return $this->hasMany(FixedAsset::class, 'category_id', 'category_id');
    }
}
