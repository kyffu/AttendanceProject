<?php

use App\Models\Roles;
use Illuminate\Support\Facades\Auth;

if (! function_exists('hasRole')) {
    /**
     * Check if the authenticated user has a specific role or any of the given roles.
     *
     * @param  mixed  $roles  Role name or array of role names.
     * @return bool
     */
    function hasRole($roles)
    {
        $user = Auth::user();
        
        if (!$user) {
            return false; // No user authenticated
        }

        // Ambil nama role berdasarkan role_id user
        $roleName = Roles::find($user->role_id)?->slug;

        $roles = is_array($roles) ? $roles : func_get_args();
        
        return in_array($roleName, $roles);
    }
}
