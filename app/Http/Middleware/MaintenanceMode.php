<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!env('SITE_UNDER_CONSTRUCTION', false)) {
            return $next($request);
        }

        // Admin routes sempre accessibili (login incluso)
        if ($request->is('admin*')) {
            return $next($request);
        }

        // Utenti loggati bypassano la manutenzione
        if (Auth::check()) {
            return $next($request);
        }

        return response()->view('maintenance', [], 503);
    }
}