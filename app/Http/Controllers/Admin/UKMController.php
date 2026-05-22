<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UKM;
use Illuminate\Http\Request;

class UKMController extends Controller
{
    public function index()
    {
        $ukms = UKM::latest()->get();

        return view('admin.ukm.index', compact('ukms'));
    }

    public function create()
    {
        return view('admin.ukm.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        UKM::create($data);

        return redirect('/admin/ukm')->with('success', 'UKM berhasil dibuat');
    }

    public function edit($id)
    {
        $ukm = UKM::findOrFail($id);

        return view('admin.ukm.edit', compact('ukm'));
    }

    public function update(Request $request, $id)
    {
        $ukm = UKM::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if it exists and is not a URL
            if ($ukm->logo && !filter_var($ukm->logo, FILTER_VALIDATE_URL)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($ukm->logo);
            }
            
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $ukm->update($data);

        return redirect('/admin/ukm')->with('success', 'UKM berhasil diupdate');
    }

    public function destroy($id)
    {
        $ukm = UKM::findOrFail($id);
        $ukm->delete();

        return back()->with('success', 'UKM berhasil dihapus');
    }
}