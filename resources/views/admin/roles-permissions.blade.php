@extends('layouts.app')

@section('title', 'Hak Akses & Role')
@section('header', 'Matriks Hak Akses & Role')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Matriks Hak Akses & Peran (Role & Permission)</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola hak akses default, kelola peran khusus dalam organisasi paduan suara, serta pantau matriks otorisasi sistem.</p>
</div>

<!-- Grid Layout -->
<div style="display: grid; grid-template-columns: 1fr; lg:grid-template-columns: 3fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    
    <!-- Left: Matriks Permission -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-shield-check" style="color: var(--accent-color);"></i> Matriks Hak Akses Pengguna
        </h4>
        
        <p style="font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.5;">
            Berikut adalah pembagian otorisasi bawaan sistem. Tindakan administratif sensitif dibatasi sesuai fungsi dan peran masing-masing.
        </p>

        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fitur / Otorisasi Modul</th>
                        <th style="text-align: center;">Administrator</th>
                        <th style="text-align: center;">Admin UKM</th>
                        <th style="text-align: center;">Pengurus</th>
                        <th style="text-align: center;">Anggota</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Mengelola Seluruh Pengguna & Role</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Backup & Restore Database</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Pengaturan Platform Global & SMTP</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Mengelola Profil & Pembina/Pelatih UKM</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Mengelola Penerimaan & Klasifikasi Suara</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Mengubah Status Keanggotaan (Aktif/Alumni)</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Mengelola Agenda Latihan & Proker</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Mengelola Uang Kas & Transaksi Keuangan</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Mengelola Persuratan (Masuk/Keluar)</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--danger-color); font-size: 1.25rem;"><i class="ph ph-x-circle" style="font-weight: bold;"></i></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">Mengunduh & Memutar Materi (Partitur, Audio)</td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                        <td style="text-align: center; color: var(--success-color); font-size: 1.25rem;"><i class="ph ph-check-circle" style="font-weight: bold;"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right: Roles list & Store Form -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- List of Roles -->
        <div class="card" style="padding: 1.5rem;">
            <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <i class="ph ph-users" style="color: var(--accent-color);"></i> Daftar Peran (Role)
            </h4>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($roles as $role)
                    <div style="padding: 0.65rem; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                            <span style="font-weight: 700; font-size: 0.8125rem; color: var(--text-primary);">{{ $role->display_name }}</span>
                            @if(!in_array($role->name, ['administrator', 'admin_ukm', 'pengurus', 'anggota']))
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Hapus role khusus ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color: var(--danger-color); cursor:pointer;"><i class="ph ph-trash" style="font-size: 0.85rem;"></i></button>
                                </form>
                            @else
                                <span style="font-size: 0.65rem; color: var(--text-muted); font-weight: bold; text-transform: uppercase;">System</span>
                            @endif
                        </div>
                        <p style="margin: 0; font-size: 0.7rem; color: var(--text-secondary); line-height: 1.3;">{{ $role->description ?? 'Tidak ada deskripsi.' }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Create Role Form -->
        <div class="card" style="padding: 1.5rem;">
            <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <i class="ph ph-plus-circle" style="color: var(--accent-color);"></i> Tambah Role Baru
            </h4>
            
            <form action="{{ route('admin.roles.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 0.85rem;">
                @csrf
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem; display: block;">Key Nama Role (Kecil/Underscore)</label>
                    <input type="text" name="name" required placeholder="misal: pembina_ukm" class="form-control" style="height: 2.25rem; font-size: 0.8125rem; border-radius: 6px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem; display: block;">Nama Tampilan (Display Name)</label>
                    <input type="text" name="display_name" required placeholder="misal: Pembina UKM" class="form-control" style="height: 2.25rem; font-size: 0.8125rem; border-radius: 6px;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 700; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem; display: block;">Deskripsi Singkat</label>
                    <textarea name="description" placeholder="Deskripsi peran..." class="form-control" style="font-size: 0.8125rem; border-radius: 6px; min-height: 60px; padding: 0.35rem;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="height: 2.25rem; font-size: 0.8125rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem;">
                    <i class="ph ph-plus"></i> Simpan Role
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
