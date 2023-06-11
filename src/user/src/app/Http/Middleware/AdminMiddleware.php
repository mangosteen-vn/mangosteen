<?php
namespace Mangosteen\User\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class AdminMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user && ($user->isAdmin() || $user->isSuperAdmin())) {
            return $next($request);
        }

        return response()->json(['error' => 'Denied access'], 403);
    }
}
