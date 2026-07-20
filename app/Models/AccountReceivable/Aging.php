<?php

namespace App\Models\AccountReceivable;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Aging extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ar_receivables';

    protected $fillable = [
        'invoice_no',
        'customer_name',
        'invoice_date',
        'due_date',
        'total_amount',
        'balance',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function getAgingAttribute()
    {
        if ($this->due_date->isFuture() || $this->due_date->isToday()) {
            return 'Current';
        }

        $days = Carbon::now()->diffInDays($this->due_date);

        if ($days <= 30) {
            return '31-60 Days';
        }

        if ($days <= 60) {
            return '61-90 Days';
        }

        return '90+ Days';
    }

    public function getStatusAttribute()
    {
        switch ($this->aging) {
            case 'Current':
                return 'Current';

            case '31-60 Days':
                return 'Pending';

            case '61-90 Days':
                return 'Overdue';

            default:
                return 'Critical';
        }
    }
}