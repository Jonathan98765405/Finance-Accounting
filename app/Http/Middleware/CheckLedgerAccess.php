<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class CheckLedgerAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // If the user does not have permission, abort with a 403 Access Denied.
        if (!Role::activeRoleCanManageLedger()) {
            abort(403, 'Access Denied: You do not have permission to manage this module.');
        }

        return $next($request);
    }
}