<?php

namespace App\Models\AccountPayable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'ap_activities';

    protected $fillable = [
        'invoice_id', 'description', 'type', 'status',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}