<?php

namespace App\Models\AccountPayable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'ap_invoices';

    protected $fillable = [
        'invoice_number', 'supplier_id', 'purchase_order_id', 'invoice_date', 'due_date',
        'payment_terms', 'currency', 'department', 'supplier_reference', 'status',
        'subtotal', 'tax', 'discount', 'total_amount', 'remarks', 'verification_remarks',
        'po_matched', 'grn_matched', 'invoice_matched', 'match_result',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'po_matched' => 'boolean',
        'grn_matched' => 'boolean',
        'invoice_matched' => 'boolean',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function goodsReceipt()
    {
        // Convenience accessor: the GRN tied to this invoice's PO.
        // Table names here are resolved from GoodsReceipt's and
        // PurchaseOrder's own $table properties automatically, so this
        // relationship needed no changes beyond those two models being fixed.
        return $this->hasOneThrough(
            GoodsReceipt::class,
            PurchaseOrder::class,
            'id',            // PurchaseOrder.id
            'purchase_order_id', // GoodsReceipt.purchase_order_id
            'purchase_order_id', // Invoice.purchase_order_id
            'id'              // PurchaseOrder.id
        );
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function documents()
    {
        return $this->hasMany(InvoiceDocument::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function isOverdue(): bool
    {
        return $this->due_date?->isPast() && !in_array($this->status, ['paid']);
    }
}