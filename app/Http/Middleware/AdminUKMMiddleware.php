<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Membership;

class AdminUKMMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Cek ukm_id dari request query atau session
        $ukmId = $request->query('ukm_id') ?? session('managed_ukm_id');
        
        if (!$ukmId) {
            // Kalau tidak ada di query, cari ukm pertama dimana dia jadi admin
            $membership = Membership::where('user_id', $user->id)
                ->where('role_in_ukm', 'admin')
                ->where('status', 'approved')
                ->first();
                
            if ($membership) {
                $ukmId = $membership->ukm_id;
            } elseif ($user->isSuperAdmin()) {
                $firstUkm = \App\Models\UKM::first();
                if ($firstUkm) $ukmId = $firstUkm->id;
            }
        }

        if (!$ukmId) {
            abort(403, 'Anda bukan admin dari UKM manapun.');
        }

        if (!$user->isSuperAdmin()) {
            // Pastikan user ini benar-benar admin dari ukmId tersebut
            $hasAccess = Membership::where('user_id', $user->id)
                ->where('ukm_id', $ukmId)
                ->where('role_in_ukm', 'admin')
                ->where('status', 'approved')
                ->exists();

            if (!$hasAccess) {
                abort(403, 'Akses ditolak. Anda bukan admin UKM ini.');
            }
        }

        // Simpan ke session biar gak usah selalu kirim query param
        session(['managed_ukm_id' => $ukmId]);
        
        // Pass the ukm_id to the request attributes so controllers can use it
        $request->attributes->set('managed_ukm_id', $ukmId);

        return $next($request);
    }
}
