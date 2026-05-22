<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $adminMembership = Membership::where('user_id', $user->id)
            ->where('role_in_ukm', 'admin')
            ->where('status', 'approved')
            ->first();

        if ($adminMembership) {
            return redirect('/ukm/dashboard');
        }

        // Ambil seluruh data keanggotaan
        $memberships = $user->memberships()->with('ukm')->get();
        $approvedMemberships = $memberships->where('status', 'approved');
        $pendingMemberships = $memberships->where('status', 'pending');

        $joinedUkmIds = $approvedMemberships->pluck('ukm_id');

        // Metrik Ringkasan
        $ukmJoinedCount = $approvedMemberships->count();
        $ukmPendingCount = $pendingMemberships->count();
        
        // Agenda & Kegiatan Terdekat di UKM yang telah diikuti
        $upcomingEvents = Event::with('ukm')
            ->whereIn('ukm_id', $joinedUkmIds)
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        // Total Kehadiran Presensi Kegiatan
        $totalPresence = Attendance::where('user_id', $user->id)->where('status', 'present')->count();

        return view('member.dashboard', compact(
            'memberships',
            'approvedMemberships',
            'pendingMemberships',
            'ukmJoinedCount',
            'ukmPendingCount',
            'upcomingEvents',
            'totalPresence'
        ));
    }
}