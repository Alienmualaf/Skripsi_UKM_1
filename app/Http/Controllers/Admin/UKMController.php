<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UKM;
use Illuminate\Http\Request;

class UKMController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = UKM::latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $ukms = $query->paginate(15)->withQueryString();

        return view('admin.ukm.index', compact('ukms', 'search'));
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
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|string|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $ukm = UKM::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'logo' => $data['logo'] ?? null,
        ]);

        // Create Admin User for this UKM
        $user = \App\Models\User::create([
            'name' => $data['admin_name'],
            'email' => $data['admin_email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['admin_password']),
            'role' => 'user',
        ]);

        // Link User as Admin of this UKM
        \App\Models\Membership::create([
            'user_id' => $user->id,
            'ukm_id' => $ukm->id,
            'role_in_ukm' => 'admin',
            'status' => 'approved'
        ]);

        return redirect('/admin/ukm')->with('success', 'UKM dan Akun Admin berhasil dibuat');
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