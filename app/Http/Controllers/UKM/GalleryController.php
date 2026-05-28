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
        $ukmId = session('managed_ukm_id');
        $search = $request->input('search');
        $type = $request->input('type');

        $query = Gallery::where('ukm_id', $ukmId)->latest();

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
        ]);

        $path = $request->file('file')->store('galleries', 'public');

        Gallery::create([
            'ukm_id' => session('managed_ukm_id'),
            'created_by' => auth()->id(),
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Media berhasil ditambahkan ke galeri');
    }

    public function destroy($id)
    {

        $ukmId = session('managed_ukm_id');
        $media = Gallery::where('id', $id)->where('ukm_id', $ukmId)->firstOrFail();
        
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return back()->with('success', 'Media berhasil dihapus');
    }
}
