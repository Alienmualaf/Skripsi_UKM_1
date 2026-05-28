@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('header', 'Edit Pengguna')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Edit Data Pengguna</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Perbarui nama lengkap, alamat email, kata sandi, dan tingkat hak akses pengguna platform.</p>
</div>

<div class="card" style="max-width: 600px; border-top: 3px solid var(--accent-color); padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-user-gear" style="color: var(--accent-color);"></i> Pengaturan Akun: {{ $user->name }}
    </h4>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}" style="padding: 0.65rem;">
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}" style="padding: 0.65rem;">
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Password <small class="text-secondary">(Kosongkan jika tidak ingin mengubah)</small></label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" style="padding: 0.65rem;">
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Role Pengguna</label>
            <div style="padding: 0.65rem; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-weight: 700; color: var(--text-primary); display: inline-block;">
                @if($user->role === 'super_admin')
                    Super Admin
                @elseif($user->role === 'user' && $currentAdminUkmId)
                    Admin UKM ({{ $ukms->firstWhere('id', $currentAdminUkmId)->name ?? '-' }})
                @else
                    Mahasiswa / Umum
                @endif
            </div>
            <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">Role pengguna bersifat tetap dan tidak dapat diubah.</small>
        </div>

        <div class="flex gap-2 mt-6">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Update Pengguna</button>
            <a href="{{ route('users.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
        </div>
    </form>
</div>
@endsection
