@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('header', 'Konfigurasi Sistem')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Konfigurasi & Pemeliharaan Sistem</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola nama platform, data universitas, kontrol registrasi, serta pengelolaan cadangan database secara aman.</p>
</div>

@if(session('success'))
<div class="card mb-6" style="background: var(--success-light); border-left: 4px solid var(--success-color); color: var(--success-color); padding: 1rem; font-weight: 600; margin-bottom: 1.5rem;">
    <i class="ph ph-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="card mb-6" style="background: var(--danger-light); border-left: 4px solid var(--danger-color); color: var(--danger-color); padding: 1rem; font-weight: 600; margin-bottom: 1.5rem;">
    <i class="ph ph-warning-circle"></i> {{ session('error') }}
</div>
@endif

<div style="display: grid; grid-template-columns: 3fr 2fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Identitas Institusi -->
    <div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem; margin-bottom: 0 !important;">
        <h4 style="margin-bottom: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
            <i class="ph ph-buildings" style="color: var(--accent-color);"></i> Identitas Institusi & Operasional
        </h4>
        
        <form action="/admin/settings" method="POST">
            @csrf
            
            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Aplikasi / Platform</label>
                <input type="text" name="app_name" class="form-control" value="{{ old('app_name', $settings['app_name'] ?? 'Sistem Informasi UKM') }}" required style="padding: 0.65rem;">
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Universitas</label>
                <input type="text" name="university_name" class="form-control" value="{{ old('university_name', $settings['university_name'] ?? 'Universitas Pancasila') }}" required style="padding: 0.65rem;">
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Kontak Admin Support</label>
                <input type="email" name="admin_contact" class="form-control" value="{{ old('admin_contact', $settings['admin_contact'] ?? 'admin@univpancasila.ac.id') }}" required style="padding: 0.65rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Pendaftaran Anggota Baru</label>
                    <select name="registration_status" class="form-control" style="padding: 0.65rem;">
                        <option value="open" {{ (old('registration_status', $settings['registration_status'] ?? '') == 'open') ? 'selected' : '' }}>Dibuka (Open)</option>
                        <option value="closed" {{ (old('registration_status', $settings['registration_status'] ?? '') == 'closed') ? 'selected' : '' }}>Ditutup (Closed)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Mode Pemeliharaan (Maintenance)</label>
                    <select name="maintenance_mode" class="form-control" style="padding: 0.65rem;">
                        <option value="inactive" {{ (old('maintenance_mode', $settings['maintenance_mode'] ?? '') == 'inactive') ? 'selected' : '' }}>Nonaktif (Normal)</option>
                        <option value="active" {{ (old('maintenance_mode', $settings['maintenance_mode'] ?? '') == 'active') ? 'selected' : '' }}>Aktif (Pemeliharaan)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-weight: 700;">
                <i class="ph ph-floppy-disk"></i> Simpan Konfigurasi
            </button>
        </form>
    </div>

    <!-- Backup & Restore -->
    <div class="card" style="border-top: 3px solid var(--warning-color); padding: 1.5rem; margin-bottom: 0 !important;">
        <h4 style="margin-bottom: 1rem; font-weight: 700; color: var(--warning-color); display: flex; align-items: center; gap: 0.5rem;">
            <i class="ph ph-database" style="color: var(--warning-color);"></i> Backup & Restore Database
        </h4>
        
        <p style="color: var(--text-secondary); font-size: 0.825rem; margin-bottom: 1.25rem; line-height: 1.4;">
            Unduh salinan cadangan lengkap basis data Anda secara berkala untuk mengamankan data pengguna, buku kas keuangan, riwayat presensi, dan agenda UKM.
        </p>
        
        <!-- Backup Action Form -->
        <form action="/admin/settings/backup" method="POST" style="margin-bottom: 1.5rem;">
            @csrf
            <button type="submit" class="btn btn-warning" style="width: 100%; padding: 0.75rem; font-weight: 700;">
                <i class="ph ph-download-simple"></i> Backup Database (SQL)
            </button>
        </form>

        <!-- Restore Action Form -->
        <form action="/admin/settings/restore" method="POST" enctype="multipart/form-data" style="border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
            @csrf
            
            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Upload File .SQL untuk Restore</label>
                <input type="file" name="backup_file" class="form-control" accept=".sql" required style="padding: 0.5rem;">
                <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Pastikan file backup berformat .SQL valid hasil download dari sistem ini.</div>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 0.75rem; font-weight: 700; background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary);" onclick="return confirm('Apakah Anda yakin ingin memulihkan database? Seluruh data saat ini akan digantikan oleh data dari file backup!')">
                <i class="ph ph-upload-simple"></i> Restore Database
            </button>
        </form>
    </div>
</div>
@endsection
