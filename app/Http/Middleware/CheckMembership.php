<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMembership
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Admins and Pengurus always have access
        if ($user->isSuperAdmin() || $user->isAdminUkm() || $user->isPengurus()) {
            return $next($request);
        }

        // If regular member, must be active
        $member = $user->member;
        if (!$member || $member->status !== 'Anggota Aktif') {
            abort(403, 'Akses ditolak. Anda belum menjadi anggota aktif PSUP.');
        }

        return $next($request);
    }
}