<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and suspended
        if (auth()->check() && auth()->user()->is_suspended) {
            // Logout the user
            auth()->logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the token
            $request->session()->regenerateToken();

            // Redirect to login with error message
            return redirect()->route('login')->with(
                'error',
                'Your account has been suspended. ' .
                    'Reason: ' . (auth()->user()->suspension_reason ?? 'No reason provided') . '. ' .
                    'Please contact your administrator for more information.'
            );
        }

        return $next($request);
    }
}