@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header', 'Pengaturan Profil')

@section('content')
<div class="card" style="max-width: 600px;">
    <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <div style="width: 80px; height: 80px; background: var(--accent-light); color: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800;">
            {{ substr($user->name, 0, 1) }}
        </div>
        <div>
            <h3 style="margin: 0;">{{ $user->name }}</h3>
            <p style="margin: 0; color: var(--text-secondary);">{{ $user->email }}</p>
            <span class="badge badge-member" style="margin-top: 0.5rem; display: inline-block;">{{ ucfirst($user->role) }}</span>
        </div>
    </div>

    <form action="/member/profile" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        @if($user->role == 'member')
        <div class="form-group">
            <label class="form-label">Fakultas</label>
            <select name="faculty" class="form-control" required>
                <option value="">Pilih Fakultas</option>
                @foreach(['Ekonomi & Bisnis', 'Teknik', 'Psikologi', 'Hukum', 'Ilmu Komunikasi', 'Farmasi', 'Pariwisata'] as $fac)
                    <option value="{{ $fac }}" {{ old('faculty', $user->faculty) == $fac ? 'selected' : '' }}>{{ $fac }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
            <h4 class="mb-4">Ganti Password</h4>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 1rem;">Biarkan kosong jika tidak ingin mengubah password.</p>
            
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary w-full">
                <i class="ph ph-floppy-disk"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
