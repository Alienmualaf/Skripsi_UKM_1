@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')
@section('subtitle', 'Lengkapi formulir di bawah ini untuk membuat akun PSUP Portal Anda.')

@section('content')

    <form method="POST" action="/register">
        @csrf

        <div class="form-group">
            <label class="form-label">Nama Lengkap <span style="color: #ef4444">*</span></label>
            <input type="text" name="name" placeholder="Masukkan nama lengkap Anda" class="form-control" required value="{{ old('name') }}">
            @error('name')
                <div class="error-message">
                    <i class="ph ph-warning-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email <span style="color: #ef4444">*</span></label>
            <input type="email" name="email" placeholder="contoh@mahasiswa.univpancasila.ac.id" class="form-control" required value="{{ old('email') }}" autocomplete="off">
            @error('email')
                <div class="error-message">
                    <i class="ph ph-warning-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password <span style="color: #ef4444">*</span></label>
            <div style="position: relative; display: flex; align-items: center;">
                <input type="password" name="password" placeholder="Buat password minimal 6 karakter" class="form-control" required autocomplete="new-password" style="padding-right: 2.75rem;">
                <button type="button" class="toggle-password-btn" style="position: absolute; right: 0.75rem; background: none; border: none; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; padding: 0.25rem; z-index: 10;">
                    <i class="ph ph-eye" style="font-size: 1.20rem;"></i>
                </button>
            </div>
            @error('password')
                <div class="error-message">
                    <i class="ph ph-warning-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password <span style="color: #ef4444">*</span></label>
            <div style="position: relative; display: flex; align-items: center;">
                <input type="password" name="password_confirmation" placeholder="Masukkan kembali password Anda" class="form-control" required autocomplete="new-password" style="padding-right: 2.75rem;">
                <button type="button" class="toggle-password-btn" style="position: absolute; right: 0.75rem; background: none; border: none; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; padding: 0.25rem; z-index: 10;">
                    <i class="ph ph-eye" style="font-size: 1.20rem;"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-submit">
            Daftar
        </button>
    </form>

    <div class="auth-footer">
        Sudah punya akun? <a href="/login">Masuk di sini</a>
    </div>

@endsection