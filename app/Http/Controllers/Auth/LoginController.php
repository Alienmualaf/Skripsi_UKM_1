<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'super_admin') {
                return redirect('/admin/dashboard');
            }

            if ($user->role === 'biro') {
                return redirect('/biro/dashboard');
            }

            $adminMembership = \App\Models\Membership::where('user_id', $user->id)
                ->where('role_in_ukm', 'admin')
                ->where('status', 'approved')
                ->first();

            if ($adminMembership) {
                return redirect('/ukm/dashboard');
            }

            return redirect('/member/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}