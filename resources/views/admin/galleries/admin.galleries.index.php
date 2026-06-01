@extends('layouts.app')

@section('title', 'Galeri UKM')
@section('header', 'Monitoring Galeri UKM')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Monitoring Galeri UKM</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Super Admin mengawasi konten publikasi dokumentasi foto dan video dari seluruh UKM.</p>
</div>

<!-- Filter Card -->
<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Filter berdasarkan UKM</label>
            <select name="ukm_id" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua UKM</option>
                @foreach($ukms as $u)
                    <option value="{{ $u->id }}" {{ request('ukm_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Konten</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->filled('ukm_id') || request()->filled('search'))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Gallery Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
    @foreach($galleries as $gallery)
    <div style="background: var(--surface-color); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; box-shadow: var(--shadow-sm); transition: all 0.25s;">
        <div style="height: 180px; background: #000; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
            @if($gallery->type === 'photo')
                <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="{{ $gallery->title }}" style="width: 100%; height: 100%; object-fit: cover;">
            @elseif($gallery->type === 'video')
                <video src="{{ asset('storage/' . $gallery->file_path) }}" style="width: 100%; height: 100%; object-fit: cover;"></video>
                <div style="position: absolute; color: white; background: rgba(0,0,0,0.5); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="ph-fill ph-play" style="font-size: 1.5rem;"></i>
                </div>
            @endif
            <div style="position: absolute; top: 10px; left: 10px; background: var(--accent-color); color: white; padding: 0.25rem 0.6rem; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                {{ $gallery->ukm->name }}
            </div>
        </div>
        <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-weight: 700; font-size: 1rem; margin-bottom: 0.25rem; color: var(--text-primary);">{{ $gallery->title }}</div>
                <div style="color: var(--text-secondary); font-size: 0.75rem;">{{ $gallery->created_at->format('d M Y') }}</div>
            </div>
            <div style="margin-top: 1rem; display: flex; justify-content: flex-end;">
                <form action="/admin/galleries/{{ $gallery->id }}" method="POST" onsubmit="return confirm('Hapus konten ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 8px;">
                        <i class="ph ph-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    @if($galleries->isEmpty())
    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; background: var(--surface-color); border: 1px solid var(--border-color); border-radius: 16px;">
        <i class="ph ph-image-square text-secondary" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
        <p class="text-secondary">Belum ada data galeri.</p>
    </div>
    @endif
</div>
<div style="margin-top: 1.5rem;">
    {{ $galleries->links('shared.pagination') }}
</div>
@endsection
