<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isBidan
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if ($request->user()->isBidan()) {
                return $next($request);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }
}
