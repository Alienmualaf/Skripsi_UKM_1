@extends('layouts.app')

@section('title', 'Profil UKM')
@section('header', 'Profil UKM: ' . $ukm->name)

@section('content')
@php
    $totalMembers = $ukm->memberships()->where('status', 'approved')->count();
    $totalCoaches = $ukm->coaches()->where('category', 'pelatih')->count();
    $totalEvents = $ukm->events()->count();
    
    $alreadyJoined = auth()->user()->memberships()->where('ukm_id', $ukm->id)->first();
@endphp

<!-- Responsive Styling for Tablet & HP -->
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
    
    .room-hero-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        flex-shrink: 0;
        min-width: 220px;
    }
    
    .room-grid-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        align-items: start;
        margin-top: 0;
    }
    
    .room-coach-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 1rem;
    }
    
    .room-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1.25rem;
    }
    
    @media (max-width: 1024px) {
        .room-grid-layout {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .room-hero-banner {
            padding: 2rem;
        }
        
        .room-hero-container {
            gap: 2rem;
        }
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
        
        .room-hero-actions {
            width: 100%;
            min-width: 100%;
        }
        
        .room-coach-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.85rem;
        }
        
        .room-gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 1rem;
        }
    }
</style>

<!-- Hero Banner (Aesthetic Unified Room Header) -->
<div class="card room-hero-banner" style="margin-bottom: 1.5rem !important;">
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
        
        <!-- Registration Actions -->
        <div class="room-hero-actions">
            @if($alreadyJoined)
                @if($alreadyJoined->status == 'approved')
                    <button class="btn" style="background: white; color: var(--success-color); padding: 0.85rem 1.75rem; border-radius: 14px; font-weight: 800; font-size: 0.95rem; border: none; box-shadow: 0 8px 16px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; cursor: default;" disabled>
                        <i class="ph-fill ph-check-circle" style="font-size: 1.2rem;"></i> SUDAH BERGABUNG
                    </button>
                @else
                    <button class="btn" style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.25); padding: 0.85rem 1.75rem; border-radius: 14px; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; backdrop-filter: blur(5px); cursor: default;" disabled>
                        <i class="ph ph-hourglass" style="font-size: 1.2rem;"></i> MENUNGGU PERSETUJUAN
                    </button>
                @endif
            @else
                @if($ukm->is_recruitment_open)
                    <form action="/member/join" method="POST" style="width: 100%; margin: 0;">
                        @csrf
                        <input type="hidden" name="ukm_id" value="{{ $ukm->id }}">
                        <button type="submit" class="btn" style="background: white; color: var(--accent-color); padding: 0.85rem 1.75rem; border-radius: 14px; font-weight: 800; font-size: 0.95rem; border: none; box-shadow: 0 8px 16px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; transition: all 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 20px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)';">
                            <i class="ph ph-user-plus" style="font-size: 1.2rem;"></i> DAFTAR SEKARANG
                        </button>
                    </form>
                @else
                    <button class="btn" style="background: rgba(255, 255, 255, 0.1); color: rgba(255,255,255,0.6); padding: 0.85rem 1.75rem; border-radius: 14px; font-weight: 800; font-size: 0.95rem; border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; cursor: not-allowed;" disabled>
                        <i class="ph ph-lock" style="font-size: 1.2rem;"></i> PENDAFTARAN DITUTUP
                    </button>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Merged Grid Layout -->
