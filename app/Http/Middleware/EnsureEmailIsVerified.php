<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $redirectToRoute = null): Response
    {
        if (! $request->user() ||
            ($request->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified.')
                    : Redirect::guest(URL::route($redirectToRoute ?: 'auth.verification.notice'));
        }

        return $next($request);
    }
}
