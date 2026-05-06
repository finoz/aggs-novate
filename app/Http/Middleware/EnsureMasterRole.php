<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureMasterRole
{
    public function handle(Request $request, Closure $next)
    {
        abort_if(! $request->user()?->hasRole('master'), 403);
        return $next($request);
    }
}
