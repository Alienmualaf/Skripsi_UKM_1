<?php

namespace App\Http\Controllers\UKM;

use App\Http\Controllers\Controller;
use App\Models\UKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $ukmId = session('managed_ukm_id');
        $ukm = UKM::findOrFail($ukmId);

        return view('ukm.profile.index', compact('ukm'));
    }

    public function update(Request $request)
    {

        $ukmId = session('managed_ukm_id');
        $ukm = UKM::findOrFail($ukmId);

        $request->validate([
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'is_recruitment_open' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'structure_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072', // Max 3MB
        ]);

        $updateData = [
            'description' => $request->description,
            'phone' => $request->phone,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'is_recruitment_open' => $request->is_recruitment_open,
        ];

        if ($request->hasFile('logo')) {
            if ($ukm->logo && !filter_var($ukm->logo, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($ukm->logo);
            }
            $updateData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('structure_image')) {
            if ($ukm->structure_image) {
                Storage::disk('public')->delete($ukm->structure_image);
            }
            $updateData['structure_image'] = $request->file('structure_image')->store('structures', 'public');
        }

        $ukm->update($updateData);

        return back()->with('success', 'Profil UKM berhasil diperbarui');
    }

    public function addClassification(Request $request)
    {

        $request->validate(['name' => 'required|string|max:255']);
        
        \App\Models\UKMClassification::create([
            'ukm_id' => session('managed_ukm_id'),
            'name' => $request->name,
        ]);

        return back()->with('success', 'Klasifikasi anggota berhasil ditambahkan');
    }

    public function deleteClassification($id)
    {

        $classification = \App\Models\UKMClassification::where('id', $id)
            ->where('ukm_id', session('managed_ukm_id'))
            ->firstOrFail();
        
        $classification->delete();

        return back()->with('success', 'Klasifikasi anggota berhasil dihapus');
    }
}
