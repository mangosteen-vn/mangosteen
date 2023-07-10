<?php

namespace Mangosteen\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLanguage
{
    public function handle($request, Closure $next)
    {
        if ($request->has('lang')) {
            App::setLocale($request->lang);
        }

        return $next($request);
    }
}
