@extends('layouts.app')

@section('title', 'Absensi Pelatih')
@section('header', 'Absensi Pelatih & BPH')

@section('content')
<!-- Filter Card -->
<div class="card mb-4" style="border-top: 3px solid var(--accent-color); padding: 1.25rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Agenda Kegiatan</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama agenda, lokasi..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->filled('search'))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card animate-fade-in">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-weight: 700; font-size: 1.25rem;">Pilih Agenda untuk Absensi Pelatih</h3>
        <p style="color: var(--text-secondary); font-size: 0.875rem;">Total: {{ $events->total() }} agenda ditemukan.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        @foreach($events as $event)
        <div class="card" style="border: 1px solid var(--border-color); padding: 1.25rem; transition: all 0.2s; border-radius: 16px; margin-bottom: 0;">
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

    <div style="margin-top: 1.75rem;">
        {{ $events->links('shared.pagination') }}
    </div>
</div>
@endsection
