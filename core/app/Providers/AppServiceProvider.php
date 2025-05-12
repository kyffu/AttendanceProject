<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\Roles;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    Blade::if('role', function ($roles) {
        $roles = is_array($roles) ? $roles : func_get_args();

        $user = auth()->user();
        if (!$user) return false;

        // Ambil nama role berdasarkan role_id user
        $roleName = Roles::find($user->role_id)?->slug;

        return in_array($roleName, $roles);
    });
}
}
