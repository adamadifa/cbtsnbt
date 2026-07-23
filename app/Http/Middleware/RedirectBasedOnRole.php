<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            if ($request->user()->hasAnyRole(['admin', 'super_admin'])) {
                if ($request->routeIs('dashboard')) {
                    return redirect()->route('admin.dashboard');
                }
            } else {
                if ($request->routeIs('admin.*')) {
                    return redirect()->route('dashboard');
                }
            }
        }

        return $next($request);
    }
}
