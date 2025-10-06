<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isPeminjam
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if ($request->user()->isPeminjam()) {
                return $next($request);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }
}
