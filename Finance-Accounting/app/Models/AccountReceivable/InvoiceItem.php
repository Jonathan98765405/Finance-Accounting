<?php

namespace App\Models\AccountReceivable;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ar_invoice_items';

    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}