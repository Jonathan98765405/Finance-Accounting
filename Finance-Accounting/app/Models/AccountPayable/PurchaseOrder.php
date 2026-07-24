<?php

namespace App\Models\AccountPayable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'ap_purchase_orders';

    protected $fillable = [
        'po_number', 'supplier_id', 'po_date', 'total_amount', 'status',
    ];

    protected $casts = [
        'po_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function goodsReceipts()
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}