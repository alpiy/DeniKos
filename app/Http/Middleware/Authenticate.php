<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Jika user belum login
        if (!Auth::check()) {
            if ($request->is('admin*')) {
                return redirect()->route('admin.auth.login');
            }
            return redirect()->route('auth.login.form');
        }

        return $next($request);
    }
}
