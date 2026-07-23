<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Return the list of roles for populating the "Switch Account" dropdown.
     * (No passwords are ever sent to the browser.)
     */
    public function roles()
    {
        return response()->json(
            Role::orderBy('role_label')->get(['role_key', 'role_label'])
        );
    }

    /**
     * Switch the active role for the CURRENT session, without logging out.
     *
     * Expects: role_key, password
     */
    public function switchRole(Request $request)
    {
        $validated = $request->validate([
            'role_key' => ['required', 'string', 'exists:roles,role_key'],
            'password' => ['required', 'string'],
        ]);

        $role = Role::where('role_key', $validated['role_key'])->firstOrFail();

        if (! Hash::check($validated['password'], $role->password)) {
            throw ValidationException::withMessages([
                'password' => ['The password you entered is incorrect for this role.'],
            ]);
        }

        // Store the active role in the session (no logout, no re-login required).
        Session::put('active_role_key', $role->role_key);
        Session::put('active_role_label', $role->role_label);

        return response()->json([
            'success'    => true,
            'role_key'   => $role->role_key,
            'role_label' => $role->role_label,
            'message'    => "Switched to {$role->role_label}.",
        ]);
    }
}
