<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = Announcement::with('creator')->latest();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $query->paginate(15)->withQueryString();

        return view('ukm.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        Announcement::create([
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

        $announcement = Announcement::findOrFail($id);
        $announcement->update($request->only('title', 'content'));

        return back()->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}

