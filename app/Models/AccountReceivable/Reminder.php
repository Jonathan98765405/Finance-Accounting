<?php

namespace App\Models\AccountReceivable;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = 'ar_reminders';

    protected $fillable = [
        'customer_id',
        'invoice_id',
        'status',
        'message',
    ];
}