@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header', 'Pengaturan Akun')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-bold" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--text-primary);">Profil Saya</h3>
        <p class="text-secondary text-sm">Kelola informasi pribadi dan keamanan akun Anda.</p>
    </div>
</div>

@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #10b981;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="card mb-4 animate-fade-in" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #ef4444;">
        <i class="ph-fill ph-warning" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('error') }}
    </div>
@endif

<div class="animate-fade-in" style="display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: start;">
    <!-- LEFT SIDEBAR COLUMN: User Meta Card -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card" style="margin-bottom: 0; padding: 1.5rem; text-align: center;">
            <div style="width: 80px; height: 80px; border-radius: 12px; background: var(--accent-light); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.25rem auto; font-weight: 800; font-family: 'Outfit', sans-serif;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            
            <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">{{ $user->name }}</h4>
            <p style="margin: 0.25rem 0 1rem 0; font-size: 0.8rem; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->email }}</p>
            
            @php
                $role = 'Anggota';
                if ($user->hasRole('super_admin')) $role = 'Super Admin';
                elseif ($user->hasRole('pembina')) $role = 'Pembina/Pelatih';
            @endphp
            <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: 700; padding: 0.3rem 0.65rem; border-radius: 6px; font-size: 0.75rem; border: 1px solid rgba(30, 64, 175, 0.1);">
                {{ $role }}
            </span>
        </div>

        <div class="card" style="margin-bottom: 0; padding: 0.75rem; display: flex; flex-direction: column; gap: 0.25rem;">
            <a href="#profile-info" style="display: flex; align-items: center; gap: 0.65rem; padding: 0.65rem 0.85rem; color: var(--accent-color); background: var(--accent-light); font-weight: 700; border-radius: 8px; font-size: 0.85rem; text-decoration: none;">
                <i class="ph ph-user" style="font-size: 1.15rem;"></i> Informasi Profil
            </a>
            <a href="#security-info" style="display: flex; align-items: center; gap: 0.65rem; padding: 0.65rem 0.85rem; color: var(--text-secondary); font-weight: 600; border-radius: 8px; font-size: 0.85rem; text-decoration: none;">
                <i class="ph ph-lock" style="font-size: 1.15rem;"></i> Keamanan Akun
            </a>
        </div>
    </div>

    <!-- RIGHT COLUMN: Settings Forms -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Card 1: Informasi Profil -->
        <div id="profile-info" class="card" style="margin-bottom: 0; padding: 1.75rem; border-top: 3px solid var(--accent-color);">
            <h4 style="font-weight: 800; margin: 0 0 1.5rem 0; font-size: 1.1rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">
                <i class="ph ph-user-gear" style="color: var(--accent-color); font-size: 1.35rem; vertical-align: middle; margin-right: 0.25rem;"></i>
                Informasi Personal
            </h4>
            
            <form method="POST" action="/profile">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.85rem; color: var(--text-primary); display: block; margin-bottom: 0.5rem;">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" style="border-radius: 8px; height: 42px;" required>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; font-size: 0.85rem; color: var(--text-primary); display: block; margin-bottom: 0.5rem;">Alamat Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" style="border-radius: 8px; height: 42px;" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" style="font-weight: 700; border-radius: var(--radius-md); padding: 0.6rem 1.25rem;">
                    <i class="ph ph-floppy-disk"></i> Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Card 2: Keamanan Akun -->
        <div id="security-info" class="card" style="margin-bottom: 0; padding: 1.75rem; border-top: 3px solid var(--warning-color);">
            <h4 style="font-weight: 800; margin: 0 0 1.5rem 0; font-size: 1.1rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">
                <i class="ph ph-shield-check" style="color: var(--warning-color); font-size: 1.35rem; vertical-align: middle; margin-right: 0.25rem;"></i>
                Ubah Password Keamanan
            </h4>
            
            <form method="POST" action="/profile/password">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div class="form-group" style="max-width: 500px;">
                        <label style="font-weight: 700; font-size: 0.85rem; color: var(--text-primary); display: block; margin-bottom: 0.5rem;">Password Saat Ini</label>
                        <input type="password" name="old_password" class="form-control" style="border-radius: 8px; height: 42px;" required placeholder="Masukkan password lama Anda">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.85rem; color: var(--text-primary); display: block; margin-bottom: 0.5rem;">Password Baru</label>
                            <input type="password" name="password" class="form-control" style="border-radius: 8px; height: 42px;" required placeholder="Password baru minimal 6 karakter">
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 0.85rem; color: var(--text-primary); display: block; margin-bottom: 0.5rem;">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" style="border-radius: 8px; height: 42px;" required placeholder="Ulangi password baru Anda">
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" style="font-weight: 700; border-radius: var(--radius-md); padding: 0.6rem 1.25rem; background: var(--warning-color); border-color: var(--warning-color); color: var(--text-primary);">
                    <i class="ph ph-key"></i> Perbarui Kata Sandi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection