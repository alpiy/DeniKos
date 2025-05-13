<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next, string $role): Response
{
    $user = $request->user();

    if (!$user) {
        return redirect()->route('auth.login.form');
    }

    if ($user->role !== $role) {
        abort(403, 'Unauthorized action.');
    }

    return $next($request);
}
}
