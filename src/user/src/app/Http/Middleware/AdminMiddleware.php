<?php
use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($user && in_array($user->role->name, ['super admin', 'admin',])) {
            return $next($request);
        }
        return response()->json(['error' => 'Denied access'], 403);
    }
}
