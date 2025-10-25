<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (
            ! isAdminCheck() &&
            $user && !$user->isVerifiedEmail()
        ) {

            abort_if($request->expectsJson(), 401, 'You must verified email!');

            return redirect()->route('verify.email.deny');
        }

        return $next($request);
    }
}
