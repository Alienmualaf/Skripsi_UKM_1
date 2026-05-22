<?php

namespace App\Repositories;

use App\Models\Membership;

class MembershipRepository
{
    public function findByUserAndUKM($userId, $ukmId)
    {
        return Membership::where('user_id', $userId)
            ->where('ukm_id', $ukmId)
            ->first();
    }

    public function create(array $data)
    {
        return Membership::create($data);
    }

    public function approve($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->update(['status' => 'approved']);

        return $membership;
    }

    public function getByUKM($ukmId)
    {
        return Membership::with('user')
            ->where('ukm_id', $ukmId)
            ->get();
    }

    public function countApproved($ukmId)
    {
        return Membership::where('ukm_id', $ukmId)
            ->where('status', 'approved')
            ->count();
    }
}