<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect('admin');
            } elseif (auth()->user()->isKalab()) {
                return redirect('kalab');
            } elseif (auth()->user()->isLaboran()) {
                return redirect('laboran');
            } elseif (auth()->user()->isPeminjam()) {
                return redirect('peminjam');
            } else {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
