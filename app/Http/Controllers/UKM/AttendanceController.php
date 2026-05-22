<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $ukmId = session('managed_ukm_id');
        $events = Event::where('ukm_id', $ukmId)->latest()->get();

        return view('ukm.attendances.index', compact('events'));
    }

    public function show($eventId)
    {
        $ukmId = session('managed_ukm_id');
        $event = Event::where('id', $eventId)->where('ukm_id', $ukmId)->firstOrFail();
        $sessions = $event->sessions()->orderBy('date', 'desc')->get();
        
        return view('ukm.attendances.show', compact('event', 'sessions'));
    }

    public function store(Request $request, $eventId)
    {
        $ukmId = session('managed_ukm_id');
        $event = Event::where('id', $eventId)->where('ukm_id', $ukmId)->firstOrFail();

        $attendances = $request->input('attendances', []);
        
        foreach ($attendances as $userId => $status) {
            Attendance::updateOrCreate(
                ['event_id' => $eventId, 'user_id' => $userId],
                ['status' => $status]
            );
        }

        return back()->with('success', 'Data absensi berhasil disimpan');
    }
}
