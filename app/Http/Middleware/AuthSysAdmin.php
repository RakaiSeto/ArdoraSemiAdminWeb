<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthSysAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (isset(AUTH::user()->privilege) && AUTH::user()->privilege == 'SYSADMIN') {
            return $next($request);
        } else {
            Auth::logout();
            return redirect('/login');
        }
    }
}
