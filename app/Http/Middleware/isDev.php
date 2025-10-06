<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isDev
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if ($request->user()->isDev()) {
                return $next($request);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }
}
