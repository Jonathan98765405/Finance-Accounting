<?php

namespace App\Models\GeneralLedger;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $table = 'gl_entries';
    protected $guarded = [];

    public function lines()
    {
        return $this->hasMany(EntryLine::class, 'gl_entry_id');
    }
}