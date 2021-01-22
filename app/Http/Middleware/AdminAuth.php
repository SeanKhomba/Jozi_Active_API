<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Session;
use Closure;

class AdminAuth
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
        if (Auth::guest())
        {
            if ($request->ajax() || $request->wantsJson())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                return redirect()->guest('login');
            }
        }
        elseif (Auth::user()->user_type_id != 1)
        {
            if ($request->ajax() || $request->wantsJson())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                return redirect()->guest('login');
            }
        }
	    if((Auth::user()->user_type_id === 1)){
		    Session::put('admin' , 'auper');
	    }
        return $next($request);
    }
}
