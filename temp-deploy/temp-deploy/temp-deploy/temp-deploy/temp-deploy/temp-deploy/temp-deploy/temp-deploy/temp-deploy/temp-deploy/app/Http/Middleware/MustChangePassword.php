<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and must change password
        if (auth()->check() && auth()->user()->must_change_password) {
            // Allow access to change password route and logout
            if (!$request->routeIs('change-password') &&
                !$request->routeIs('change-password.update') &&
                !$request->routeIs('logout')) {
                return redirect()->route('change-password')
                    ->with('warning', 'Anda harus mengganti password default terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
