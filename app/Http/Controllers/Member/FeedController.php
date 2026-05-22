<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\UKM;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Only fetch UKMs that the user has successfully joined (status: approved)
        $joinedUkmIds = $user->memberships()->where('status', 'approved')->pluck('ukm_id');

        $selectedUkmId = $request->query('ukm_id');
        $filterType = $request->query('type'); // 'all', 'announcement', 'event', 'gallery'

        // 1. Build queries restricting to followed UKMs
        $announcementQuery = Announcement::with(['ukm', 'creator'])->whereIn('ukm_id', $joinedUkmIds);
        $eventQuery = Event::with('ukm')->whereIn('ukm_id', $joinedUkmIds);
        $galleryQuery = Gallery::with(['ukm', 'creator'])->whereIn('ukm_id', $joinedUkmIds);

        // UKM filter
        if ($selectedUkmId) {
            $announcementQuery->where('ukm_id', $selectedUkmId);
            $eventQuery->where('ukm_id', $selectedUkmId);
            $galleryQuery->where('ukm_id', $selectedUkmId);
        }

        // 2. Fetch and tag
        $feedItems = collect();

        if (!$filterType || $filterType === 'all' || $filterType === 'announcement') {
            $announcements = $announcementQuery->latest()->get()->map(function ($item) {
                $item->feed_type = 'announcement';
                return $item;
            });
            $feedItems = $feedItems->concat($announcements);
        }

        if (!$filterType || $filterType === 'all' || $filterType === 'event') {
            $events = $eventQuery->latest()->get()->map(function ($item) {
                $item->feed_type = 'event';
                return $item;
            });
            $feedItems = $feedItems->concat($events);
        }

        if (!$filterType || $filterType === 'all' || $filterType === 'gallery') {
            $galleries = $galleryQuery->latest()->get()->map(function ($item) {
                $item->feed_type = 'gallery';
                return $item;
            });
            $feedItems = $feedItems->concat($galleries);
        }

        // 3. Sort by created_at descending
        $feedItems = $feedItems->sortByDesc('created_at')->values();

        // UKMs for filter dropdown
        $myUkms = UKM::whereIn('id', $joinedUkmIds)->get();

        return view('member.feed', compact(
            'feedItems',
            'myUkms',
            'selectedUkmId',
            'filterType'
        ));
    }
}
