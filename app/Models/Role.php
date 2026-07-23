<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role_key',
        'role_label',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Financial Reports constants.
     */
    public const CAN_MANAGE_FINANCIAL_REPORTS = [
        'administrator',
        'auditor',
        'finance_manager',
    ];
    
    public const CAN_MANAGE_LEDGER = [
        'administrator',
        'accountant',
        'finance_manager',
    ];

    /**
     * NEW: Roles allowed to manage Accounts Receivable.
     * Everyone else is restricted to view-only mode.
     */
    public const CAN_MANAGE_AR = [
        'administrator',
        'ar_staff',
        'finance_manager',
    ];

    /**
     * Check the currently active session role against a permission list.
     */
    public static function activeRoleCan(array $allowedKeys): bool
    {
        $activeKey = session('active_role_key');

        if (!$activeKey) {
            return false;
        }

        return in_array($activeKey, $allowedKeys, true);
    }

    public static function activeRoleCanManageFinancialReports(): bool
    {
        return self::activeRoleCan(self::CAN_MANAGE_FINANCIAL_REPORTS);
    }
    
    public static function activeRoleCanManageLedger(): bool
    {
        return self::activeRoleCan(self::CAN_MANAGE_LEDGER);
    }

    /**
     * NEW: Method helper to verify Accounts Receivable write access.
     */
    public static function activeRoleCanManageAR(): bool
    {
        return self::activeRoleCan(self::CAN_MANAGE_AR);
    }
}