<?php

namespace App;

use MadWeb\Initializer\Contracts\Runner;

class Update
{
    public function production(Runner $run)
    {
        $run->external('composer', 'install', '--no-dev', '--prefer-dist', '--optimize-autoloader', '--ignore-platform-reqs')
            ->external('npm', 'install', '--production','--legacy-peer-deps')
            ->external('npm', 'run', 'production')
            ->artisan('route:cache')
            ->artisan('config:cache')
            ->artisan('event:cache')
            ->artisan('migrate', ['--force' => true])
            ->artisan('cache:clear')
            ->artisan('queue:restart'); // ->artisan('horizon:terminate');
    }

    public function local(Runner $run)
    {
        $run->external('composer', 'install', '--ignore-platform-reqs')
            ->external('npm', 'install', '--legacy-peer-deps')
            ->external('npm', 'run', 'development')
            ->artisan('migrate')
            ->artisan('cache:clear');
    }
}
