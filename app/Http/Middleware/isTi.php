<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isTi
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->isTi()) {
            return $next($request);
        } else {
            return redirect('/');
        }
    }
}
