<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if (
            $request
                ->user()
                ->roles()
                ->select('id', 'name')
                ->first()?->name !== 'ADMIN'
        ) {
            return response()->json(
                [
                    'message' => 'Unauthenticated.',
                ],
                403
            );
        }
        return $next($request);
    }
}
