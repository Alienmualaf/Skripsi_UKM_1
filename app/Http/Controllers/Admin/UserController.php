<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UKM;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = User::latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/admin/users')->with('success', 'User berhasil dibuat');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $ukms = UKM::all();
        $currentAdminUkm = $user->memberships()
            ->where('role_in_ukm', 'admin')
            ->first();
        $currentAdminUkmId = $currentAdminUkm ? $currentAdminUkm->ukm_id : null;

        return view('admin.users.edit', compact('user', 'ukms', 'currentAdminUkmId'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect('/admin/users')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        if(auth()->id() == $id) {
            return back()->with('error', 'Tidak bisa menghapus diri sendiri');
        }
        
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}