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
<<<<<<< HEAD:app/Models/Role.php
     * Roles allowed to manage Accounts Payable.
     * Everyone else is restricted to view-only mode.
=======
     * NEW: Roles allowed to manage Accounts Payable.
>>>>>>> df559247cea13dad1c9e7ba8fb183e7aab709ff6:Finance-Accounting/app/Models/Role.php
     */
    public const CAN_MANAGE_AP = [
        'administrator',
        'ap_staff',
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

    /**
<<<<<<< HEAD:app/Models/Role.php
     * Method helper to verify Accounts Payable write access.
=======
     * NEW: Method helper to verify Accounts Payable write access.
>>>>>>> df559247cea13dad1c9e7ba8fb183e7aab709ff6:Finance-Accounting/app/Models/Role.php
     */
    public static function activeRoleCanManageAP(): bool
    {
        return self::activeRoleCan(self::CAN_MANAGE_AP);
    }
}