@extends('layouts.app')

@section('title', 'Pusat Kegiatan: ' . $event->title)
@section('header', 'Pusat Kegiatan: ' . $event->title)

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

<div class="animate-fade-in">
    <!-- Header / Banner Agenda -->
    <div style="background: linear-gradient(135deg, var(--accent-color), #2563eb); color: white; border-radius: var(--radius-md); padding: 2.5rem 2rem; margin-bottom: 2rem; position: relative; overflow: hidden; box-shadow: 0 10px 20px -5px rgba(30, 64, 175, 0.15); border-top: 4px solid var(--warning-color);">
        <div style="position: relative; z-index: 2;">
            <span class="badge" style="background: rgba(255, 255, 255, 0.15); color: white; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.725rem; border: 1px solid rgba(255, 255, 255, 0.25); text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 0.75rem;">
                Pusat Kegiatan
            </span>
            <h1 style="font-size: 2rem; font-weight: 800; margin: 0; letter-spacing: -0.025em; color: #ffffff; font-family: 'Outfit', sans-serif;">{{ $event->title }}</h1>
            <p style="font-size: 1rem; opacity: 0.85; margin: 0.5rem 0 0 0; font-weight: 500; display: flex; align-items: center; gap: 0.4rem;">
                <i class="ph ph-calendar"></i> {{ $event->start_date->format('d M Y') }} • <i class="ph ph-hash"></i> {{ $ukm->name }}
            </p>
        </div>
        <!-- Decorative Shapes -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.07); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
    </div>

    <!-- Navigation Tabs (Premium Vercel-Style Segmented Pills) -->
    <div style="display: flex; gap: 0.35rem; background: var(--bg-color); border: 1px solid var(--border-color); padding: 4px; border-radius: 8px; margin-bottom: 2.25rem; width: max-content; font-family: 'Outfit', sans-serif;">
        <button onclick="switchClassroomTab('stream')" class="classroom-tab-btn {{ $activeTab === 'stream' ? 'active' : '' }}" data-tab="stream">
            <i class="ph ph-megaphone" style="font-size: 1rem;"></i> Informasi
        </button>
        <button onclick="switchClassroomTab('materials')" class="classroom-tab-btn {{ $activeTab === 'materials' ? 'active' : '' }}" data-tab="materials">
            <i class="ph ph-notebook" style="font-size: 1rem;"></i> Materi
        </button>
        <button onclick="switchClassroomTab('members')" class="classroom-tab-btn {{ $activeTab === 'members' ? 'active' : '' }}" data-tab="members">
            <i class="ph ph-users-three" style="font-size: 1rem;"></i> Peserta Anggota
        </button>
        <button onclick="switchClassroomTab('attendances')" class="classroom-tab-btn {{ $activeTab === 'attendances' ? 'active' : '' }}" data-tab="attendances">
            <i class="ph ph-list-checks" style="font-size: 1rem;"></i> Presensi & Sesi
        </button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Tab: Stream -->
        <div id="tab-content-stream" class="classroom-tab-content" style="display: {{ $activeTab === 'stream' ? 'grid' : 'none' }}; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">
            <!-- Sidebar -->
            <div class="hidden-mobile" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="card" style="padding: 1.25rem; border-top: 3px solid var(--warning-color);">
                    <h4 style="font-weight: 800; font-size: 0.875rem; margin: 0 0 0.5rem 0; color: var(--text-primary); font-family: 'Outfit', sans-serif;">Mendatang</h4>
                    <p style="font-size: 0.775rem; color: var(--text-secondary); line-height: 1.4; margin: 0 0 1rem 0;">Tidak ada agenda terdekat saat ini.</p>
                    <a href="javascript:void(0)" onclick="switchClassroomTab('materials')" style="font-size: 0.8rem; color: var(--accent-color); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                        Lihat semua <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Feed Thread -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @forelse($announcements as $ann)
                <div class="card" style="padding: 1.5rem; display: flex; gap: 1.25rem; align-items: flex-start; border-left: 3px solid var(--accent-color);">
                    <div style="width: 38px; height: 38px; background: var(--accent-light); color: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: bold;">
                        {{ strtoupper(substr($ann->creator->name, 0, 1)) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                            <div>
                                <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">{{ $ann->creator->name }}</span>
                                <span style="color: var(--text-secondary); font-size: 0.775rem; margin-left: 0.5rem;">• {{ $ann->created_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                        <div style="font-size: 0.875rem; line-height: 1.55; color: var(--text-primary);">
                            {!! nl2br(e($ann->content)) !!}
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 4rem 2rem;" class="card">
                    <i class="ph ph-chat-circle-dots" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; color: var(--text-secondary);"></i>
                    <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada informasi terbaru di agenda ini.</p>
                    <p style="font-size: 0.8125rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Pengumuman kelas akan muncul secara teratur di sini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Tab: Materials -->
        <div id="tab-content-materials" class="classroom-tab-content" style="max-width: 800px; margin: 0 auto; display: {{ $activeTab === 'materials' ? 'flex' : 'none' }}; flex-direction: column; gap: 0.75rem;">
            @forelse($materials as $material)
            <div class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; transition: all 0.2s; border-left: 3px solid {{ $material->link ? 'var(--warning-color)' : 'var(--accent-color)' }};">
                <div style="display: flex; align-items: center; gap: 1rem; min-width: 0; flex: 1;">
                    <div style="width: 42px; height: 42px; background: {{ $material->link ? 'var(--accent-light)' : '#ecfdf5' }}; color: {{ $material->link ? 'var(--accent-color)' : '#10b981' }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.25rem;">
                        <i class="ph ph-{{ $material->type === 'audio' ? 'music-note' : ($material->type === 'video' ? 'video-camera' : 'file-text') }}"></i>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <h4 style="font-weight: 700; margin: 0; font-size: 0.925rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-family: 'Outfit', sans-serif;">{{ $material->material_title ?? $material->title }}</h4>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.725rem; color: var(--text-secondary);">Diposting {{ $material->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <div style="flex-shrink: 0; display: flex; gap: 0.5rem; align-items: center;">
                    @if($material->file_path)
                        <a href="/room/{{ $ukm->id }}/materials/{{ $material->id }}" class="btn btn-secondary" style="padding: 0.45rem 1rem; font-size: 0.8rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                            <i class="ph ph-download"></i> Unduh File
                        </a>
                    @endif
                    @if($material->link)
                        <a href="{{ $material->link }}" target="_blank" class="btn btn-secondary" style="padding: 0.45rem 1rem; font-size: 0.8rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem; color: var(--success-color);">
                            <i class="ph ph-arrow-square-out"></i> Buka Link
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 4rem 2rem;" class="card">
                <i class="ph ph-books" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; color: var(--text-secondary);"></i>
                <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada materi pembelajaran.</p>
                <p style="font-size: 0.8125rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Modul atau materi rujukan akan dibagikan oleh pelatih/pengurus di sini.</p>
            </div>
            @endforelse
        </div>

        <!-- Tab: Members -->
        <div id="tab-content-members" class="classroom-tab-content" style="max-width: 800px; margin: 0 auto; display: {{ $activeTab === 'members' ? 'flex' : 'none' }}; flex-direction: column; gap: 2rem;">
            <!-- Pelatih -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem;">
                    <h4 style="font-size: 1rem; font-weight: 800; color: var(--accent-color); margin: 0; font-family: 'Outfit', sans-serif;">Pengajar & Pelatih</h4>
                    <span style="font-size: 0.75rem; font-weight: 700; background: var(--accent-light); color: var(--accent-color); padding: 0.2rem 0.5rem; border-radius: 6px;">{{ count($coaches) }} Orang</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach($coaches as $coach)
                    <div class="card" style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; margin-bottom: 0; border-left: 3px solid var(--accent-color);">
                        @if($coach->photo)
                            <img src="{{ asset('storage/' . $coach->photo) }}" style="width: 36px; height: 36px; border-radius: 8px; object-fit: cover;">
                        @else
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--accent-light); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--accent-color); font-size: 0.9rem;">{{ substr($coach->name, 0, 1) }}</div>
                        @endif
                        <div style="min-width: 0; flex: 1;">
                            <h5 style="margin: 0; font-weight: 700; font-size: 0.875rem; color: var(--text-primary);">{{ $coach->name }}</h5>
                            <p style="margin: 2px 0 0 0; font-size: 0.725rem; color: var(--text-secondary);">{{ $coach->category }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Peserta -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem;">
                    <h4 style="font-size: 1rem; font-weight: 800; color: var(--text-primary); margin: 0; font-family: 'Outfit', sans-serif;">Rekan Aktifitas</h4>
                    <span style="font-size: 0.75rem; font-weight: 700; background: var(--bg-color); color: var(--text-secondary); padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid var(--border-color);">{{ count($participants) }} Orang</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach($participants as $part)
                    <div class="card" style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; margin-bottom: 0;">
                        @if($part->user->photo)
                            <img src="{{ asset('storage/' . $part->user->photo) }}" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;">
                        @else
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--bg-color); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; color: var(--text-secondary); border: 1px solid var(--border-color);">{{ substr($part->user->name, 0, 1) }}</div>
                        @endif
                        <div style="min-width: 0; flex: 1; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                            <span style="font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">{{ $part->user->name }}</span>
                            @if($part->classification)
                                <span style="font-size: 0.725rem; color: var(--text-secondary); background: var(--bg-color); padding: 2px 8px; border-radius: 6px; border: 1px solid var(--border-color); font-weight: 600;">{{ $part->classification->name }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tab: Attendances -->
        <div id="tab-content-attendances" class="classroom-tab-content" style="max-width: 800px; margin: 0 auto; display: {{ $activeTab === 'attendances' ? 'flex' : 'none' }}; flex-direction: column; gap: 1.5rem;">
            <!-- Personal Stats Summary Card -->
            <div class="card animate-fade-in" style="border-top: 3px solid var(--accent-color); padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; border-radius: 8px; background: var(--accent-light); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="ph ph-chart-line-up"></i>
                    </div>
                    <div>
                        <h4 style="font-weight: 800; margin: 0; font-size: 1rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">Rangkuman Presensi Anda</h4>
                        <p style="color: var(--text-secondary); font-size: 0.8rem; margin: 0.25rem 0 0 0;">
                            Total Sesi Terlaksana: <strong style="color: var(--accent-color);">{{ $totalSessions }} Sesi</strong>
                        </p>
                    </div>
                </div>
                
                <!-- Stats Breakdown -->
                <div style="display: flex; gap: 1.25rem; flex-wrap: wrap; align-items: center;">
                    <div style="text-align: center;">
                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Hadir</span>
                        <span style="font-size: 1.15rem; font-weight: 800; color: var(--success-color);">{{ $hadirCount }}</span>
                    </div>
                    <div style="text-align: center; border-left: 1.5px solid var(--border-color); padding-left: 1.25rem;">
                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Izin</span>
                        <span style="font-size: 1.15rem; font-weight: 800; color: var(--warning-color);">{{ $izinCount }}</span>
                    </div>
                    <div style="text-align: center; border-left: 1.5px solid var(--border-color); padding-left: 1.25rem;">
                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Alpa</span>
                        <span style="font-size: 1.15rem; font-weight: 800; color: var(--danger-color);">{{ $tidakHadirCount }}</span>
                    </div>
                    
                    <div style="text-align: center; border-left: 1.5px solid var(--border-color); padding-left: 1.25rem;">
                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Rasio Kehadiran</span>
                        @php
                            $barColor = 'var(--danger-color)';
                            if ($persentase >= 75) {
                                $barColor = 'var(--success-color)';
                            } elseif ($persentase >= 50) {
                                $barColor = 'var(--warning-color)';
                            }
                        @endphp
                        <span style="font-size: 1.25rem; font-weight: 900; color: {{ $barColor }};">{{ $persentase }}%</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 style="font-weight: 800; margin: 0 0 1.25rem 0; font-size: 1rem; color: var(--text-primary); font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph ph-check-square" style="color: var(--accent-color); font-size: 1.25rem;"></i> 
                    Riwayat Pertemuan & Presensi Sesi
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem;">
                    @forelse($sessions as $session)
                    <div class="card" style="padding: 1.25rem; text-align: center; display: flex; flex-direction: column; justify-content: space-between; margin-bottom: 0; height: 100%;">
                        <div>
                            <div style="font-size: 0.725rem; color: var(--text-secondary); font-weight: 700; margin-bottom: 0.5rem;">{{ $session->date->format('d M Y') }}</div>
                            <h4 style="font-weight: 800; font-size: 0.9rem; color: var(--text-primary); margin: 0 0 1rem 0; font-family: 'Outfit', sans-serif; min-height: 2.25rem; display: flex; align-items: center; justify-content: center;">{{ $session->title }}</h4>
                            
                            @php
                                $att = $session->attendances->first();
                                $statusColor = 'var(--text-secondary)';
                                $statusBg = 'var(--bg-color)';
                                $statusLabel = 'Belum Diabsen';
                                $statusIcon = 'ph-question';
                                $statusBorder = 'var(--border-color)';

                                if($att) {
                                    if($att->status === 'hadir') {
                                        $statusColor = 'var(--success-color)';
                                        $statusBg = '#f0fdf4';
                                        $statusLabel = 'Hadir';
                                        $statusIcon = 'ph-check-circle';
                                        $statusBorder = '#bbf7d0';
                                    } elseif($att->status === 'izin') {
                                        $statusColor = 'var(--warning-color)';
                                        $statusBg = '#fffbeb';
                                        $statusLabel = 'Izin';
                                        $statusIcon = 'ph-info';
                                        $statusBorder = '#fef3c7';
                                    } elseif($att->status === 'tidak hadir' || $att->status === 'alpa') {
                                        $statusColor = 'var(--danger-color)';
                                        $statusBg = '#fef2f2';
                                        $statusLabel = 'Tidak Hadir';
                                        $statusIcon = 'ph-x-circle';
                                        $statusBorder = '#fecaca';
                                    }
                                }
                            @endphp

                            <div style="background: {{ $statusBg }}; color: {{ $statusColor }}; border: 1px solid {{ $statusBorder }}; padding: 0.45rem; border-radius: 8px; font-weight: 800; font-size: 0.775rem; margin-bottom: 1.25rem; display: inline-flex; align-items: center; gap: 0.25rem; justify-content: center; width: 100%;">
                                <i class="ph-fill {{ $statusIcon }}"></i> {{ $statusLabel }}
                            </div>
                        </div>

                        <a href="{{ route('member.sessions.attendance', $session->id) }}" class="btn btn-primary" style="width: 100%; font-size: 0.775rem; padding: 0.45rem; justify-content: center; border-radius: 8px; font-weight: 700;">
                            <i class="ph ph-check-square"></i> {{ $att ? 'Lihat Absensi' : 'Isi Absensi' }}
                        </a>
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem;" class="card">
                        <i class="ph ph-calendar-blank" style="font-size: 3rem; opacity: 0.2; color: var(--text-secondary);"></i>
                        <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada pertemuan/sesi.</p>
                        <p style="font-size: 0.8125rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Riwayat absensi kelas Anda akan muncul di sini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .classroom-tab-btn {
        padding: 0.45rem 1rem;
        font-weight: 700;
        border-radius: 6px;
        font-size: 0.825rem;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--text-secondary);
        border: 1px solid transparent;
        background: transparent;
        cursor: pointer;
    }
    .classroom-tab-btn.active {
        background: var(--surface-color);
        color: var(--accent-color);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }
    .tab-content {
        min-height: 400px;
    }
    @media (max-width: 768px) {
        .hidden-mobile { display: none; }
    }
</style>

<script>
    function switchClassroomTab(tabId) {
        // 1. Sembunyikan seluruh konten tab
        const contents = document.querySelectorAll('.classroom-tab-content');
        contents.forEach(content => {
            content.style.display = 'none';
        });

        // 2. Hapus kelas active dari seluruh button segmented pills
        const buttons = document.querySelectorAll('.classroom-tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });

        // 3. Tampilkan target konten tab yang sesuai dengan property layout aslinya
        const targetContent = document.getElementById('tab-content-' + tabId);
        if (targetContent) {
            if (tabId === 'stream') {
                targetContent.style.display = 'grid';
            } else {
                targetContent.style.display = 'flex';
            }
        }

        // 4. Tambahkan kelas active pada tombol yang diklik
        const targetButton = document.querySelector(`.classroom-tab-btn[data-tab="${tabId}"]`);
        if (targetButton) {
            targetButton.classList.add('active');
        }

        // 5. Perbarui URL di address bar browser secara dinamis tanpa reload halaman
        const newUrl = window.location.pathname + '?tab=' + tabId;
        window.history.replaceState({ tab: tabId }, '', newUrl);
    }
</script>
@endsection
