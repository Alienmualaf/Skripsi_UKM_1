@extends('layouts.app')

@section('title', 'Edit Pelatih')
@section('header', 'Edit Pelatih PSUP')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Edit Data Pelatih</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Perbarui informasi profil dan spesialisasi pelatih paduan suara.</p>
</div>

<div class="card" style="max-width: 600px; padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-user-gear" style="color: var(--accent-color);"></i> Profil Pelatih: {{ $trainer->name }}
    </h4>

    <form action="{{ route('pengurus.trainers.update', $trainer->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $trainer->name) }}" placeholder="Contoh: Budi Santoso, S.Sn." style="padding: 0.65rem;">
            @error('name') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Spesialisasi / Keahlian</label>
            <input type="text" name="specialty" class="form-control" required value="{{ old('specialty', $trainer->specialty) }}" placeholder="Contoh: Pelatih Vokal Utama / Pianis / Dirigen" style="padding: 0.65rem;">
            @error('specialty') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi Profil (Opsional)</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Contoh: Berdedikasi tinggi dalam membimbing teknik vokal..." style="padding: 0.65rem;">{{ old('description', $trainer->description) }}</textarea>
            @error('description') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">No. Telepon / WhatsApp</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $trainer->phone) }}" placeholder="Contoh: 08123456789" style="padding: 0.65rem;">
            @error('phone') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Foto Profil</label>
            <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 0.5rem;">
                <div style="width: 60px; height: 60px; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--accent-color);">
                    @if($trainer->photo)
                        <img src="{{ asset('storage/' . $trainer->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        {{ substr($trainer->name, 0, 1) }}
                    @endif
                </div>
                <input type="file" name="photo" class="form-control" accept="image/*" style="padding: 0.5rem;">
            </div>
            <p style="font-size: 0.75rem; color: var(--text-secondary);">Format: JPG, PNG (Maks. 2MB). Biarkan kosong jika tidak ingin mengubah foto.</p>
            @error('photo') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2 mt-6">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Update Pelatih</button>
            <a href="{{ route('pengurus.trainers.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
        </div>
    </form>
</div>
@endsection
