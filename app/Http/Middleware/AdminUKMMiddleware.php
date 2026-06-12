<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminUKMMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !($user->isAdminUkm() || $user->isSuperAdmin())) {
            abort(403, 'Akses ditolak. Anda bukan Admin UKM.');
        }

        return $next($request);
    }
}
