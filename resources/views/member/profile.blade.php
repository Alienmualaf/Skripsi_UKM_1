@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header', 'Pengaturan Profil Saya')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card" style="max-width: 600px; padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-user-focus" style="color: var(--accent-color);"></i> Detail Akun & Profil Penyanyi
    </h4>

    <form action="{{ route('member.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', auth()->user()->name) }}" placeholder="Nama Lengkap Anda" style="padding: 0.65rem;">
            @error('name') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Alamat Email</label>
            <input type="email" class="form-control" disabled value="{{ auth()->user()->email }}" style="padding: 0.65rem; background: #f1f5f9; cursor: not-allowed; color: #64748b;">
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Email akun tidak dapat diubah secara mandiri.</p>
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">NPM (Nomor Pokok Mahasiswa)</label>
            <input type="text" name="nim" class="form-control" required value="{{ old('nim', $member->nim) }}" placeholder="Contoh: 45202100XX" style="padding: 0.65rem;">
            @error('nim') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Fakultas</label>
            <select name="faculty" class="form-control" required style="padding: 0.65rem;">
                <option value="">-- Pilih Fakultas --</option>
                <option value="Ekonomi & Bisnis" {{ old('faculty', $member->faculty) === 'Ekonomi & Bisnis' ? 'selected' : '' }}>Ekonomi & Bisnis</option>
                <option value="Teknik" {{ old('faculty', $member->faculty) === 'Teknik' ? 'selected' : '' }}>Teknik</option>
                <option value="Psikologi" {{ old('faculty', $member->faculty) === 'Psikologi' ? 'selected' : '' }}>Psikologi</option>
                <option value="Hukum" {{ old('faculty', $member->faculty) === 'Hukum' ? 'selected' : '' }}>Hukum</option>
                <option value="Ilmu Komunikasi" {{ old('faculty', $member->faculty) === 'Ilmu Komunikasi' ? 'selected' : '' }}>Ilmu Komunikasi</option>
                <option value="Farmasi" {{ old('faculty', $member->faculty) === 'Farmasi' ? 'selected' : '' }}>Farmasi</option>
                <option value="Pariwisata" {{ old('faculty', $member->faculty) === 'Pariwisata' ? 'selected' : '' }}>Pariwisata</option>
            </select>
            @error('faculty') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">No. Telepon / WhatsApp</label>
            <input type="text" name="phone" class="form-control" required value="{{ old('phone', $member->phone) }}" placeholder="Contoh: 08123456789" style="padding: 0.65rem;">
            @error('phone') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Klasifikasi Suara Saya</label>
            <input type="text" class="form-control" disabled value="{{ $member->voiceClassification->name ?? 'Belum Ditentukan' }}" style="padding: 0.65rem; background: #f1f5f9; cursor: not-allowed; color: #64748b; font-weight: bold;">
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Klasifikasi ditentukan oleh pelatih/pengurus melalui tes vokal.</p>
        </div>

        <div class="form-group mb-6" style="border-top: 1px solid var(--border-color); padding-top: 1.5rem; margin-top: 1.5rem;">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Ubah Password (Opsional)</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru jika ingin mengubah" style="padding: 0.65rem;">
            @error('password') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-weight: 700;">Simpan Perubahan</button>
    </form>
</div>
@endsection
