<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $query = Gallery::latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        $galleries = $query->paginate(15)->withQueryString();

        return view('ukm.galleries.index', compact('galleries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:photo,video',
            'file' => 'required|file|max:20480', // max 20MB
            'show_on_landing' => 'nullable|boolean',
        ]);

        $path = $request->file('file')->store('galleries', 'public');

        Gallery::create([
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
            'show_on_landing' => $request->has('show_on_landing'),
        ]);

        return back()->with('success', 'Media berhasil ditambahkan ke galeri');
    }

    public function toggleLanding($id)
    {
        $media = Gallery::findOrFail($id);
        
        $media->update([
            'show_on_landing' => !$media->show_on_landing
        ]);

        return back()->with('success', 'Visibilitas media galeri berhasil diubah.');
    }

    public function destroy($id)
    {
        $media = Gallery::findOrFail($id);
        
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return back()->with('success', 'Media berhasil dihapus');
    }
}
