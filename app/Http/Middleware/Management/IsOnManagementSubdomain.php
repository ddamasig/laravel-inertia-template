<?php

namespace App\Http\Middleware\Management;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsOnManagementSubdomain
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

        $url = $request->getHost();
        $parts = explode('.', $url);
        if (!(count($parts) > 0)) {
            abort(404);
        }

        $subdomain = $parts[0];
        if ($subdomain !== env('APP_MANAGEMENT_SUBDOMAIN')) {
            abort(404);
        }
        // Only allow Users who are part of the Management Team
//        $user = $request->user();
//        if (!empty($user->tenant_id)) {
//            return abort(404);
//        }

        return $next($request);
    }
}
