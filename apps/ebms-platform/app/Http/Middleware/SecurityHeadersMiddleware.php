<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Generate nonce before the controller runs so views can reference it.
        $nonce = $this->nonce($request);
        view()->share('csp_nonce', $nonce);

        $response = $next($request);

        // Skip the health check endpoint
        if ($request->is('up')) {
            return $response;
        }

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'nonce-{$nonce}'; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "img-src 'self' data: https://students.uasckuexams.in; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none';"
        );

        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        // Remove information-disclosure headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // Remove Allow header on 405 responses (method not allowed)
        if ($response->getStatusCode() === 405) {
            $response->headers->remove('Allow');
        }

        return $response;
    }

    private function nonce(Request $request): string
    {
        if (! $request->attributes->has('csp_nonce')) {
            $request->attributes->set('csp_nonce', base64_encode(random_bytes(16)));
        }

        return $request->attributes->get('csp_nonce');
    }
}
