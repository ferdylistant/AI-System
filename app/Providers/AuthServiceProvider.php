<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
            // return session('access')->where('url', $url)
            //         ->where('type', 'Read')->isNotEmpty();
            $collect = Redis::get('access');
            return collect($collect)->where('url', $url)
                    ->where('type', 'Read')->isNotEmpty();
        });
        Gate::define('do_read_raw', function ($user, $raw) {
            // return session('permissions')->where('raw', $raw)
            //         ->isNotEmpty();
            $collect = json_decode(Redis::get('permissions'));
            return collect($collect)->where('raw', $raw)
                    ->isNotEmpty();
        });

        Gate::define('do_create', function ($user, $raw) {
            // return session('permissions')->where('raw', $raw)
            //         ->isNotEmpty();
            $collect = json_decode(Redis::get('permissions'));
            return collect($collect)->where('raw', $raw)
                    ->isNotEmpty();
        });

        Gate::define('do_update', function ($user, $raw) {
            $collect = json_decode(Redis::get('permissions'));
            return collect($collect)->where('raw', $raw)
                    ->isNotEmpty();
        });

        Gate::define('do_delete', function ($user, $raw) {
            $collect = json_decode(Redis::get('permissions'));
            return collect($collect)->where('raw', $raw)
                    ->isNotEmpty();
        });

        Gate::define('do_approval', function ($user, $raw) {
            $collect = json_decode(Redis::get('permissions'));
            return collect($collect)->where('raw', $raw)
                    ->isNotEmpty();
        });

        Gate::define('do_decline', function ($user, $raw) {
            $collect = json_decode(Redis::get('permissions'));
            return collect($collect)->where('raw', $raw)
                    ->isNotEmpty();
        });
    }
}
