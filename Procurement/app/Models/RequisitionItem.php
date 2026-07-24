<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionItem extends Model
{
    use HasFactory;

    protected $fillable = ['requisition_id', 'description', 'quantity', 'unit_price', 'total_price'];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}