<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinTaxCalendar extends Model
{
    protected $table = 'fin_tax_calendar';

    protected $fillable = [
        'label',
        'due_date',
        'amount',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date:Y-m-d',
        'amount'   => 'decimal:2',
    ];
}
