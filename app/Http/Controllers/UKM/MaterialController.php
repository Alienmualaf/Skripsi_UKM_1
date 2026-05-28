<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $ukmId = session('managed_ukm_id');
        $search = $request->input('search');
        $type = $request->input('type');
        $eventId = $request->input('event_id');

        $query = Material::with('event')->where('ukm_id', $ukmId)->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        $materials = $query->paginate(15)->withQueryString();
        $events = Event::where('ukm_id', $ukmId)->latest()->get();

        return view('ukm.materials.index', compact('materials', 'events'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:dokumen,audio,video',
            'file' => 'nullable|file|max:10240', // max 10MB
            'link' => 'nullable|url',
            'description' => 'nullable|string',
            'event_id' => 'required|exists:events,id',
        ]);

        if (!$request->hasFile('file') && !$request->link) {
            return back()->with('error', 'Wajib mengunggah file atau mencantumkan link materi.')->withInput();
        }

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('materials', 'public');
        }

        Material::create([
            'ukm_id' => session('managed_ukm_id'),
            'created_by' => auth()->id(),
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
            'link' => $request->link,
            'description' => $request->description,
            'event_id' => $request->event_id,
        ]);

        return back()->with('success', 'Materi berhasil diunggah.');
    }

    public function destroy($id)
    {

        $ukmId = session('managed_ukm_id');
        $material = Material::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();

        return back()->with('success', 'Materi berhasil dihapus.');
    }
}
