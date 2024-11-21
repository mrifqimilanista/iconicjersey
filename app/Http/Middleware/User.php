<?php

namespace App\Http\Middleware;

use Closure;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty(session('user'))) {
            // Log the session data for debugging
            \Log::info('Session data:', session()->all());

            return redirect()->route('login.form');
        }

        return $next($request);
    }
}
