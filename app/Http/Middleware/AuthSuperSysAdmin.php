<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthSuperSysAdmin
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
        if (isset(AUTH::user()->privilege) && AUTH::user()->privilege == 'ROOT') {
            return $next($request);
        } else {
            Auth::logout();
            return redirect('/login');
        }
    }
}
