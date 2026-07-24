<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'vendor_id',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * vendor_id is a plain reference to the Procurement app's own
     * vendors table (a different database) — there is intentionally
     * no local foreign key / belongsTo() to a Vendor model here.
     */

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }
}