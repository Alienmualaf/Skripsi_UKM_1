<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Coach;
use App\Models\Membership;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $ukmId = session('managed_ukm_id');
        $events = Event::where('ukm_id', $ukmId)->latest()->get();

        return view('ukm.events.index', compact('events'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event = Event::create([
            'ukm_id' => session('managed_ukm_id'),
            'created_by' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'upcoming',
        ]);

        // Secara default, semua anggota otomatis terpilih
        $memberships = Membership::where('ukm_id', $event->ukm_id)
            ->where('status', 'approved')
            ->where('role_in_ukm', '!=', 'admin')
            ->pluck('id');
        
        $event->participants()->attach($memberships);

        return back()->with('success', 'Agenda berhasil ditambahkan dan seluruh anggota otomatis menjadi peserta.');
    }

    public function destroy($id)
    {

        $ukmId = session('managed_ukm_id');
        $event = Event::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        $event->delete();

        return back()->with('success', 'Agenda berhasil dihapus');
    }

    public function showParticipants($id)
    {
        $ukmId = session('managed_ukm_id');
        $event = Event::with('participants.user', 'coaches')->where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        
        $allMembers = Membership::with('user')->where('ukm_id', $ukmId)->where('status', 'approved')->where('role_in_ukm', '!=', 'admin')->get();
        $allCoaches = Coach::where('ukm_id', $ukmId)->get();

        return view('ukm.events.participants', compact('event', 'allMembers', 'allCoaches'));
    }

    public function updateParticipants(Request $request, $id)
    {

        $ukmId = session('managed_ukm_id');
        $event = Event::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        
        $event->participants()->sync($request->memberships ?? []);
        $event->coaches()->sync($request->coach_ids ?? []);

        return back()->with('success', 'Daftar peserta dan pelatih terlibat berhasil diperbarui.');
    }

    public function updateCoaches(Request $request, $id)
    {

        $ukmId = session('managed_ukm_id');
        $event = Event::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        
        $event->coaches()->sync($request->coach_ids ?? []);

        return back()->with('success', 'Daftar pelatih terlibat berhasil diperbarui.');
    }

}