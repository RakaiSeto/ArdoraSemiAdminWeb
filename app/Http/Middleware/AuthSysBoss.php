<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthSysBoss
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
        if (isset(AUTH::user()->privilege) && ((AUTH::user()->privilege == 'ROOT') || (AUTH::user()->privilege == 'SYSADMIN') || (AUTH::user()->privilege == 'SYSFINANCE') || (AUTH::user()->privilege == 'SYSOP') || (AUTH::user()->privilege == 'B2B_USER') || (AUTH::user()->privilege == 'B2B_RESELLER'))) {
//            error_log('AuthSysBoss: ' . AUTH::user()->privilege);
            return $next($request);
        } else {
//            error_log('AuthSysBoss: ' . AUTH::user()->privilege);
            Auth::logout();
            return redirect('/login');
        }
    }
}
