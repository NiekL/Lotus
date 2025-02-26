<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Check of de gebruiker ingelogd is en minimaal één van de opgegeven rollen heeft
        if (!$user || !$user->hasAnyRole($roles, 'web')) {
            abort(403, 'Onvoldoende rechten.');
        }

        return $next($request);
    }
}
