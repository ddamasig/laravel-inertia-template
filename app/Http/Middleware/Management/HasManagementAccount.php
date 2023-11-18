<?php

namespace App\Http\Middleware\Management;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasManagementAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        // Only allow Users who are part of the Management Team
        $user = $request->user();
        if (!empty($user->tenant_id)) {
            return abort(404);
        }

        return $next($request);
    }
}
