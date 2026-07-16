@extends('layouts.auth')

@section('title', 'Masuk dan Verifikasi')
@section('subtitle', 'Nikmati kemudahan akses portal tunggal untuk mengelola seluruh kegiatan, presensi, keuangan, dan LPJ UKM Anda.')

@section('content')

    <form method="POST" action="/login">
        @csrf

        <div class="form-group">
            <label class="form-label">Email/akun pengguna <span style="color: #ef4444">*</span></label>
            <input type="email" name="email" placeholder="Masukkan email atau NPM/NIP Anda" class="form-control" required value="{{ old('email') }}">
            @error('email')
                <div class="error-message">
                    <i class="ph ph-warning-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password <span style="color: #ef4444">*</span></label>
            <div style="position: relative; display: flex; align-items: center;">
                <input type="password" name="password" placeholder="Masukkan password" class="form-control" required style="padding-right: 2.75rem;">
                <button type="button" class="toggle-password-btn" style="position: absolute; right: 0.75rem; background: none; border: none; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; padding: 0.25rem; z-index: 10;">
                    <i class="ph ph-eye" style="font-size: 1.20rem;"></i>
                </button>
            </div>
        </div>

        <div style="display: flex; align-items: center; margin: -0.25rem 0 1.25rem 0; font-size: 0.8rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none; color: var(--text-muted); font-weight: 600;">
                <input type="checkbox" name="remember" value="1" style="cursor: pointer; accent-color: var(--primary); width: 15px; height: 15px;">
                Ingat Saya
            </label>
        </div>

        <button type="submit" class="btn-submit">
            Masuk
        </button>
    </form>

    <div class="auth-footer">
        Belum terdaftar menjadi anggota? <a href="/daftar">Daftar di sini</a>
    </div>

@endsection