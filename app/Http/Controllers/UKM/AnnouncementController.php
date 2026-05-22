<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $ukmId = session('managed_ukm_id');
        $events = Event::where('ukm_id', $ukmId)->orderBy('start_date', 'desc')->get();
        
        $eventId = $request->query('event_id');
        $query = Announcement::with('event', 'creator')->where('ukm_id', $ukmId);
        
        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        $announcements = $query->orderBy('created_at', 'desc')->get();

        return view('ukm.announcements.index', compact('announcements', 'events'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $ukmId = session('managed_ukm_id');
        
        Announcement::create([
            'ukm_id' => $ukmId,
            'event_id' => $request->event_id,
            'created_by' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement = Announcement::where('id', $id)->where('ukm_id', session('managed_ukm_id'))->firstOrFail();
        $announcement->update($request->only('title', 'content'));

        return back()->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {

        $announcement = Announcement::where('id', $id)->where('ukm_id', session('managed_ukm_id'))->firstOrFail();
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}
