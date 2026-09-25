<?php

namespace App\Http\Middleware;

use App\Support\AsepAvailability;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAsepIsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! AsepAvailability::enabled()) {
            return redirect()->route('home')->with('asep_disabled', 'Aplikasi ASEP sedang dinonaktifkan sementara.');
        }

        return $next($request);
    }
}
