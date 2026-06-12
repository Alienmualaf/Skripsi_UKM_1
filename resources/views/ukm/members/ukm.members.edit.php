@extends('layouts.app')

@section('title', 'Edit Anggota')
@section('header', 'Edit Anggota PSUP')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Perbarui Detail Anggota</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Perbarui informasi nomor induk mahasiswa, kontak, jenis suara, dan status aktif penyanyi.</p>
</div>

<div class="card" style="max-width: 600px; padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-user-gear" style="color: var(--accent-color);"></i> Profil Anggota: {{ $member->user->name ?? 'Pengguna' }}
    </h4>

    <form action="{{ route('ukm.members.update', $member->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">NPM (Nomor Pokok Mahasiswa)</label>
            <input type="text" name="npm" class="form-control" value="{{ old('npm', $member->npm) }}" placeholder="Contoh: 45202100XX" style="padding: 0.65rem;">
            @error('npm') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Fakultas</label>
            <select name="faculty" class="form-control" style="padding: 0.65rem;">
                <option value="">-- Pilih Fakultas --</option>
                <option value="Ekonomi & Bisnis" {{ old('faculty', $member->faculty) == 'Ekonomi & Bisnis' ? 'selected' : '' }}>Ekonomi & Bisnis</option>
                <option value="Teknik" {{ old('faculty', $member->faculty) == 'Teknik' ? 'selected' : '' }}>Teknik</option>
                <option value="Psikologi" {{ old('faculty', $member->faculty) == 'Psikologi' ? 'selected' : '' }}>Psikologi</option>
                <option value="Hukum" {{ old('faculty', $member->faculty) == 'Hukum' ? 'selected' : '' }}>Hukum</option>
                <option value="Ilmu Komunikasi" {{ old('faculty', $member->faculty) == 'Ilmu Komunikasi' ? 'selected' : '' }}>Ilmu Komunikasi</option>
                <option value="Farmasi" {{ old('faculty', $member->faculty) == 'Farmasi' ? 'selected' : '' }}>Farmasi</option>
                <option value="Pariwisata" {{ old('faculty', $member->faculty) == 'Pariwisata' ? 'selected' : '' }}>Pariwisata</option>
            </select>
            @error('faculty') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">No. HP / WhatsApp</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}" placeholder="Contoh: 0812345678" style="padding: 0.65rem;">
            @error('phone') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Klasifikasi Suara</label>
            @if($member->user && $member->user->role && $member->user->role->name === 'anggota')
                <select name="voice_classification_id" class="form-control" style="padding: 0.65rem;">
                    <option value="">- Belum Ditentukan -</option>
                    @foreach($classifications as $c)
                        <option value="{{ $c->id }}" {{ old('voice_classification_id', $member->voice_classification_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('voice_classification_id') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            @else
                <div style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.75rem; border-radius: 8px; font-size: 0.875rem; color: var(--text-secondary);">
                    <i class="ph ph-info" style="vertical-align: middle; margin-right: 0.35rem; color: var(--accent-color);"></i>
                    Klasifikasi suara hanya untuk Anggota. (Role saat ini: <strong>{{ $member->user && $member->user->role ? $member->user->role->display_name : 'Tidak Ada' }}</strong>)
                </div>
                <input type="hidden" name="voice_classification_id" value="">
            @endif
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Status Keanggotaan</label>
            <select name="status" class="form-control" required style="padding: 0.65rem;">
                <option value="Anggota Aktif" {{ old('status', $member->status) === 'Anggota Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Alumni" {{ old('status', $member->status) === 'Alumni' ? 'selected' : '' }}>Alumni</option>
            </select>
            @error('status') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2 mt-6">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Simpan Perubahan</button>
            <a href="{{ route('ukm.members') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
        </div>
    </form>
</div>
@endsection
