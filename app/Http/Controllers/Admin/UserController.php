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
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $ukms = UKM::all();
        return view('admin.users.create', compact('ukms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,user',
            'assign_admin_ukm_id' => 'nullable|exists:ukms,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Assign as Admin UKM if role is user and ukm is selected
        if ($request->role === 'user' && $request->assign_admin_ukm_id) {
            Membership::create([
                'user_id' => $user->id,
                'ukm_id' => $request->assign_admin_ukm_id,
                'role_in_ukm' => 'admin',
                'status' => 'approved'
            ]);
        }

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
            'role' => 'required|in:super_admin,user',
            'assign_admin_ukm_id' => 'nullable|exists:ukms,id'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        // Update Admin UKM status:
        // 1. Delete any existing "admin" role memberships for this user
        Membership::where('user_id', $user->id)
            ->where('role_in_ukm', 'admin')
            ->delete();

        // 2. If user is a user (or admin ukm) and a UKM is selected, assign as Admin
        if ($request->role === 'user' && $request->assign_admin_ukm_id) {
            $existing = Membership::where('user_id', $user->id)
                ->where('ukm_id', $request->assign_admin_ukm_id)
                ->first();
            if ($existing) {
                $existing->update([
                    'role_in_ukm' => 'admin',
                    'status' => 'approved'
                ]);
            } else {
                Membership::create([
                    'user_id' => $user->id,
                    'ukm_id' => $request->assign_admin_ukm_id,
                    'role_in_ukm' => 'admin',
                    'status' => 'approved'
                ]);
            }
        }

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