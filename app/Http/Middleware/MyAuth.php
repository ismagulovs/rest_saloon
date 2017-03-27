<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class MyAuth
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
        $userId = Session::get('user');

        if(!isset($userId) and $userId != 'default'){
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        }
        return $next($request);
    }
}
