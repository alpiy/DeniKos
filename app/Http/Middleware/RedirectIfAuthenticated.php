<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Jika admin mengakses halaman login admin, redirect ke dashboard admin
                if ($request->is('admin/auth/login') && $user->role === 'admin') {
                    return redirect()->route('admin.dashboard')
                        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', '0');
                }
                
                // Jika user biasa mengakses halaman login user, redirect ke halaman kos
                if ($request->is('auth/login') && $user->role === 'user') {
                    return redirect()->route('user.kos.index');
                }
                
                // PENTING: Jika admin mengakses halaman apapun selain admin, redirect ke dashboard admin
                if ($user->role === 'admin' && !$request->is('admin*')) {
                    return redirect()->route('admin.dashboard')
                        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', '0');
                }
                
                // Jika user biasa mengakses halaman admin, redirect ke halaman user
                if ($request->is('admin*') && $user->role === 'user') {
                    return redirect()->route('user.kos.index');
                }
                
                // Jika admin mengakses halaman user auth, redirect ke dashboard admin
                if ($request->is('auth/*') && $user->role === 'admin') {
                    return redirect()->route('admin.dashboard')
                        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', '0');
                }
            }
        }

        return $next($request);
    }
}
