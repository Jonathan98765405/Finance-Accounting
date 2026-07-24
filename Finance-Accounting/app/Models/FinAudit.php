<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinAudit extends Model
{
    protected $table = 'fin_audits';

    protected $fillable = [
        'name',
        'audit_year',
        'audit_month',
        'audit_type',
        'priority',
        'scheduled_date',
        'recurrence',
        'auditor',
        'status',
        'date_completed',
        'findings',
        'checklist',
        'notify',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'date_completed' => 'date',
        'checklist'      => 'array',
        'notify'         => 'boolean',
    ];
}
