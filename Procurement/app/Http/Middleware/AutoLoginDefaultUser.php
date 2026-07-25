<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLoginDefaultUser
{
    /**
     * If nobody is logged in yet, automatically log in the first existing
     * user in the database. This exists so features that call auth()->id()
     * (like PurchaseOrderController::store()'s created_by) always get a
     * real user without requiring a manual login step or a database change.
     *
     * NOTE: this is a development convenience only. It effectively removes
     * real authentication for anyone using the app locally — do not deploy
     * this to a server other people can reach.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            $defaultUser = User::orderBy('id')->first();

            if ($defaultUser) {
                Auth::login($defaultUser);
            }
        }

        return $next($request);
    }
}