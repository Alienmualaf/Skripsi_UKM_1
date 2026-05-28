@extends('layouts.app')

@section('title', 'Pusat Kegiatan')
@section('header', 'Pusat Kegiatan: ' . $ukm->name)

@section('content')
@php
    $totalMembers = $ukm->memberships()->where('status', 'approved')->count();
    $totalCoaches = $ukm->coaches()->where('category', 'pelatih')->count();
    $totalEvents = $ukm->events()->count();
@endphp

<!-- Responsive Styling for Banner -->
<style>
    .room-hero-banner {
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-hover) 100%); 
        border: none; 
        border-radius: 24px; 
        position: relative; 
        overflow: hidden; 
        padding: 2.5rem; 
        color: white; 
        box-shadow: var(--shadow-lg);
        margin-bottom: 2rem !important;
    }
    
    .room-hero-container {
        display: flex;
        align-items: center;
        gap: 2.5rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    
    .room-logo-wrapper {
        width: 120px;
        height: 120px;
        background-color: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-shrink: 0;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s;
    }
    .room-logo-wrapper:hover {
        transform: scale(1.05);
    }
    
    .room-hero-text {
        flex: 1;
        min-width: 300px;
    }
    
    .room-hero-title-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 0.75rem;
    }
    
    .room-hero-title {
        margin: 0; 
        font-size: 2.25rem; 
        font-weight: 800; 
        color: white; 
        letter-spacing: -0.03em; 
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .room-hero-stats {
        display: flex; 
        gap: 1rem; 
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .room-hero-banner {
            padding: 1.5rem;
            border-radius: 20px;
            text-align: center;
        }
        
        .room-hero-container {
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }
        
        .room-logo-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 20px;
        }
        
        .room-hero-text {
            min-width: 100%;
        }
        
        .room-hero-title-container {
            flex-direction: column;
            gap: 0.5rem;
            align-items: center;
            justify-content: center;
        }
        
        .room-hero-title {
            font-size: 1.85rem;
        }
        
        .room-hero-stats {
            justify-content: center;
            gap: 0.75rem;
        }
    }
</style>

<!-- Hero Banner (Aesthetic Unified Room Header) -->
<div class="card room-hero-banner">
    <div style="position: absolute; right: -50px; top: -50px; width: 250px; height: 250px; border-radius: 50%; background: rgba(255, 255, 255, 0.05); filter: blur(30px); pointer-events: none;"></div>
    <div style="position: absolute; left: 10%; bottom: -80px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255, 255, 255, 0.03); filter: blur(20px); pointer-events: none;"></div>
    
    <div class="room-hero-container">
        <!-- Logo -->
        <div class="room-logo-wrapper">
            @if($ukm->logo)
                <img src="{{ filter_var($ukm->logo, FILTER_VALIDATE_URL) ? $ukm->logo : asset('storage/' . $ukm->logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <img src="{{ asset('images/logoup.png') }}" style="width: 70%; height: 70%; object-fit: contain; filter: brightness(0) invert(1);">
            @endif
        </div>
        
        <!-- UKM Title & Description -->
        <div class="room-hero-text">
            <div class="room-hero-title-container">
                <h2 class="room-hero-title">{{ $ukm->name }}</h2>
                
                @if($ukm->is_recruitment_open)
                    <span style="background: rgba(16, 185, 129, 0.25); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); padding: 0.4rem 0.8rem; border-radius: 12px; font-weight: 800; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.35rem; text-transform: uppercase; letter-spacing: 0.05em; backdrop-filter: blur(5px);">
                        <i class="ph-fill ph-circle" style="font-size: 0.6rem;"></i> Pendaftaran Buka
                    </span>
                @else
                    <span style="background: rgba(239, 68, 68, 0.25); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); padding: 0.4rem 0.8rem; border-radius: 12px; font-weight: 800; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.35rem; text-transform: uppercase; letter-spacing: 0.05em; backdrop-filter: blur(5px);">
                        <i class="ph-fill ph-circle" style="font-size: 0.6rem;"></i> Pendaftaran Tutup
                    </span>
                @endif
            </div>
            
            <p style="margin: 0 0 1.5rem 0; color: rgba(255, 255, 255, 0.85); line-height: 1.6; font-size: 1rem; font-weight: 400; max-width: 700px;">{{ $ukm->description ?? 'Belum ada deskripsi.' }}</p>
            
            <!-- Quick Info Metrics -->
            <div class="room-hero-stats">
                <div style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); color: white; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-users-three" style="color: rgba(255, 255, 255, 0.7);"></i> {{ $totalMembers }} Anggota Aktif
                </div>
                <div style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); color: white; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-briefcase" style="color: rgba(255, 255, 255, 0.7);"></i> {{ $totalCoaches }} Pelatih
                </div>
                <div style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); color: white; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-calendar-blank" style="color: rgba(255, 255, 255, 0.7);"></i> {{ $totalEvents }} Agenda
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card animate-fade-in">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-weight: 700; font-size: 1.5rem;">Pusat Kegiatan</h3>
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
