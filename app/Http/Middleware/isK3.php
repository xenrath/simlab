<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isK3
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if ($request->user()->isK3()) {
                return $next($request);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }
}
