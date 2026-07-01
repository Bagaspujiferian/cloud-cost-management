<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}
