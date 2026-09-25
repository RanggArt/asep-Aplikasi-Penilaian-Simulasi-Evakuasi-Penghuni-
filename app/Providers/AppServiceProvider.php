<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('manage-apem', function ($user): bool {
            return in_array($user->role, ['admin', 'super_admin'], true);
        });

        Gate::define('manage-app-settings', fn ($user): bool => $user->role === 'super_admin');
    }
}