<div class="room-grid-layout">
    
    <!-- Left Column (Main content) -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">
        
        <!-- Sejarah UKM Card -->
        @if($ukm->history)
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--surface-color); padding: 2rem; border-top: 3px solid var(--accent-color); margin-bottom: 0 !important;">
            <h3 style="font-weight: 800; font-size: 1.35rem; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); letter-spacing: -0.02em;">
                <i class="ph-fill ph-book-open" style="color: var(--accent-color); font-size: 1.6rem;"></i> Sejarah UKM
            </h3>
            
            <div style="white-space: pre-line; line-height: 1.7; font-size: 1rem; color: var(--text-primary); font-weight: 500;">
                {{ $ukm->history }}
            </div>
        </div>
        @endif

        <!-- Visi & Misi Card -->
        @if($ukm->vision || $ukm->mission)
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--surface-color); padding: 2rem; border-top: 3px solid var(--accent-color); margin-bottom: 0 !important;">
            <h3 style="font-weight: 800; font-size: 1.35rem; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); letter-spacing: -0.02em;">
                <i class="ph-fill ph-flag-banner" style="color: var(--accent-color); font-size: 1.6rem;"></i> Visi & Misi UKM
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @if($ukm->vision)
                <div style="background: var(--bg-color); border-left: 4px solid var(--accent-color); padding: 1.25rem 1.5rem; border-radius: 4px 16px 16px 4px; box-shadow: var(--shadow-sm);">
                    <h4 style="font-weight: 700; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Visi</h4>
                    <p style="font-size: 1.1rem; font-weight: 600; line-height: 1.6; font-style: italic; color: var(--text-primary); margin: 0;">"{{ $ukm->vision }}"</p>
                </div>
                @endif
                
                @if($ukm->mission)
                <div style="background: var(--bg-color); border-left: 4px solid var(--accent-color); padding: 1.25rem 1.5rem; border-radius: 4px 16px 16px 4px; box-shadow: var(--shadow-sm);">
                    <h4 style="font-weight: 700; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Misi</h4>
                    <div style="white-space: pre-line; line-height: 1.7; font-size: 1rem; color: var(--text-primary); font-weight: 600;">{{ $ukm->mission }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Struktur Organisasi -->
        @if($ukm->structure_image)
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--surface-color); padding: 2rem; border-top: 3px solid var(--accent-color); margin-bottom: 0 !important; display: flex; flex-direction: column; align-items: center;">
            <h3 style="font-weight: 800; font-size: 1.35rem; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); letter-spacing: -0.02em; align-self: flex-start;">
                <i class="ph-fill ph-tree-structure" style="color: var(--accent-color); font-size: 1.6rem;"></i> Struktur Organisasi
            </h3>
            
            <div style="background: var(--bg-color); border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color); text-align: center; max-width: 500px; width: 100%; padding: 0.5rem; display: flex; justify-content: center; align-items: center; box-shadow: var(--shadow-sm); cursor: zoom-in;" onclick="window.open('{{ asset('storage/' . $ukm->structure_image) }}')">
                <img src="{{ asset('storage/' . $ukm->structure_image) }}" alt="Struktur Organisasi {{ $ukm->name }}" style="max-width: 100%; max-height: 350px; object-fit: contain; border-radius: var(--radius-md); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            </div>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.75rem; font-weight: 500; display: flex; align-items: center; gap: 0.25rem;"><i class="ph ph-magnifying-glass-plus"></i> Klik bagan untuk memperbesar di tab baru</span>
        </div>
        @endif

        <!-- Dokumentasi Kegiatan (Galeri) -->
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--surface-color); padding: 2rem; border-top: 3px solid var(--accent-color); margin-bottom: 0 !important;">
            <h3 style="font-weight: 800; font-size: 1.35rem; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); letter-spacing: -0.02em;">
                <i class="ph-fill ph-image" style="color: var(--warning-color); font-size: 1.6rem;"></i> Galeri Dokumentasi
            </h3>
            
            <div class="room-gallery-grid">
                @forelse($ukm->galleries as $gallery)
                <div style="border-radius: 16px; overflow: hidden; position: relative; aspect-ratio: 4/3; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='var(--shadow-md)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)';">
                    @if($gallery->type === 'photo')
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="{{ $gallery->title }}" style="width: 100%; height: 100%; object-fit: cover;" onclick="window.open(this.src)">
                    @elseif($gallery->type === 'video')
                        <video src="{{ asset('storage/' . $gallery->file_path) }}" style="width: 100%; height: 100%; object-fit: cover;" controls></video>
                        <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.3); color: white; pointer-events: none;">
                            <i class="ph-fill ph-play-circle" style="font-size: 2.5rem;"></i>
                        </div>
                    @endif
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 0.75rem; background: linear-gradient(transparent, rgba(0,0,0,0.85)); color: white; font-size: 0.75rem; font-weight: 600; line-height: 1.4;">
                        <span style="display: block; opacity: 0.8; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--warning-color); margin-bottom: 0.15rem;">{{ $gallery->type }}</span>
                        {{ Str::limit($gallery->title, 25) }}
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; color: var(--text-secondary); padding: 3rem 1rem; background: var(--bg-color); border-radius: 16px; border: 1px dashed var(--border-color); width: 100%;">
                    <i class="ph ph-image-square" style="font-size: 2.5rem; color: var(--text-secondary); opacity: 0.5; margin-bottom: 0.5rem;"></i>
                    <p style="font-weight: 600; margin: 0;">Belum ada dokumentasi atau foto diunggah.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
    
    <!-- Right Column (Sidebar) -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">
        
        <!-- Pelatih Resmi UKM -->
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--surface-color); padding: 1.5rem; border-top: 3px solid var(--success-color); margin-bottom: 0 !important;">
            <h4 style="font-weight: 800; font-size: 1.15rem; margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary); letter-spacing: -0.02em;">
                <i class="ph-fill ph-briefcase" style="color: var(--success-color); font-size: 1.4rem;"></i> Pelatih & Pengajar
            </h4>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($ukm->coaches->where('category', 'pelatih') as $coach)
                <div style="display: flex; align-items: center; gap: 0.75rem; background: var(--bg-color); padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border-color);">
                    @if($coach->photo)
                        <img src="{{ asset('storage/' . $coach->photo) }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);">
                    @else
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; color: var(--accent-color); border: 1px solid var(--border-color);">
                            {{ substr($coach->name, 0, 1) }}
                        </div>
                    @endif
                    <div style="flex: 1; min-width: 0;">
                        <h5 style="margin: 0; font-weight: 700; font-size: 0.85rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $coach->name }}</h5>
                        <p style="margin: 0.15rem 0 0 0; font-size: 0.65rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">{{ $coach->category }}</p>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.7rem; color: var(--accent-color); font-weight: 600;">{{ $coach->skills }}</p>
                    </div>
                </div>
                @empty
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin: 0; text-align: center; padding: 1.5rem 0;">Belum ada pelatih terdaftar.</p>
                @endforelse
            </div>
        </div>

        <!-- Kegiatan Mendatang -->
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--surface-color); padding: 1.5rem; border-top: 3px solid var(--accent-color); margin-bottom: 0 !important;">
            <h4 style="font-weight: 800; font-size: 1.15rem; margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary); letter-spacing: -0.02em;">
                <i class="ph-fill ph-calendar-blank" style="color: var(--accent-color); font-size: 1.4rem;"></i> Kegiatan Mendatang
            </h4>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @php
                    $upcomingEvents = $ukm->events()->where('start_date', '>=', now())->get();
                @endphp
                @forelse($upcomingEvents as $event)
                <div style="background: var(--bg-color); padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 0.25rem;">
                    <h5 style="margin: 0; font-weight: 700; font-size: 0.875rem; color: var(--text-primary);">{{ $event->title }}</h5>
                    <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; color: var(--text-secondary);">
                        <i class="ph ph-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</span>
                    </div>
                    @if($event->location)
                    <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; color: var(--text-secondary);">
                        <i class="ph ph-map-pin"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                    @endif
                </div>
                @empty
                <div style="text-align: center; color: var(--text-secondary); padding: 1.5rem 0;">
                    <i class="ph ph-calendar" style="font-size: 1.5rem; opacity: 0.4; margin-bottom: 0.25rem; display: block; margin-left: auto; margin-right: auto;"></i>
                    <p style="font-size: 0.8rem; margin: 0;">Belum ada jadwal kegiatan</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
