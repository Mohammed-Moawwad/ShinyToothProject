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
        // Redirect to admin login if not authenticated
        if (auth()->guard('web')->guest()) {
            return redirect()->route('admin.login.form');
        }

        // Define admin email - change this to your admin email
        $adminEmail = config('admin.email');

        if (auth()->guard('web')->user()->email !== $adminEmail) {
            abort(403, 'Access denied. Admin only.');
        }

        return $next($request);
    }
}
