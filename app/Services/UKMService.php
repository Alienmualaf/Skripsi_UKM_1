<?php

namespace App\Services;

use App\Models\UKM;
use App\Models\Membership;

class UKMService
{
    // 🔥 ambil UKM aktif user
    public function getUserUKM($user)
    {
        $membership = $user->memberships()
            ->where('status', 'approved')
            ->first();

        return $membership ? $membership->ukm : null;
    }

    // 🔥 total anggota UKM
    public function countMembers($ukmId)
    {
        return Membership::where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->count();
    }

    // 🔥 ambil semua UKM + count
    public function getAllWithStats()
    {
        return UKM::withCount('memberships')->get();
    }
}