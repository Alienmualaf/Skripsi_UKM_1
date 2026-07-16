<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SystemLogger;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => $user->status === 'pending'
                        ? 'Akun Anda masih dalam status pendaftaran. Silakan tunggu verifikasi dari Admin.'
                        : 'Akun Anda ditolak atau dinonaktifkan.',
                ]);
            }

            $request->session()->regenerate();
            SystemLogger::logLogin($user->id, $user->name, 'Success');
            SystemLogger::logActivity('Melakukan login ke sistem', 'Autentikasi');
            return $this->redirectUser($user);
        }

        SystemLogger::logLogin(null, $request->email, 'Failed');

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    protected function redirectUser($user)
    {
        if ($user->isSuperAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isAdminUkm()) {
            return redirect('/ukm/dashboard');
        } elseif ($user->isPengurus()) {
            return redirect('/pengurus/dashboard');
        } elseif ($user->isAnggota()) {
            return redirect('/member/dashboard');
        }
        return redirect('/');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            SystemLogger::logActivity('Melakukan logout dari sistem', 'Autentikasi');
            SystemLogger::logLogout($user->id, $user->name);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}