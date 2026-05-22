@extends('layouts.app')

@section('title', 'Tambah UKM')
@section('header', 'Tambah UKM Baru')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Tambah UKM Baru</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Daftarkan Unit Kegiatan Mahasiswa baru ke dalam sistem agar mahasiswa dapat bergabung.</p>
</div>

<div class="card" style="max-width: 600px; border-top: 3px solid var(--accent-color); padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-plus-circle" style="color: var(--accent-color);"></i> Profil & Data UKM
    </h4>

    <form action="{{ route('ukm.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama UKM</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Contoh: UKM Basket" style="padding: 0.65rem;">
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi & Visi Misi</label>
            <textarea name="description" class="form-control" rows="5" required placeholder="Jelaskan secara ringkas mengenai fokus kegiatan dan visi misi UKM..." style="padding: 0.65rem; resize: vertical;">{{ old('description') }}</textarea>
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Logo UKM</label>
            <input type="file" name="logo" class="form-control" style="padding: 0.5rem;">
            <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">Gunakan file gambar format PNG/JPG maksimal 2MB.</small>
        </div>

        <!-- Section: Akun Admin UKM -->
        <hr style="border-top: 1px solid var(--border-color); margin: 2rem 0;">
        <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-user-plus" style="color: var(--accent-color);"></i> Akun Admin Utama UKM
        </h4>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Lengkap Admin</label>
            <input type="text" name="admin_name" class="form-control" required value="{{ old('admin_name') }}" placeholder="Contoh: Admin Basket" style="padding: 0.65rem;">
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Email Admin</label>
            <input type="email" name="admin_email" class="form-control" required value="{{ old('admin_email') }}" placeholder="admin.basket@example.com" style="padding: 0.65rem;" autocomplete="off">
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Password Admin</label>
            <input type="password" name="admin_password" class="form-control" required placeholder="Minimal 8 karakter" style="padding: 0.65rem;" autocomplete="new-password">
        </div>

        <div class="flex gap-2 mt-6">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Simpan UKM</button>
            <a href="{{ route('ukm.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
        </div>
    </form>
</div>
@endsection
