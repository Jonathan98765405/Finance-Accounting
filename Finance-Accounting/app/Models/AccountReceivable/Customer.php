<?php

namespace App\Models\AccountReceivable;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ar_customers';

    protected $fillable = [
        'customer_name',
        'company',
        'address',
        'email',
        'phone',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}