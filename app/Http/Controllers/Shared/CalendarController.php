<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Event::with('ukm');

        if ($user->isSuperAdmin()) {
            // Global view for Super Admin
            $events = $query->get();
        } elseif (session()->has('managed_ukm_id')) {
            // UKM Admin view
            $events = $query->where('ukm_id', session('managed_ukm_id'))->get();
        } else {
            // Member view: Events from UKMs they are members of
            $ukmIds = $user->memberships()->where('status', 'approved')->pluck('ukm_id');
            $events = $query->whereIn('ukm_id', $ukmIds)->get();
        }

        // Transform events for FullCalendar: Split multi-day events into individual entries
        $formattedEvents = [];
        foreach ($events as $event) {
            $start = \Carbon\Carbon::parse($event->start_date);
            $end = $event->end_date ? \Carbon\Carbon::parse($event->end_date) : $start->copy();
            
            // Loop through each day from start to end
            $currentDate = $start->copy()->startOfDay();
            $endDate = $end->copy()->startOfDay();

            while ($currentDate->lte($endDate)) {
                $formattedEvents[] = [
                    'id' => $event->id,
                    'title' => '[' . $event->ukm->name . '] ' . $event->title,
                    'start' => $currentDate->toDateString(),
                    'allDay' => true,
                    'extendedProps' => [
                        'description' => $event->description,
                        'location' => $event->location,
                        'ukm_name' => $event->ukm->name,
                        'original_start' => $event->start_date->format('H:i'),
                        'original_end' => $event->end_date ? $event->end_date->format('H:i') : null,
                    ],
                    'color' => $this->getEventColor($event->ukm->id),
                ];
                $currentDate->addDay();
            }
        }

        return view('shared.calendar.index', compact('formattedEvents'));
    }

    private function getEventColor($id)
    {
        $colors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'];
        return $colors[$id % count($colors)];
    }
}
