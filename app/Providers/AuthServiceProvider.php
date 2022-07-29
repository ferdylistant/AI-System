<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('do_read', function ($user, $url) {
            return session('access')->where('url', $url)
                    ->where('type', 'Read')->isNotEmpty();
        });

        Gate::define('do_create', function ($user, $raw) {
            return session('permissions')->where('raw', $raw)
                    ->isNotEmpty();
        });

        Gate::define('do_update', function ($user, $raw) {
            return session('permissions')->where('raw', $raw)
                    ->isNotEmpty();
        });

        Gate::define('do_delete', function ($user, $raw) {
            return session('permissions')->where('raw', $raw)
                    ->isNotEmpty();
        });

        Gate::define('do_approval', function ($user, $raw) {
            return session('permissions')->where('raw', $raw)
                    ->isNotEmpty();
        });

    }
}
