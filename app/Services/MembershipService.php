<?php

namespace App\Services;

use App\Models\Membership;

class MembershipService
{
    // 🔥 join UKM
    public function join($userId, $ukmId)
    {
        $exists = Membership::where('user_id', $userId)
            ->where('ukm_id', $ukmId)
            ->exists();

        if ($exists) {
            return [
                'status' => false,
                'message' => 'Sudah terdaftar di UKM ini'
            ];
        }

        Membership::create([
            'user_id' => $userId,
            'ukm_id' => $ukmId,
            'role_in_ukm' => 'member',
            'status' => 'pending',
        ]);

        return [
            'status' => true,
            'message' => 'Berhasil mendaftar, menunggu persetujuan'
        ];
    }

    // 🔥 approve anggota
    public function approve($membershipId)
    {
        $membership = Membership::findOrFail($membershipId);

        $membership->update([
            'status' => 'approved'
        ]);

        return true;
    }

    // 🔥 cek apakah user anggota
    public function isMember($userId, $ukmId)
    {
        return Membership::where('user_id', $userId)
            ->where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->exists();
    }
}