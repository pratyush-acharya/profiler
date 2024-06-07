<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class studentCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     //if(\Auth::user()->hasAnyRoles(['student'])){
    //         return $next($request);
    //     //}
    //     //return redirect('home');
    // }

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if(Auth::user()->role == 'student')
            return $next($request);
        elseif(Auth::user()->role == 'admin')
            return redirect('/login');
        else
            return redirect('/login');
    }
}
