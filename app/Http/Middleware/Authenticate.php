<?php

namespace App\Http\Middleware;

use Closure;
use App\Events\SessionExpired;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $userId = Auth::id();
            if ($userId) {
                event(new SessionExpired($userId));
            }
            return route('login');
        }
    }
    // public function handle($request, Closure $next, ...$guards)
    // {
    //     $response = $next($request);

    //     if (!Auth::check()) {
    //         $userId = Auth::id(); // Retrieve the user ID

    //         if ($userId) {
    //             event(new SessionExpired($userId));
    //         }
    //     }

    //     return $response;
    // }
}
