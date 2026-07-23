<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    /**
     * Return the list of roles.
     */
    public function roles()
    {
        return response()->json(
            Role::orderBy('role_label')->get(['role_key', 'role_label'])
        );
    }

    /**
     * Switch the active role.
     */
    public function switchRole(Request $request)
    {
        $request->validate([
            'role_key' => 'required|string|exists:roles,role_key',
            'password' => 'required|string',
        ]);

        $role = Role::where('role_key', $request->role_key)->first();

        if (!Hash::check($request->password, $role->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong password.',
            ], 422);
        }

        // Store active role in session
        Session::put('active_role_key', $role->role_key);
        Session::put('active_role_label', $role->role_label);
        
        // Force session to save immediately to ensure persistence across page refreshes
        Session::save();

        return response()->json([
            'success' => true,
            'role_key' => $role->role_key,
            'role_label' => $role->role_label,
            'message' => "Switched to {$role->role_label}.",
        ]);
    }
}