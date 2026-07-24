<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'billing_address',
        'billing_email',
        'billing_phone',
        'invoice_date',
        'due_date',
        'payment_terms',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'subtotal'     => 'decimal:2',
        'tax_amount'   => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return '₱' . number_format((float) $this->total_amount, 2);
    }
}