@extends('layouts.app')

@section('title', 'Absensi Pelatih')
@section('header', 'Absensi Pelatih & BPH')

@section('content')
<div class="card animate-fade-in">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-weight: 700; font-size: 1.25rem;">Pilih Agenda untuk Absensi Pelatih</h3>
        <p style="color: var(--text-secondary); font-size: 0.875rem;">Input absensi pelatih, BPH, dan lainnya berdasarkan agenda kegiatan.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        @foreach($events as $event)
        <div class="card" style="border: 1px solid var(--border-color); padding: 1.25rem; transition: all 0.2s; border-radius: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <h4 style="font-weight: 700; font-size: 1.1rem;">{{ $event->title }}</h4>
                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">
                        <i class="ph ph-calendar"></i> {{ $event->start_date->format('d M Y') }}
                    </p>
                </div>
                <span class="badge {{ $event->calculated_status == 'completed' ? 'badge-success' : 'badge-primary' }}">
                    {{ ucfirst($event->calculated_status) }}
                </span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; -webkit-mask-image: linear-gradient(to right, black 80%, transparent); mask-image: linear-gradient(to right, black 80%, transparent);">
                    @foreach($event->coaches->take(3) as $coach)
                        @if($coach->photo)
                            <img src="{{ asset('storage/' . $coach->photo) }}" style="width: 28px; height: 28px; border-radius: 50%; border: 2px solid white; margin-right: -10px;">
                        @else
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--bg-color); border: 2px solid white; margin-right: -10px; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 800;">{{ substr($coach->name, 0, 1) }}</div>
                        @endif
                    @endforeach
                </div>
                <span style="font-size: 0.75rem; color: var(--text-secondary);">
                    {{ $event->coaches->count() }} pelatih terlibat
                </span>
            </div>

            <a href="{{ route('coach-attendances.sessions', $event->id) }}" class="btn btn-primary" style="width: 100%; text-align: center; justify-content: center; font-weight: 700; border-radius: 12px;">
                Pilih Agenda
            </a>
        </div>
        @endforeach
    </div>
    
    @if($events->isEmpty())
        <div style="text-align: center; padding: 4rem; color: var(--text-secondary);">
            <i class="ph ph-calendar-slash" style="font-size: 3rem; opacity: 0.2;"></i>
            <p style="margin-top: 1rem;">Belum ada agenda kegiatan.</p>
        </div>
    @endif
</div>
@endsection
