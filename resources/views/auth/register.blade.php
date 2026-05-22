@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')
@section('subtitle', 'Buat akun untuk bergabung dengan UKM impianmu')

@section('content')

    <form method="POST" action="/register">
        @csrf

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" placeholder="Dimas Ganteng" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" placeholder="contoh@mahasiswa.ac.id" class="form-control" required value="{{ old('email') }}" autocomplete="off">
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Fakultas</label>
            <select name="faculty" class="form-control" required>
                <option value="">-- Pilih Fakultas --</option>
                <option value="Ekonomi & Bisnis" {{ old('faculty') == 'Ekonomi & Bisnis' ? 'selected' : '' }}>Ekonomi & Bisnis</option>
                <option value="Teknik" {{ old('faculty') == 'Teknik' ? 'selected' : '' }}>Teknik</option>
                <option value="Psikologi" {{ old('faculty') == 'Psikologi' ? 'selected' : '' }}>Psikologi</option>
                <option value="Hukum" {{ old('faculty') == 'Hukum' ? 'selected' : '' }}>Hukum</option>
                <option value="Ilmu Komunikasi" {{ old('faculty') == 'Ilmu Komunikasi' ? 'selected' : '' }}>Ilmu Komunikasi</option>
                <option value="Farmasi" {{ old('faculty') == 'Farmasi' ? 'selected' : '' }}>Farmasi</option>
                <option value="Pariwisata" {{ old('faculty') == 'Pariwisata' ? 'selected' : '' }}>Pariwisata</option>
            </select>
            @error('faculty')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" placeholder="••••••••" class="form-control" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="••••••••" class="form-control" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn-submit">
            Daftar Sekarang
        </button>
    </form>

    <div class="auth-footer">
        Sudah punya akun? <a href="/login">Masuk di sini</a>
    </div>

@endsection