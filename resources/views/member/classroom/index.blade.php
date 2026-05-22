@extends('layouts.app')

@section('title', 'Aktifitas Agenda')
@section('header', 'Aktifitas: ' . $ukm->name)

@section('content')
<div class="card animate-fade-in">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-weight: 700; font-size: 1.5rem;">Pusat Pembelajaran & Informasi</h3>
        <p style="color: var(--text-secondary);">Silakan pilih agenda yang Anda ikuti untuk mengakses materi dan pengumuman.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
        @forelse($events as $event)
        <div class="card" style="border: 1px solid var(--border-color); padding: 1.5rem; transition: all 0.3s; border-radius: 20px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; padding: 0.75rem; background: var(--accent-color); color: white; border-bottom-left-radius: 12px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                {{ $event->calculated_status }}
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <h4 style="font-weight: 800; font-size: 1.25rem; line-height: 1.3; margin-bottom: 0.5rem;">{{ $event->title }}</h4>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); font-size: 0.8125rem;">
                    <i class="ph ph-calendar"></i> {{ $event->start_date->format('d M Y') }}
                </div>
            </div>

            <a href="/room/{{ $ukm->id }}/classroom/{{ $event->id }}" class="btn btn-primary" style="margin-top: 1.5rem; width: 100%; justify-content: center; font-size: 0.875rem; font-weight: 700; border-radius: 12px; padding: 0.75rem;">
                <i class="ph-fill ph-door-open"></i> Masuk Ruang
            </a>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem;">
            <i class="ph ph-calendar-slash" style="font-size: 4rem; opacity: 0.1; margin-bottom: 1rem;"></i>
            <h4 style="font-weight: 700; color: var(--text-secondary);">Tidak Ada Agenda Terdaftar</h4>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">Anda belum terdaftar dalam agenda kegiatan apapun di UKM ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
