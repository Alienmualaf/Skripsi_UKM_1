<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Membership;

class CheckMembership
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $ukm = $request->route('ukm');
        $ukmId = $ukm instanceof \App\Models\UKM ? $ukm->id : $ukm;

        $membership = Membership::where('user_id', $user->id)
            ->where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->first();

        if (!$membership) {
            abort(403, 'Anda belum menjadi anggota UKM ini');
        }

        return $next($request);
    }
}