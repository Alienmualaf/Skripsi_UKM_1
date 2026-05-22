@extends('layouts.app')

@section('title', 'Materi Pembelajaran')
@section('header', $event->title)

@section('content')
<div class="card animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="font-weight: 700; font-size: 1.5rem;">Materi & Referensi</h3>
            <p style="color: var(--text-secondary);">Unduh atau akses materi pembelajaran untuk agenda <strong>{{ $event->title }}</strong>.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        @forelse($materials as $mat)
        <div style="padding: 1.5rem; border: 1px solid var(--border-color); border-radius: 20px; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--bg-color); display: flex; align-items: center; justify-content: center; color: var(--accent-color); margin-bottom: 1.25rem;">
                    <i class="ph {{ $mat->type == 'audio' ? 'ph-music-note' : ($mat->type == 'video' ? 'ph-video-camera' : 'ph-file-text') }}" style="font-size: 1.5rem;"></i>
                </div>
                <h4 style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem; line-height: 1.3;">{{ $mat->title }}</h4>
                <p style="font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 1rem;">{{ $mat->description ?: 'Tidak ada deskripsi tambahan.' }}</p>
            </div>
            
            <div style="display: flex; gap: 0.5rem;">
                @if($mat->file_path)
                    <a href="/room/{{ $ukm->id }}/materials/{{ $mat->id }}" class="btn btn-primary" style="flex: 1; justify-content: center; font-size: 0.8125rem;">
                        <i class="ph ph-download-simple"></i> Unduh
                    </a>
                @endif
                @if($mat->link)
                    <a href="{{ $mat->link }}" target="_blank" class="btn" style="flex: 1; justify-content: center; background: var(--bg-color); color: var(--text-primary); font-size: 0.8125rem;">
                        <i class="ph ph-link"></i> Buka Link
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem;">
            <i class="ph ph-books" style="font-size: 4rem; opacity: 0.1; margin-bottom: 1rem;"></i>
            <h4 style="font-weight: 700; color: var(--text-secondary);">Materi Belum Tersedia</h4>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">Admin belum mengunggah materi untuk agenda ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
