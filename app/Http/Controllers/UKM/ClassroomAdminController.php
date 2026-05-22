<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Material;
use App\Models\ActivitySession;
use App\Models\UKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomAdminController extends Controller
{
    public function index()
    {
        $ukmId = session('managed_ukm_id');
        $ukm = UKM::findOrFail($ukmId);
        $events = Event::where('ukm_id', $ukmId)->latest()->get();

        return view('ukm.classroom.index', compact('ukm', 'events'));
    }

    public function classroom($eventId, Request $request)
    {
        $ukmId = session('managed_ukm_id');
        $event = Event::where('id', $eventId)->where('ukm_id', $ukmId)->firstOrFail();
        $ukm = $event->ukm;

        $tab = $request->query('tab', 'stream'); // Default to stream

        // 1. Stream: Announcements
        $announcements = Announcement::with('creator')
            ->where('event_id', $event->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Classwork: Materials
        $materials = Material::where('event_id', $event->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Attendance: Activity Sessions
        $sessions = ActivitySession::where('event_id', $event->id)
            ->with('attendances')
            ->orderBy('date', 'desc')
            ->get();

        // 4. People: Members & Coaches
        $participants = $event->participants()->with('user', 'classification')->get();
        $coaches = $event->coaches;

        return view('ukm.classroom.classroom', compact(
            'ukm', 'event', 'tab',
            'announcements', 'materials', 'sessions', 'participants', 'coaches'
        ));
    }
}
