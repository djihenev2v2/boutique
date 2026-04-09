<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsClient
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isClient()) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
