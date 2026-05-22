<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ActivitySession;
use App\Models\Attendance;
use App\Models\CoachAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivitySessionController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        ActivitySession::create([
            'event_id' => $eventId,
            'title' => $request->title,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Pertemuan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $session = ActivitySession::findOrFail($id);
        $session->delete();

        return back()->with('success', 'Pertemuan berhasil dihapus.');
    }

    public function showAttendance($id, Request $request)
    {
        $session = ActivitySession::with('event.ukm')->findOrFail($id);
        $event = $session->event;
        $ukm = $event->ukm;
        
        $user = Auth::user();
        $isUKMAdmin = $user->isSuperAdmin() || $user->memberships()->where('ukm_id', $event->ukm->id)->where('role_in_ukm', 'admin')->where('status', 'approved')->exists(); 

        // Authorization check
        if (!$isUKMAdmin && !$user->isParticipant($event->id)) {
            abort(403, 'Anda tidak memiliki akses ke absensi ini.');
        }

        $members = $event->participants()->with('user', 'classification')->get();
        $memberAttendances = Attendance::where('session_id', $id)->get()->keyBy('user_id');

        // If member, only show their own attendance and restricted UI
        $isMember = !$isUKMAdmin;
        
        return view('ukm.classroom.session_attendance', compact(
            'session', 'event', 'members', 
            'memberAttendances', 'isUKMAdmin', 'isMember'
        ));
    }

    public function toggleAttendance($id)
    {
        $session = ActivitySession::findOrFail($id);
        $user = Auth::user();
        $isUKMAdmin = $user->isSuperAdmin() || $user->memberships()->where('ukm_id', $session->event->ukm_id)->where('role_in_ukm', 'admin')->where('status', 'approved')->exists();

        if (!$isUKMAdmin) abort(403);

        $session->update(['is_open' => !$session->is_open]);

        $status = $session->is_open ? 'dibuka' : 'ditutup';
        return back()->with('success', "Absensi berhasil $status.");
    }

    public function storeAttendance(Request $request, $id)
    {
        $session = ActivitySession::findOrFail($id);
        $user = Auth::user();
        $isUKMAdmin = $user->isSuperAdmin() || $user->memberships()->where('ukm_id', $session->event->ukm_id)->where('role_in_ukm', 'admin')->where('status', 'approved')->exists();

        // Authorization check
        if (!$isUKMAdmin && !$user->isParticipant($session->event_id)) {
            abort(403, 'Anda tidak memiliki akses untuk mengisi absensi ini.');
        }

        // VALIDASI: Jika absensi ditutup, member tidak bisa isi
        if (!$isUKMAdmin && !$session->is_open) {
            return back()->with('error', 'Absensi telah ditutup oleh admin.');
        }

        // Simpan Absensi Anggota
        if ($request->has('attendances')) {
            foreach ($request->attendances as $userId => $status) {
                // SECURITY: If not admin, can only fill their own attendance
                if (!$isUKMAdmin && $userId != $user->id) {
                    continue;
                }

                // LOCK LOGIC: If not admin and already filled, cannot change
                if (!$isUKMAdmin) {
                    $existing = Attendance::where('session_id', $id)->where('user_id', $userId)->first();
                    if ($existing) {
                        continue; // Locked
                    }
                }

                Attendance::updateOrCreate(
                    ['session_id' => $id, 'user_id' => $userId],
                    ['event_id' => $session->event_id, 'status' => $status]
                );
            }
        }

        return back()->with('success', 'Absensi berhasil disimpan.');
    }
}
