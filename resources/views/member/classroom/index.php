@extends('layouts.app')

@section('title', 'Classroom Saya')
@section('header', 'Classroom Saya')

@section('content')
<link rel="stylesheet" href="{{ asset('css/member.css') }}">

<!-- Hero Banner (Aesthetic Unified Room Header) -->
<div class="card room-hero-banner" style="background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%); color: white; border-radius: 16px; padding: 2.5rem; position: relative; overflow: hidden; margin-bottom: 2rem; box-shadow: 0 10px 20px -5px rgba(29, 78, 216, 0.15);">
    <div style="position: absolute; right: -50px; top: -50px; width: 250px; height: 250px; border-radius: 50%; background: rgba(255, 255, 255, 0.05); filter: blur(30px); pointer-events: none;"></div>
    <div style="position: absolute; left: 10%; bottom: -80px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255, 255, 255, 0.03); filter: blur(20px); pointer-events: none;"></div>
    
    <div class="room-hero-container" style="position: relative; z-index: 2;">
        <div class="room-hero-text">
            <h2 class="room-hero-title" style="margin: 0; font-size: 2rem; font-weight: 800; font-family: 'Outfit', sans-serif;">Pusat Pembelajaran & Classroom</h2>
            <p style="margin: 0.5rem 0 0 0; color: rgba(255, 255, 255, 0.85); line-height: 1.6; font-size: 1rem; font-weight: 400; max-width: 700px;">
                Akses materi latihan, jadwal presensi, target lagu, dan pengumuman untuk setiap penampilan yang Anda ikuti.
            </p>
        </div>
    </div>
</div>

<div class="card animate-fade-in" style="padding: 2rem; border-radius: 16px; background: var(--bg-color); border: 1px solid var(--border-color);">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-weight: 800; font-size: 1.35rem; color: var(--text-primary); font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
            <i class="ph-fill ph-chalkboard" style="color: var(--accent-color);"></i> Daftar Classroom Aktif
        </h3>
        <p style="color: var(--text-secondary); margin-top: 0.25rem; font-size: 0.9rem;">Silakan pilih ruang kelas untuk masuk.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
        @forelse($classrooms as $classroom)
        <div class="card" style="border: 1px solid var(--border-color); padding: 1.75rem; transition: all 0.3s; border-radius: 16px; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 220px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);" onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='var(--accent-color)'" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border-color)'">
            <div style="position: absolute; top: 0; right: 0; padding: 0.5rem 1rem; background: var(--accent-color); color: white; border-bottom-left-radius: 12px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">
                {{ $classroom->status }}
            </div>
            
            <div style="margin-bottom: 1.5rem; padding-top: 0.5rem;">
                <span style="font-size: 0.725rem; font-weight: 700; color: var(--accent-color); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">
                    {{ $classroom->performance && is_null($classroom->performance->program_id) ? 'PENUGASAN JOB' : 'PENAMPILAN' }}
                </span>
                <h4 style="font-weight: 800; font-size: 1.25rem; line-height: 1.35; margin: 0 0 0.5rem 0; color: var(--text-primary); font-family: 'Outfit', sans-serif;">
                    {{ $classroom->performance->title ?? 'Classroom' }}
                </h4>
                <p style="margin: 0; color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                    {{ $classroom->description ?? 'Belum ada deskripsi classroom.' }}
                </p>
            </div>

            <div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.775rem; color: var(--text-secondary); border-top: 1px solid var(--border-color); padding-top: 1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph ph-calendar" style="color: var(--accent-color); font-size: 0.95rem;"></i>
                        <span>{{ date('d-m-Y', strtotime($classroom->performance->performance_date ?? now())) }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="ph ph-map-pin" style="color: var(--accent-color); font-size: 0.95rem;"></i>
                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $classroom->performance->venue ?? '-' }}</span>
                    </div>
                </div>

                <a href="{{ route('member.classrooms.show', $classroom->id) }}" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 0.875rem; font-weight: 700; border-radius: 10px; padding: 0.65rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="ph-fill ph-door-open"></i> Masuk Classroom
                </a>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem;">
            <i class="ph ph-calendar-slash" style="font-size: 4rem; opacity: 0.15; margin-bottom: 1rem; color: var(--text-secondary);"></i>
            <h4 style="font-weight: 700; color: var(--text-secondary); font-family: 'Outfit', sans-serif;">Tidak Ada Classroom Terdaftar</h4>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">Anda tidak terdaftar sebagai peserta pada penampilan manapun yang aktif.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
