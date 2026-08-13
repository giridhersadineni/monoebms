<?php

namespace App\Http\Middleware;

use App\Models\FeatureFlag;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureFlag
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $flag = FeatureFlag::firstWhere('name', $feature);

        if ($flag && ! $flag->enabled) {
            return response()->view('student.maintenance', ['flag' => $flag], 503);
        }

        return $next($request);
    }
}
