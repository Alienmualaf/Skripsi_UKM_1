<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // 🔥 tampilkan halaman profil
    public function index()
    {
        $user = auth()->user();

        return view('shared.profile', compact('user'));
    }

    // 🔥 update data profil
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    // 🔥 update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        // cek password lama
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Password lama salah');
        }

        // update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
}