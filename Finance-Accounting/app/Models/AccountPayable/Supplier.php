<?php

namespace App\Models\AccountPayable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'ap_suppliers';

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'payment_terms',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}