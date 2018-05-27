<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('user')->user()->status!=2) {
            return redirect()->route('UserCenterGetIndex')
					->withErrors('审核还未通过，不能使用投标及招标管理');
        }

        return $next($request);
    }
}
