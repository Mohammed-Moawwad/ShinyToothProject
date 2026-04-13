<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define admin email - change this to your admin email
        $adminEmail = env('ADMIN_EMAIL', 'admin@shinytooth.com');

        if (auth()->guest() || auth()->user()->email !== $adminEmail) {
            abort(403, 'Access denied. Admin only.');
        }

        return $next($request);
    }
}
