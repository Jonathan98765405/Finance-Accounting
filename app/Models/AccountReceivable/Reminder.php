<?php

namespace App\Models\AccountReceivable;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ar_reminders';

    protected $fillable = [
        'customer_id',
        'invoice_id',
        'message',
    ];
}