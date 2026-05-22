@extends('layouts.auth')

@section('title', 'Login')
@section('subtitle', 'Silakan masuk ke akun Anda')

@section('content')

    <form method="POST" action="/login">
        @csrf

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" placeholder="contoh@mahasiswa.ac.id" class="form-control" required value="{{ old('email') }}">
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" placeholder="••••••••" class="form-control" required>
        </div>

        <button type="submit" class="btn-submit">
            Masuk Sekarang
        </button>
    </form>

    <div class="auth-footer">
        Belum punya akun? <a href="/register">Daftar di sini</a>
    </div>

@endsection