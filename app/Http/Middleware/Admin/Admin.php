<?php

namespace App\Http\Middleware\Admin;

use Auth;
use Closure;

class Admin
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
        Auth::shouldUse('admin');
        if (!Auth::check()) {
            return redirect(route('admin.logout'));
        }
        return $next($request);
    }
}
