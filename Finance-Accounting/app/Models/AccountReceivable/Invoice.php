<?php

namespace App\Models\AccountReceivable;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ar_invoices';

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'invoice_date',
        'due_date',
        'payment_terms',
        'subtotal',
        'tax',
        'total',
        'balance',
        'status',
        'notes',
        'sales_invoice_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
}