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
            <input type="password" name="password" placeholder="Masukkan password" class="form-control" required>
        </div>

        <button type="submit" class="btn-submit">
            Masuk
        </button>
    </form>

    <div class="auth-footer">
        Belum punya akun? <a href="/register">Daftar di sini</a>
    </div>

@endsection