<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        $ukmId = session('managed_ukm_id');
        $search = $request->input('search');
        $category = $request->input('category');

        $query = Coach::where('ukm_id', $ukmId)->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('skills', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $coaches = $query->paginate(15)->withQueryString();
        return view('ukm.coaches.index', compact('coaches'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:pelatih,bph,lainnya',
            'photo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'skills' => 'nullable|string',
        ]);

        $ukmId = session('managed_ukm_id');
        $data = $request->except('photo');
        $data['ukm_id'] = $ukmId;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('coaches', 'public');
        }

        Coach::create($data);

        return back()->with('success', 'Pelatih berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:pelatih,bph,lainnya',
            'photo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'skills' => 'nullable|string',
        ]);

        $coach = Coach::where('id', $id)->where('ukm_id', session('managed_ukm_id'))->firstOrFail();
        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            if ($coach->photo) Storage::disk('public')->delete($coach->photo);
            $data['photo'] = $request->file('photo')->store('coaches', 'public');
        }

        $coach->update($data);

        return back()->with('success', 'Data pelatih berhasil diperbarui.');
    }

    public function destroy($id)
    {

        $coach = Coach::where('id', $id)->where('ukm_id', session('managed_ukm_id'))->firstOrFail();
        if ($coach->photo) Storage::disk('public')->delete($coach->photo);
        $coach->delete();

        return back()->with('success', 'Pelatih berhasil dihapus.');
    }
}
