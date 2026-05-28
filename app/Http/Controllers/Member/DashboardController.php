<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Announcement;
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

        // Ambil event yang diikuti oleh user (sebagai partisipan)
        $myMembershipIds = $approvedMemberships->pluck('id');
        $participatingEvents = Event::with('ukm')
            ->whereHas('participants', function ($query) use ($myMembershipIds) {
                $query->whereIn('membership_id', $myMembershipIds);
            })
            ->where('is_archived', false)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get();

        // Ambil pengumuman dari UKM yang diikuti
        $announcements = Announcement::with(['ukm', 'creator'])
            ->whereIn('ukm_id', $joinedUkmIds)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->notification_type = 'announcement';
                return $item;
            });

        // Ambil event terbaru dari UKM yang diikuti
        $events = Event::with('ukm')
            ->whereIn('ukm_id', $joinedUkmIds)
            ->where('is_archived', false)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->notification_type = 'event';
                return $item;
            });

        // Gabungkan dan urutkan berdasarkan tanggal dibuat paling baru
        $notifications = $announcements->concat($events)->sortByDesc('created_at')->take(5);

        // Total Kehadiran Presensi Kegiatan
        $totalPresence = Attendance::where('user_id', $user->id)->where('status', 'present')->count();

        return view('member.dashboard', compact(
            'memberships',
            'approvedMemberships',
            'pendingMemberships',
            'ukmJoinedCount',
            'ukmPendingCount',
            'upcomingEvents',
            'participatingEvents',
            'notifications',
            'totalPresence'
        ));
    }
}