<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('view permissions', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('view roles', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('view categories', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('view permissions', function ($user) {
            return $user->role === 'admin';
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
