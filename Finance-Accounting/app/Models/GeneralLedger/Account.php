<?php

namespace App\Models\GeneralLedger;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'gl_accounts';
    protected $guarded = [];

    public function entryLines()
    {
        return $this->hasMany(EntryLine::class, 'gl_account_id');
    }
}