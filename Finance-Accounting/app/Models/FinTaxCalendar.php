<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinTaxCalendar extends Model
{
    protected $table = 'fin_tax_calendar';

    protected $fillable = [
        'label',
        'due_date',
        'tax_year',
        'tax_month',
        'amount',
        'status',
    ];

    protected $casts = [
        'due_date'  => 'date:Y-m-d',
        'tax_year'  => 'integer',
        'tax_month' => 'integer',
        'amount'    => 'decimal:2',
    ];
}