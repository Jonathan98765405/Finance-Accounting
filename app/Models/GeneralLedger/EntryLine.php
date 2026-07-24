<?php

namespace App\Models\GeneralLedger;

use Illuminate\Database\Eloquent\Model;

class EntryLine extends Model
{
    protected $table = 'gl_entry_lines';
    protected $guarded = [];

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'gl_entry_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'gl_account_id');
    }
}