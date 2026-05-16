<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('manage-users', function (User $user) {
            return $user->isSuperAdmin() || $user->isAdmin();
        });

        Gate::define('manage-master', function (User $user) {
            return $user->isSuperAdmin() || $user->isAdmin();
        });

        Gate::define('manage-rbac', function (User $user) {
            return $user->isSuperAdmin();
        });
    }
}