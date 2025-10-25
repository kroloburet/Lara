<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccessMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $mode = appSettings('access.mode');
        $isNotAllowed = ($mode !== 'allowed') && !isAdminCheck();

        if ($request->expectsJson() && $isNotAllowed) abort(403);

        if ($isNotAllowed) {
            return response(view("{$mode}-mode"));
        }

        return $next($request);
    }
}
