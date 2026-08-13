<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required',
        ]);

        $loginInput = $request->input('login');
        $password   = $request->input('password');
        $remember   = $request->has('remember');

        // Tentukan apakah input adalah email atau NPM
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            // Login via email (cara lama)
            $user = \App\Models\User::where('email', $loginInput)->first();
        } else {
            // Login via NPM — cari member berdasarkan npm, lalu ambil user-nya
            $member = Member::withoutGlobalScopes()
                ->where('npm', $loginInput)
                ->whereNotNull('user_id')
                ->first();
            $user = $member ? $member->user : null;
        }

        // Verifikasi user dan password
        if ($user && Hash::check($password, $user->password)) {
            if ($user->status !== 'active') {
                return back()->withErrors([
                    'login' => $user->status === 'pending'
                        ? 'Akun Anda masih dalam status pendaftaran. Silakan tunggu verifikasi dari Admin.'
                        : 'Akun Anda ditolak atau dinonaktifkan.',
                ])->withInput(['login' => $loginInput]);
            }

            Auth::login($user, $remember);
            $request->session()->regenerate();
            SystemLogger::logLogin($user->id, $user->name, 'Success');
            SystemLogger::logActivity('Melakukan login ke sistem', 'Autentikasi');
            return $this->redirectUser($user);
        }

        SystemLogger::logLogin(null, $loginInput, 'Failed');

        return back()->withErrors([
            'login' => 'Email/NPM atau password salah.',
        ])->withInput(['login' => $loginInput]);
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