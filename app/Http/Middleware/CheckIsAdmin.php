<?php

namespace App\Http\Middleware;

use function auth;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @package App\Http\Middleware
 */
class CheckIsAdmin
{
    /**
     * @param $request
     * @param Closure $next
     *
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->is_admin) {
            return $next($request);
        }

        return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
    }
}
