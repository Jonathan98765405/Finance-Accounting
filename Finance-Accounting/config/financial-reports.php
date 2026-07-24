<?php

// Save this file as: config/financial-reports.php

return [

    /*
    |--------------------------------------------------------------------------
    | Financial Reports — Write Access
    |--------------------------------------------------------------------------
    |
    | Only these roles (matched against roles.role_key, i.e. the value stored
    | in Session::get('active_role_key') by AccountController@switchRole) are
    | allowed to create, edit, or delete records in the Financial Reports
    | submodule (audits, tax calendar filings, etc).
    |
    | Everyone else gets read-only access: the UI stays visible, but any
    | write attempt (button click or direct API call) is blocked with an
    | "Access Denied" message.
    |
    | IMPORTANT: update the values below to match the exact role_key values
    | in your `roles` table (e.g. 'admin' vs 'administrator').
    |
    */

    'write_roles' => [
        'administrator',
        'finance-manager',
    ],

];
