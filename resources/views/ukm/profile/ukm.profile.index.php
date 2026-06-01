@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Profil UKM')
@section('header', 'Profil UKM')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #10b981;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card mb-6">
    <form action="/ukm/profile" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Deskripsi UKM</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $ukm->description) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Sejarah UKM</label>
            <textarea name="history" class="form-control" rows="4">{{ old('history', $ukm->history) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Visi</label>
            <textarea name="vision" class="form-control" rows="3">{{ old('vision', $ukm->vision) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Misi</label>
            <textarea name="mission" class="form-control" rows="4">{{ old('mission', $ukm->mission) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">No. Telepon / WhatsApp</label>
            <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789 atau 628123456789" value="{{ old('phone', $ukm->phone) }}">
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Gunakan nomor aktif yang terhubung dengan WhatsApp admin UKM.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Logo UKM</label>
            <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 0.5rem;">
                <div style="width: 60px; height: 60px; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 0.5rem; overflow: hidden; display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--accent-color);">
                    @if($ukm->logo)
                        <img src="{{ filter_var($ukm->logo, FILTER_VALIDATE_URL) ? $ukm->logo : asset('storage/' . $ukm->logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        {{ substr($ukm->name, 0, 1) }}
                    @endif
                </div>
                @if($isOperator)
                    <input type="file" name="logo" class="form-control" accept="image/*">
                @endif
            </div>
            @if($isOperator)
                <p style="font-size: 0.75rem; color: var(--text-secondary);">Format: JPG, PNG, SVG (Maks. 2MB). Biarkan kosong jika tidak ingin mengubah logo.</p>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label">Struktur Organisasi (Gambar)</label>
            <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 0.5rem;">
                <div style="width: 120px; height: 60px; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 0.5rem; overflow: hidden; display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--accent-color);">
                    @if($ukm->structure_image)
                        <img src="{{ asset('storage/' . $ukm->structure_image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-size: 0.7rem; text-align: center; color: var(--text-secondary);">Belum ada gambar</span>
                    @endif
                </div>
                @if($isOperator)
                    <input type="file" name="structure_image" class="form-control" accept="image/*">
                @endif
            </div>
            @if($isOperator)
                <p style="font-size: 0.75rem; color: var(--text-secondary);">Format: JPG, PNG (Maks. 3MB). Unggah bagan struktur organisasi UKM Anda.</p>
            @endif
        </div>

        <div class="form-group" style="margin-top: 1.5rem; padding: 1rem; border: 1px solid var(--border-color); border-radius: 0.5rem; background: var(--bg-color);">
            <label class="form-label" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0;">
                <input type="hidden" name="is_recruitment_open" value="0">
                <input type="checkbox" name="is_recruitment_open" value="1" {{ $ukm->is_recruitment_open ? 'checked' : '' }} style="width: 1.2rem; height: 1.2rem; cursor: pointer;">
                <span style="font-weight: 600;">Buka Pendaftaran Anggota Baru</span>
            </label>
            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0;">Jika dicentang, mahasiswa dapat melihat dan mendaftar ke UKM Anda dari halaman Daftar UKM.</p>
        </div>

        @if($isOperator)
        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <i class="ph ph-floppy-disk"></i> Simpan Perubahan
            </button>
        </div>
        @endif
    </form>
</div>

<!-- Dynamic Classification Management -->
<div class="card mt-6">
    <h3 class="mb-4">Manajemen Klasifikasi Anggota</h3>
    <p class="text-secondary mb-4" style="font-size: 0.875rem;">Daftar klasifikasi khusus untuk anggota UKM Anda.</p>
    
    @if($isOperator)
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- Form Add -->
        <div>
            <form action="/ukm/profile/classifications" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Klasifikasi Baru</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Sabuk Hitam/Sopran/dll" required>
                </div>
                <button type="submit" class="btn btn-primary w-full">
                    <i class="ph ph-plus"></i> Tambah Klasifikasi
                </button>
            </form>
        </div>

        <!-- List -->
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Klasifikasi</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ukm->classifications as $c)
                    <tr>
                        <td style="font-weight: 500;">{{ $c->name }}</td>
                        <td>
                            <form action="/ukm/profile/classifications/{{ $c->id }}" method="POST" onsubmit="return confirm('Hapus klasifikasi ini? Anggota yang menggunakan klasifikasi ini akan di-reset.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center text-secondary">Belum ada klasifikasi khusus.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div>
        <table class="table" style="max-width: 600px;">
            <thead>
                <tr>
                    <th>Nama Klasifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ukm->classifications as $c)
                <tr>
                    <td style="font-weight: 500;">{{ $c->name }}</td>
                </tr>
                @empty
                <tr>
                    <td class="text-center text-secondary">Belum ada klasifikasi khusus.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
