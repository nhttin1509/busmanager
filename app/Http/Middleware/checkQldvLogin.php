<?php

namespace App\Http\Middleware;

use Closure;

class checkQldvLogin
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
        if(session()->has('qldv.islogin')){
            return $next($request);
        }
        return redirect('/qldv/login');
    }
}
