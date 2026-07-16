<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockAdminUkmCreate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->isAdminUkm()) {
            $routeName = $request->route() ? $request->route()->getName() : '';

            if ($routeName) {
                // If checking other create/store/add routes:
                if (
                    str_ends_with($routeName, '.create') ||
                    str_ends_with($routeName, '.store') ||
                    str_ends_with($routeName, '.materials.add')
                ) {
                    abort(403, 'Akses ditolak: Admin UKM tidak diperbolehkan menambahkan data baru pada menu Pengurus.');
                }
            }
        }

        return $next($request);
    }
}
