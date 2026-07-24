<?php

namespace App\Models\AccountPayable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'ap_payments';

    protected $fillable = [
        'invoice_id', 'reference_number', 'payment_date', 'amount',
        'payment_method', 'bank_account', 'priority', 'status', 'remarks',
        'remittance_number', 'remittance_pdf_path', 'remittance_sent_to', 'remittance_sent_at',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'remittance_sent_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}