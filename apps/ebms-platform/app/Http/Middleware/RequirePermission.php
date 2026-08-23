<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePermission
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user('admin');

        if (! $user || ! $user->canAccess($feature)) {
            abort(403, 'Insufficient permissions.');
        }

        return $next($request);
    }
}
