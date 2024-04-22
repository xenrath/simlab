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
                // if (auth()->user()->isBidan()) {
                //     return redirect('peminjam/bidan');
                // } elseif (auth()->user()->isPerawat()) {
                //     return redirect('peminjam/perawat');
                // } elseif (auth()->user()->isK3()) {
                //     return redirect('peminjam/k3');
                // } elseif (auth()->user()->isFarmasi()) {
                //     return redirect('peminjam/farmasi');
                // }
                if (auth()->user()->isLabTerpadu()) {
                    return redirect('peminjam/labterpadu');
                } elseif (auth()->user()->isFarmasi()) {
                    return redirect('peminjam/farmasi');
                }
            } else {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
