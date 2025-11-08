<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $requiredKey = config('app.api_access_key', env('API_ACCESS_KEY'));

        // misal pakai header X-Access-Key
        $providedKey = $request->header('X-Access-Key');

        if (!$requiredKey || $providedKey !== $requiredKey) {
            return response()->json([
                'message' => 'Unauthorized: invalid access key',
            ], 401);
        }

        return $next($request);
    }
}
