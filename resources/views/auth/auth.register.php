@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')
@section('subtitle', 'Lengkapi formulir di bawah ini untuk membuat akun Sistem UKM Universitas Pancasila Anda.')

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
            <label class="form-label">Email/akun pengguna <span style="color: #ef4444">*</span></label>
            <input type="email" name="email" placeholder="contoh@mahasiswa.univpancasila.ac.id" class="form-control" required value="{{ old('email') }}" autocomplete="off">
            @error('email')
                <div class="error-message">
                    <i class="ph ph-warning-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Fakultas <span style="color: #ef4444">*</span></label>
            <select name="faculty" class="form-control" required style="appearance: none; background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 fill=%22none%22 stroke=%22%23475569%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 class=%22feather feather-chevron-down%22%3E%3Cpath d=%22M6 9l4 4 4-4%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;">
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
                <div class="error-message">
                    <i class="ph ph-warning-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password <span style="color: #ef4444">*</span></label>
            <input type="password" name="password" placeholder="Buat password minimal 8 karakter" class="form-control" required autocomplete="new-password">
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