<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckForMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    public function handle(Request $request, Closure $next)
    {
        if ($this->app->isDownForMaintenance() && ! $this->isIpWhiteListed()) {
            throw new HttpException(503);
        }

        return $next($request);
    }
    private function isIpWhiteListed()
    {
        $ip = Request::getClientIp();
        $allowed = explode(',', getenv('WHEN_DOWN_WHITELIST_THIS_IPS'));

        return in_array($ip, $allowed);
    }
}
