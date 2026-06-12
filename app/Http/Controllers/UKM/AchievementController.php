<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Achievement::orderBy('date', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $achievements = $query->paginate(10)->withQueryString();

        return view('ukm.achievements.index', compact('achievements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'show_on_landing' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('achievements', 'public');
        }

        $data['show_on_landing'] = $request->has('show_on_landing');

        Achievement::create($data);

        return back()->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'show_on_landing' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($achievement->photo) {
                Storage::disk('public')->delete($achievement->photo);
            }
            $data['photo'] = $request->file('photo')->store('achievements', 'public');
        }

        $data['show_on_landing'] = $request->has('show_on_landing');

        $achievement->update($data);

        return back()->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function toggleLanding($id)
    {
        $achievement = Achievement::findOrFail($id);
        
        $achievement->update([
            'show_on_landing' => !$achievement->show_on_landing
        ]);

        return back()->with('success', 'Visibilitas prestasi berhasil diubah.');
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);

        if ($achievement->photo) {
            Storage::disk('public')->delete($achievement->photo);
        }

        $achievement->delete();

        return back()->with('success', 'Prestasi berhasil dihapus.');
    }
}
