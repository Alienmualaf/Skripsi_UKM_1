@extends('layouts.app')

@section('title', 'Edit UKM')
@section('header', 'Edit UKM: ' . $ukm->name)

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Edit Data UKM</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Perbarui data profil, logo, dan identitas Unit Kegiatan Mahasiswa.</p>
</div>

<div class="card" style="max-width: 600px; border-top: 3px solid var(--accent-color); padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-pencil-simple" style="color: var(--accent-color);"></i> Profil UKM: {{ $ukm->name }}
    </h4>

    <form action="{{ route('ukm.update', $ukm->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama UKM</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $ukm->name) }}" style="padding: 0.65rem;">
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi</label>
            <textarea name="description" class="form-control" rows="5" required style="padding: 0.65rem; line-height: 1.5;">{{ old('description', $ukm->description) }}</textarea>
        </div>
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Logo UKM</label>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="width: 60px; height: 60px; border-radius: 0.5rem; background: var(--bg-color); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                    @if($ukm->logo)
                        <img src="{{ filter_var($ukm->logo, FILTER_VALIDATE_URL) ? $ukm->logo : asset('storage/' . $ukm->logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="ph ph-image" style="font-size: 1.5rem; color: var(--text-secondary);"></i>
                    @endif
                </div>
                <input type="file" name="logo" class="form-control" style="padding: 0.5rem;">
            </div>
        </div>

        <div class="flex gap-2 mt-6">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Update UKM</button>
            <a href="{{ route('ukm.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
        </div>
    </form>
</div>
@endsection
