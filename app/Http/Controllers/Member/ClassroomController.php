<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Material;
use App\Models\UKM;
use App\Models\ActivitySession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClassroomController extends Controller
{
    // 🔥 Pilih Agenda (Daftar Kelas)
    public function index(UKM $ukm)
    {
        $user = Auth::user();
        $membership = $user->memberships()->where('ukm_id', $ukm->id)->where('status', 'approved')->first();

        if (!$membership) abort(403);

        $events = $ukm->events()->whereHas('participants', function($q) use ($membership) {
            $q->where('membership_id', $membership->id);
        })->get();

        return view('member.classroom.index', compact('ukm', 'events'));
    }

    // 🔥 Halaman Classroom (Halaman Utama Agenda)
    public function classroom(UKM $ukm, Event $event, Request $request)
    {
        // Validasi akses
        if (!Auth::user()->isParticipant($event->id)) {
            abort(403, 'Anda bukan peserta agenda ini.');
        }

        $tab = $request->query('tab', 'stream'); // Default ke stream

        // Load stream data
        $announcements = Announcement::with('creator')
            ->where('event_id', $event->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $latestMaterials = Material::where('event_id', $event->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Load materials data
        $materials = Material::where('event_id', $event->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Load members data
        $participants = $event->participants()->with('user', 'classification')->get();
        $coaches = $event->coaches;

        // Load attendances data
        $sessions = ActivitySession::where('event_id', $event->id)
            ->with(['attendances' => function($q) {
                $q->where('user_id', Auth::id());
            }])
            ->orderBy('date', 'desc')
            ->get();
        
        $totalSessions = $sessions->count();
        $hadir = 0;
        $izin = 0;
        $tidakHadir = 0;
        
        foreach ($sessions as $session) {
            $att = $session->attendances->first();
            if ($att) {
                if ($att->status === 'hadir') {
                    $hadir++;
                } elseif ($att->status === 'izin') {
                    $izin++;
                } elseif ($att->status === 'tidak hadir' || $att->status === 'alpa') {
                    $tidakHadir++;
                }
            }
        }
        
        $persentase = $totalSessions > 0 ? ($hadir / $totalSessions) * 100 : 0;

        $data = [
            'ukm' => $ukm,
            'event' => $event,
            'activeTab' => $tab,
            'announcements' => $announcements,
            'latestMaterials' => $latestMaterials,
            'materials' => $materials,
            'participants' => $participants,
            'coaches' => $coaches,
            'sessions' => $sessions,
            'totalSessions' => $totalSessions,
            'hadirCount' => $hadir,
            'izinCount' => $izin,
            'tidakHadirCount' => $tidakHadir,
            'persentase' => round($persentase, 2),
        ];

        return view('member.classroom.classroom', $data);
    }

    public function downloadMaterial($ukm, $id)
    {
        $material = Material::findOrFail($id);
        if (!Auth::user()->isParticipant($material->event_id)) abort(403);

        if ($material->file_path) {
            return Storage::disk('public')->download($material->file_path, $material->title);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }
}
