<?php

namespace Mangosteen\User\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class AuthenticateMiddleware extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string|null
     */
    // Add new method
    protected function unauthenticated($request, array $guards): ?string
    {
        abort(response()->json(['message' => 'Unauthenticated',], 401));
    }

}
