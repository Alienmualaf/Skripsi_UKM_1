<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\CoachAttendance;
use App\Models\Event;
use App\Models\ActivitySession;
use Illuminate\Http\Request;

class CoachAttendanceController extends Controller
{
    /**
     * Step 1: List Agendas (Events)
     */
    public function index(Request $request)
    {
        $ukmId = session('managed_ukm_id');
        $search = $request->input('search');

        $query = Event::where('ukm_id', $ukmId)->orderBy('start_date', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->paginate(15)->withQueryString();

        return view('ukm.attendances.coach_index', compact('events'));
    }

    /**
     * Step 2: List Sesi Pertemuan (ActivitySession) for the chosen Event
     */
    public function sessions($eventId)
    {
        $ukmId = session('managed_ukm_id');
        $event = Event::where('id', $eventId)->where('ukm_id', $ukmId)->firstOrFail();
        $sessions = $event->sessions()->orderBy('date', 'desc')->get();
        
        return view('ukm.attendances.coach_sessions', compact('event', 'sessions'));
    }

    /**
     * Step 3: Integrated Session Coach Attendance Sheet
     */
    public function sessionShow($sessionId)
    {
        $ukmId = session('managed_ukm_id');
        $session = ActivitySession::with('event')->findOrFail($sessionId);
        $event = $session->event;

        if ($event->ukm_id != $ukmId) {
            abort(403, 'Anda tidak memiliki akses ke agenda ini.');
        }

        // Fetch coaches and BPH belonging to this UKM
        $coaches = Coach::where('ukm_id', $ukmId)->where('category', 'pelatih')->get();
        $bphs = Coach::where('ukm_id', $ukmId)->where('category', 'bph')->get();

        // Fetch registered coach attendances for this session
        $attendances = CoachAttendance::with('coach')
            ->where('session_id', $sessionId)
            ->get();

        return view('ukm.attendances.coach_session_attendance', compact('session', 'event', 'coaches', 'bphs', 'attendances'));
    }

    /**
     * Step 4: Save Coach Attendance Record
     */
    public function sessionStore(Request $request, $sessionId)
    {
        $ukmId = session('managed_ukm_id');
        $session = ActivitySession::with('event')->findOrFail($sessionId);
        $event = $session->event;

        if ($event->ukm_id != $ukmId) {
            abort(403, 'Anda tidak memiliki akses ke agenda ini.');
        }

        // Custom Validation based on chosen category
        $rules = [
            'category' => 'required|in:pelatih,bph,lainnya',
            'status' => 'required|in:hadir,izin,tidak hadir',
        ];

        if ($request->category === 'pelatih' || $request->category === 'bph') {
            $rules['coach_id'] = 'required|exists:coaches,id';
        } else {
            $rules['name'] = 'required|string|max:255';
            $rules['notes'] = 'required|string';
        }

        $validated = $request->validate($rules);

        if ($request->category === 'pelatih' || $request->category === 'bph') {
            // Verify coach belongs to this UKM and category matches
            $coach = Coach::where('id', $request->coach_id)
                ->where('ukm_id', $ukmId)
                ->where('category', $request->category)
                ->firstOrFail();

            // Save or Update for Registered Coach/BPH
            CoachAttendance::updateOrCreate(
                ['session_id' => $sessionId, 'coach_id' => $coach->id],
                [
                    'event_id' => $event->id,
                    'category' => $request->category,
                    'status' => $request->status
                ]
            );
        } else {
            // Create a new record for Others (Lainnya)
            CoachAttendance::create([
                'session_id' => $sessionId,
                'event_id' => $event->id,
                'coach_id' => null,
                'category' => 'lainnya',
                'name' => $request->name,
                'notes' => $request->notes,
                'status' => $request->status
            ]);
        }

        return back()->with('success', 'Absensi pelatih/staf berhasil disimpan.');
    }

    /**
     * Step 5: Delete Attendance Record
     */
    public function destroy($id)
    {
        $ukmId = session('managed_ukm_id');
        $attendance = CoachAttendance::with('event')->findOrFail($id);

        if ($attendance->event->ukm_id != $ukmId) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $attendance->delete();

        return back()->with('success', 'Absensi pelatih/staf berhasil dihapus.');
    }
}
