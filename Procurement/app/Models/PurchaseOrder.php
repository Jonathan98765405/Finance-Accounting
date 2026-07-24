<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['po_number', 'vendor_id', 'requisition_id', 'created_by', 'total_amount', 'status', 'ap_synced_at'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }
}