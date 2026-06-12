<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if ($user && $user->role && $user->role->name === 'administrator') {
            return $next($request);
        }

        if (!$user || !$user->role || !in_array($user->role->name, $roles)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}