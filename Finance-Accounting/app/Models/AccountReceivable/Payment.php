<?php

namespace App\Models\AccountReceivable;

use Illuminate\Database\Eloquent\Model;
use App\Models\AccountReceivable\Invoice;
use App\Models\AccountReceivable\Customer;

class Payment extends Model
{
    protected $table = 'ar_payments';

    protected $fillable = [
        'invoice_id',
        'customer_id',
        'payment_date',
        'payment_method',
        'reference_no',
        'amount',
        'remarks'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}