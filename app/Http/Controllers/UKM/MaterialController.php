<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $currentFolderId = $request->query('folder_id');
        $search = $request->query('search');
        $type = $request->query('type'); // Partitur, Audio, Video
        $classroomId = $request->query('classroom_id');

        $classroom = $classroomId ? \App\Models\Classroom::with('materials')->find($classroomId) : null;

        // Build breadcrumbs if inside a folder
        $breadcrumbs = [];
        $currentFolder = null;
        if ($currentFolderId) {
            $currentFolder = Folder::findOrFail($currentFolderId);
            $breadcrumbs[] = $currentFolder;
            $parent = $currentFolder->parent;
            while ($parent) {
                array_unshift($breadcrumbs, $parent);
                $parent = $parent->parent;
            }
        }

        // Folders list
        $foldersQuery = Folder::query();
        if ($currentFolderId) {
            $foldersQuery->where('parent_id', $currentFolderId);
        } else {
            $foldersQuery->whereNull('parent_id');
        }
        if ($search) {
            $foldersQuery->where('name', 'like', "%{$search}%");
        }
        $folders = $foldersQuery->orderBy('name', 'asc')->get();

        // Materials list
        $materialsQuery = Material::with('uploader');
        if ($currentFolderId) {
            $materialsQuery->where('folder_id', $currentFolderId);
        } else {
            $materialsQuery->whereNull('folder_id');
        }
        if ($search) {
            $materialsQuery->where('title', 'like', "%{$search}%");
        }
        if ($type) {
            $materialsQuery->where('type', $type);
        }
        $materials = $materialsQuery->orderBy('title', 'asc')->get();

        return view('pengurus.materials.index', compact('folders', 'materials', 'breadcrumbs', 'currentFolder', 'currentFolderId', 'search', 'type', 'classroom', 'classroomId'));
    }

    public function storeFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Folder berhasil dibuat.');
    }

    public function deleteFolder($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete(); // Cascades delete to children and materials

        return back()->with('success', 'Folder dan seluruh isinya berhasil dihapus.');
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:Partitur,Audio,Video',
            'folder_id' => 'required|exists:folders,id',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,docx,mp3,wav,mp4,mov|max:51200', // Max 50MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('materials', 'public');
            $extension = strtolower($file->getClientOriginalExtension());

            Material::create([
                'title' => $request->title,
                'type' => $request->type,
                'folder_id' => $request->folder_id,
                'description' => $request->description,
                'file_path' => $path,
                'file_type' => $extension,
                'uploader_id' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Materi berhasil diunggah.');
    }

    public function deleteMaterial($id)
    {
        $material = Material::findOrFail($id);
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();

        return back()->with('success', 'Materi berhasil dihapus.');
    }

    public function download($id)
    {
        $material = Material::findOrFail($id);
        if (!Storage::disk('public')->exists($material->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($material->file_path, $material->title . '.' . $material->file_type);
    }
}
