<?php

namespace App\Models\AccountPayable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    use HasFactory;

    protected $table = 'ap_goods_receipts';

    protected $fillable = [
        'grn_number', 'purchase_order_id', 'receipt_date', 'total_amount',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}