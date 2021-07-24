<?php

namespace bachphuc\PhpLaravelHelpers\Middleware;

use Closure;
use Session;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(auth()->check()){
            return $next($request);
        }
        // Session::flash('error', trans('message.you_do_not_have_permission_to_do_that'));
        return redirect()->route('login');
        
        // if(auth()->check() && auth()->user()->isAdmin()){
        //     return $next($request);
        // }
        // // Session::flash('error', trans('message.you_do_not_have_permission_to_do_that'));
        // return redirect()->route('admin.login');
    }
}