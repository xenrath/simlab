<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isKalab
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if ($request->user()->isKalab()) {
                return $next($request);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }
}
