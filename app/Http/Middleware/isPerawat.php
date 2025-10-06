<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isPerawat
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if ($request->user()->isPerawat()) {
                return $next($request);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }
}
