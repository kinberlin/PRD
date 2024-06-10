<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /*public function handle($request, Closure $next, $role)
    {
        if ($request->user() && $request->user()->role != $role) {
            return response('Unauthorized.', 401);
        }
    
        return $next($request);
    }*/
    public function handle($request, Closure $next, ...$roles)
    {
        
        if (Auth::check() && Auth::user()->access ==1 && in_array(Auth::user()->role, $roles)) {
            if (Auth::user()->role == 2) {
                    return $next($request);
            } else {
                return $next($request);
            }
        }
        return redirect("/notfound")->with('error', 'You do not have permission to access that page.');
    }
}