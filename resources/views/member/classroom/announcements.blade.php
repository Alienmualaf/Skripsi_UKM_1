@extends('layouts.app')

@section('title', 'Pengumuman Agenda')
@section('header', $event->title)

@section('content')
<div class="card animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="font-weight: 700; font-size: 1.5rem;">Pengumuman & Informasi</h3>
            <p style="color: var(--text-secondary);">Informasi resmi terkait agenda <strong>{{ $event->title }}</strong>.</p>
        </div>
    </div>

    <div style="max-width: 800px;">
        @forelse($announcements as $ann)
        <div style="padding: 1.5rem; border: 1px solid var(--border-color); border-radius: 16px; margin-bottom: 1.5rem; background: var(--bg-color);">
            <div style="margin-bottom: 1rem;">
                <h4 style="font-weight: 800; font-size: 1.25rem; color: var(--text-primary);">{{ $ann->title }}</h4>
                <div style="display: flex; align-items: center; gap: 1rem; color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                    <span style="display: flex; align-items: center; gap: 0.25rem;"><i class="ph ph-user"></i> {{ $ann->creator->name }}</span>
                    <span style="display: flex; align-items: center; gap: 0.25rem;"><i class="ph ph-clock"></i> {{ $ann->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
            <div style="color: var(--text-primary); font-size: 1rem; line-height: 1.7; white-space: pre-line;">
                {{ $ann->content }}
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 5rem 2rem;">
            <i class="ph ph-megaphone-slash" style="font-size: 4rem; opacity: 0.1; margin-bottom: 1rem;"></i>
            <h4 style="font-weight: 700; color: var(--text-secondary);">Belum Ada Pengumuman</h4>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">Saat ini belum ada informasi khusus untuk agenda ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
