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
            <input type="password" name="password" placeholder="Buat password minimal 6 karakter" class="form-control" required autocomplete="new-password">
            @error('password')
                <div class="error-message">
                    <i class="ph ph-warning-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password <span style="color: #ef4444">*</span></label>
            <input type="password" name="password_confirmation" placeholder="Masukkan kembali password Anda" class="form-control" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn-submit">
            Daftar
        </button>
    </form>

    <div class="auth-footer">
        Sudah punya akun? <a href="/login">Masuk di sini</a>
    </div>

@endsection