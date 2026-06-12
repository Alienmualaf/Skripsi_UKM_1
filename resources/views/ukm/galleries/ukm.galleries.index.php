@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Galeri')
@section('header', 'Galeri')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if($isOperator)
<div class="card mb-4">
    <h3 style="margin-bottom: 1rem;">Upload Dokumentasi</h3>
    <form action="/ukm/galleries" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="stat-grid" style="margin-bottom: 0;">
            <div class="form-group">
                <label class="form-label">Judul / Nama Acara</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Jenis</label>
                <select name="type" class="form-control" required>
                    <option value="photo">Foto</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Pilih File</label>
                <input type="file" name="file" class="form-control" required accept="image/*,video/*">
            </div>
        </div>
        <div style="margin-top: 1rem; margin-bottom: 1.25rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 700; font-size: 0.85rem; color: var(--text-primary);">
                <input type="checkbox" name="show_on_landing" value="1" style="width: auto; margin: 0; cursor: pointer;">
                Tampilkan di Landing Page
            </label>
        </div>
        <button type="submit" class="btn btn-primary"><i class="ph ph-upload-simple"></i> Upload ke Galeri</button>
    </form>
</div>
@endif

<!-- Filter Card -->
<div class="card" style="padding: 1.25rem; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="width: 180px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Jenis Media</label>
            <select name="type" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="photo" {{ request('type') == 'photo' ? 'selected' : '' }}>Foto</option>
                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Judul Dokumentasi</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama acara..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['type', 'search']))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding: 0 0.5rem;">
    <span style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 600;">
        Menampilkan {{ $galleries->count() }} dari {{ $galleries->total() }} dokumentasi
    </span>
</div>

<div class="stat-grid" style="margin-bottom: 1.5rem;">
    @forelse($galleries as $item)
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 0;">
        @if($item->type === 'photo')
            <img src="{{ Storage::url($item->file_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 200px; object-fit: cover;">
        @else
            <video src="{{ Storage::url($item->file_path) }}" style="width: 100%; height: 200px; object-fit: cover;" controls></video>
        @endif
        <div style="padding: 1rem;">
            <h4 style="margin: 0 0 0.5rem 0;">{{ $item->title }}</h4>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; color: var(--text-secondary);">Oleh: {{ $item->creator?->name ?? 'Admin' }}</span>
                @if($isOperator)
                <div style="display: flex; gap: 0.35rem; align-items: center;">
                    <form action="/ukm/galleries/{{ $item->id }}/toggle-landing" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; background: {{ $item->show_on_landing ? 'var(--accent-color)' : 'var(--bg-color)' }}; border: 1px solid {{ $item->show_on_landing ? 'var(--accent-color)' : 'var(--border-color)' }}; color: {{ $item->show_on_landing ? '#fff' : 'var(--text-secondary)' }}; display: inline-flex; align-items: center; justify-content: center;" title="{{ $item->show_on_landing ? 'Batal Tampilkan di Landing Page' : 'Tampilkan di Landing Page' }}">
                            <i class="{{ $item->show_on_landing ? 'ph-fill ph-star' : 'ph ph-star' }}"></i>
                        </button>
                    </form>
                    <form action="/ukm/galleries/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus dari galeri?');" style="margin: 0;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; display: inline-flex; align-items: center; justify-content: center;"><i class="ph ph-trash"></i></button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; color: var(--text-secondary); padding: 3rem;">
        Belum ada dokumentasi di galeri.
    </div>
    @endforelse
</div>

<div style="margin-top: 1.25rem; margin-bottom: 2rem;">
    {{ $galleries->links('shared.pagination') }}
</div>
@endsection
