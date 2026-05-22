@extends('layouts.app')

@section('title', 'Tambah User')
@section('header', 'Tambah User Baru')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Tambah User Baru</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Buat akun pengguna baru dan tentukan tingkat hak akses atau penugasan UKM.</p>
</div>

<div class="card" style="max-width: 600px; border-top: 3px solid var(--accent-color); padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-user-plus" style="color: var(--accent-color);"></i> Informasi Akun Baru
    </h4>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Contoh: John Doe" style="padding: 0.65rem;">
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="johndoe@example.com" style="padding: 0.65rem;">
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter" style="padding: 0.65rem;">
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Role Pengguna</label>
            <select name="role_type" class="form-control" required id="role-type-select" onchange="toggleUkmSelect()" style="padding: 0.65rem;">
                <option value="user_biasa">User Biasa / Anggota</option>
                <option value="admin_ukm">Admin UKM</option>
                <option value="super_admin">Super Admin</option>
            </select>
            <!-- Hidden input to bind actual role parameter (user vs super_admin) to Backend validation -->
            <input type="hidden" name="role" id="role-hidden" value="user">
        </div>
        
        <div class="form-group mb-4" id="ukm-assign-group" style="display: none;">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Ditugaskan Sebagai Admin UKM</label>
            <select name="assign_admin_ukm_id" id="ukm-select" class="form-control" style="padding: 0.65rem;">
                <option value="">-- Pilih UKM --</option>
                @foreach($ukms as $ukm)
                    <option value="{{ $ukm->id }}">{{ $ukm->name }}</option>
                @endforeach
            </select>
            <small style="color: var(--text-secondary); display: block; margin-top: 0.5rem;">User ini akan langsung terdaftar sebagai admin utama untuk UKM yang dipilih.</small>
        </div>

        <div class="flex gap-2 mt-6">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Simpan Pengguna</button>
            <a href="{{ route('users.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
        </div>
    </form>
</div>

<script>
    function toggleUkmSelect() {
        const type = document.getElementById('role-type-select').value;
        const ukmGroup = document.getElementById('ukm-assign-group');
        const roleHidden = document.getElementById('role-hidden');
        const ukmSelect = document.getElementById('ukm-select');

        if (type === 'admin_ukm') {
            ukmGroup.style.display = 'block';
            ukmSelect.required = true;
            roleHidden.value = 'user';
        } else if (type === 'super_admin') {
            ukmGroup.style.display = 'none';
            ukmSelect.required = false;
            roleHidden.value = 'super_admin';
        } else {
            ukmGroup.style.display = 'none';
            ukmSelect.required = false;
            roleHidden.value = 'user';
        }
    }
    // Initialize
    toggleUkmSelect();
</script>
@endsection
