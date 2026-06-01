@extends('layouts.app')

@section('title', 'Absensi Pelatih: ' . $event->title)
@section('header', 'Pertemuan Absensi Pelatih')

@section('content')
<div class="card mb-4 animate-fade-in" style="background: var(--accent-color); color: white; border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.15);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <h3 style="margin: 0; font-weight: 800; color: #ffffff;">{{ $event->title }}</h3>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.8; font-size: 0.875rem;">
                <i class="ph ph-calendar"></i> {{ $event->start_date->format('d M Y') }} • Agenda Pelatih & Staf
            </p>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
            <a href="{{ route('coach-attendances.index') }}" class="btn" style="background: rgba(255,255,255,0.18); color: white; border: 1px solid rgba(255,255,255,0.3); font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.25rem;">
                <i class="ph ph-arrow-left"></i> Kembali ke Agenda
            </a>
            <a href="{{ route('ukm.rekap.coaches', $event->id) }}" class="btn" style="background: rgba(255,255,255,0.18); color: white; border: 1px solid rgba(255,255,255,0.3); font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.25rem;">
                <i class="ph ph-users-three"></i> Lihat Rekap Pelatih
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: 12px; font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card animate-fade-in" style="border: 1px solid var(--border-color); border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.01);">
    <h4 style="font-weight: 700; margin-bottom: 1.5rem; color: var(--text-color);"><i class="ph ph-list-bullets" style="color: var(--accent-color);"></i> Daftar Pertemuan / Sesi Pelatih</h4>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
        @forelse($sessions as $session)
        <div class="card animate-fade-in" style="border: 1px solid var(--border-color); border-radius: 14px; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.008);">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: var(--accent-light); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="ph ph-calendar-blank"></i>
                    </div>
                    <div>
                        <h4 style="font-weight: 700; font-size: 0.95rem; margin: 0; color: var(--text-color);">{{ $session->title }}</h4>
                        <p style="font-size: 0.725rem; color: var(--text-secondary); margin: 0.15rem 0 0 0;">{{ $session->date->format('d M Y') }}</p>
                    </div>
                </div>
                
                <p style="font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.4; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 36px;">
                    {{ $session->description ?: 'Tidak ada deskripsi pertemuan.' }}
                </p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 0.5rem;">
                <div>
                    @php
                        $filledCount = $session->coachAttendances()->count();
                    @endphp
                    @if($filledCount > 0)
                        <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700; font-size: 0.725rem;">
                            <i class="ph-fill ph-check-circle" style="font-size: 0.75rem;"></i> {{ $filledCount }} Terisi
                        </span>
                    @else
                        <span class="badge" style="background: #f3f4f6; color: #4b5563; font-weight: 600; font-size: 0.725rem;">
                            Belum Diisi
                        </span>
                    @endif
                </div>
                
                <div style="display: flex; gap: 0.35rem; align-items: center;">
                    <a href="{{ route('coach-attendances.session-show', $session->id) }}" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8125rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                        <i class="ph ph-check-square"></i> Kelola
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--text-secondary);">
            <i class="ph ph-calendar-blank" style="font-size: 3rem; opacity: 0.2;"></i>
            <p style="margin-top: 1rem; font-weight: 600;">Belum ada pertemuan pada agenda ini.</p>
            <p style="font-size: 0.8125rem; opacity: 0.8;">Agenda pelatih mengikuti jadwal pertemuan yang terdaftar pada Classroom.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
