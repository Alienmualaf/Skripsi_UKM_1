@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('header', 'Halo, ' . auth()->user()->name)

@section('content')
<!-- Responsive Styling for Tablet & HP -->
<style>
    .member-hero-banner {
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-hover) 100%); 
        border: none; 
        border-radius: 24px; 
        position: relative; 
        overflow: hidden; 
        padding: 2.5rem; 
        color: white; 
        box-shadow: var(--shadow-lg);
    }
    
    .member-hero-container {
        display: flex;
        align-items: center;
        gap: 2.5rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    
    .member-avatar-wrapper {
        width: 120px;
        height: 120px;
        background-color: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-shrink: 0;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s;
    }
    .member-avatar-wrapper:hover {
        transform: scale(1.05);
    }
    
    .member-hero-text {
        flex: 1;
        min-width: 300px;
    }
    
    .member-hero-title-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }
    
    .member-hero-title {
        margin: 0; 
        font-size: 2.25rem; 
        font-weight: 800; 
        color: white; 
        letter-spacing: -0.03em; 
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .member-hero-stats {
        display: flex; 
        gap: 1rem; 
        flex-wrap: wrap;
    }
    
    .member-hero-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        flex-shrink: 0;
        min-width: 220px;
    }
    
    .member-grid-layout {
        display: grid;
        grid-template-columns: 2fr 1.2fr;
        gap: 1.5rem;
        align-items: start;
        margin-top: 1.5rem;
    }
    
    .ukm-room-card {
        border: 1px solid var(--border-color); 
        border-radius: 16px; 
        padding: 1.25rem; 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        background: var(--surface-color); 
        transition: all 0.25s ease;
        box-shadow: var(--shadow-sm);
    }
    .ukm-room-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent-color);
    }
    
    .ukm-mini-logo {
        width: 48px;
        height: 48px;
        background: var(--bg-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        overflow: hidden;
        flex-shrink: 0;
    }

    .timeline-item {
        border-left: 2px solid var(--border-color); 
        padding-left: 1.25rem; 
        position: relative;
        padding-bottom: 1.25rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--accent-color);
        border: 2px solid var(--surface-color);
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    @media (max-width: 1024px) {
        .member-grid-layout {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .member-hero-banner {
            padding: 2rem;
        }
        
        .member-hero-container {
            gap: 2rem;
        }
    }
    
    @media (max-width: 768px) {
        .member-hero-banner {
            padding: 1.5rem;
            border-radius: 20px;
            text-align: center;
        }
        
        .member-hero-container {
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }
        
        .member-avatar-wrapper {
            width: 100px;
            height: 100px;
        }
        
        .member-hero-text {
            min-width: 100%;
        }
        
        .member-hero-title-container {
            flex-direction: column;
            gap: 0.5rem;
            align-items: center;
            justify-content: center;
        }
        
        .member-hero-title {
            font-size: 1.85rem;
        }
        
        .member-hero-stats {
            justify-content: center;
            gap: 0.75rem;
        }
        
        .member-hero-actions {
            width: 100%;
            min-width: 100%;
        }
        
        .ukm-room-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .ukm-room-card .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Hero Banner (Aesthetic Student Dashboard Header) -->
<div class="card member-hero-banner" style="margin-bottom: 1.5rem !important;">
    <div style="position: absolute; right: -50px; top: -50px; width: 250px; height: 250px; border-radius: 50%; background: rgba(255, 255, 255, 0.05); filter: blur(30px); pointer-events: none;"></div>
    <div style="position: absolute; left: 10%; bottom: -80px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255, 255, 255, 0.03); filter: blur(20px); pointer-events: none;"></div>
    
    <div class="member-hero-container">
        <!-- Student Photo/Avatar -->
        <div class="member-avatar-wrapper">
            @if(auth()->user()->photo)
                <img src="{{ filter_var(auth()->user()->photo, FILTER_VALIDATE_URL) ? auth()->user()->photo : asset('storage/' . auth()->user()->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div style="font-size: 2.5rem; font-weight: 800; color: white; text-transform: uppercase; font-family: 'Outfit';">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
            @endif
        </div>
        
        <!-- Student Info -->
        <div class="member-hero-text">
            <div class="member-hero-title-container">
                <h2 class="member-hero-title">{{ auth()->user()->name }}</h2>
                <span style="background: rgba(254, 191, 36, 0.25); color: #fbbf24; border: 1px solid rgba(254, 191, 36, 0.4); padding: 0.4rem 0.8rem; border-radius: 12px; font-weight: 800; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.35rem; text-transform: uppercase; letter-spacing: 0.05em; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-student" style="font-size: 0.95rem;"></i> Mahasiswa Aktif
                </span>
            </div>
            
            <p style="margin: 0 0 1.5rem 0; color: rgba(255, 255, 255, 0.85); line-height: 1.6; font-size: 0.95rem; font-weight: 500; max-width: 700px;">
                {{ auth()->user()->npm ? 'NPM ' . auth()->user()->npm : '' }} 
                {{ auth()->user()->major ? '• ' . auth()->user()->major : '' }}
                {{ auth()->user()->faculty ? '• Fakultas ' . auth()->user()->faculty : '' }}
            </p>
            
            <!-- Quick Info Metrics -->
            <div class="member-hero-stats">
                <div style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); color: white; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-house-line" style="color: rgba(255, 255, 255, 0.7);"></i> {{ $ukmJoinedCount }} UKM Diikuti
                </div>
                <div style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); color: white; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-check-square" style="color: rgba(255, 255, 255, 0.7);"></i> {{ $totalPresence }} Sesi Hadir
                </div>
                <div style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); color: white; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-calendar-blank" style="color: rgba(255, 255, 255, 0.7);"></i> {{ $upcomingEvents->count() }} Agenda Terdekat
                </div>
            </div>
        </div>
        
        <!-- Primary Action Buttons -->
        <div class="member-hero-actions">
            <a href="/member/join" class="btn" style="background: white; color: var(--accent-color); padding: 0.85rem 1.75rem; border-radius: 14px; font-weight: 800; font-size: 0.95rem; border: none; box-shadow: 0 8px 16px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s; text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 20px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)';">
                <i class="ph-bold ph-plus-circle" style="font-size: 1.2rem;"></i> CARI & DAFTAR UKM
            </a>
            <a href="/member/profile" class="btn" style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.25); padding: 0.85rem 1.75rem; border-radius: 14px; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s; backdrop-filter: blur(5px); text-decoration: none;" onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.transform='none';">
                <i class="ph ph-user-gear" style="font-size: 1.2rem;"></i> PENGATURAN PROFIL
            </a>
        </div>
    </div>
</div>

<!-- Aggregated Student Stats Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 0.5rem;">
    <div class="card stat-card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-house-line"></i> UKM Diikuti
        </div>
        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0.5rem;">{{ $ukmJoinedCount }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Unit organisasi aktif</div>
    </div>

    <div class="card stat-card" style="border-top: 3px solid var(--warning-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-hourglass"></i> Pengajuan Pending
        </div>
        <div class="stat-value text-warning" style="font-size: 1.5rem; font-weight: 800; margin-top: 0.5rem;">{{ $ukmPendingCount }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Pendaftaran dalam review</div>
    </div>

    <div class="card stat-card" style="border-top: 3px solid var(--success-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-check-square"></i> Kehadiran Kegiatan
        </div>
        <div class="stat-value text-success" style="font-size: 1.5rem; font-weight: 800; margin-top: 0.5rem;">{{ $totalPresence }} Sesi</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Presensi mandiri disetujui</div>
    </div>

    <div class="card stat-card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-calendar-blank"></i> Agenda Terdekat
        </div>
        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: var(--accent-color); margin-top: 0.5rem;">{{ $upcomingEvents->count() }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Jadwal dalam waktu dekat</div>
    </div>
</div>

<!-- Grid Layout: Rooms & Timeline -->
<div class="member-grid-layout">
    <!-- Left Column: Active Rooms -->
    <div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem; margin-bottom: 0 !important;">
        <h3 class="mb-4" style="font-size: 1.1rem; font-weight: 750; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-door-open" style="color: var(--accent-color); font-size: 1.25rem;"></i> Pusat Kegiatan UKM Saya
        </h3>
        
        <div style="display: grid; gap: 1rem;">
            @foreach($approvedMemberships as $membership)
            <div class="ukm-room-card">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div class="ukm-mini-logo">
                        @if($membership->ukm->logo)
                            <img src="{{ filter_var($membership->ukm->logo, FILTER_VALIDATE_URL) ? $membership->ukm->logo : asset('storage/' . $membership->ukm->logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="ph ph-buildings" style="font-size: 1.5rem; color: var(--accent-color);"></i>
                        @endif
                    </div>
                    <div>
                        <h4 style="margin: 0; font-weight: 750; font-size: 1rem; color: var(--text-primary);">{{ $membership->ukm->name }}</h4>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem; display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                            <span class="badge badge-approved" style="font-size: 0.65rem; padding: 0.15rem 0.4rem; font-weight: 700; text-transform: uppercase;">
                                {{ $membership->classification->name ?? 'Anggota' }}
                            </span>
                            <span>•</span>
                            <span>Bergabung {{ $membership->created_at ? $membership->created_at->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
                <a href="/room/{{ $membership->ukm_id }}/classroom" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.8125rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem; border-radius: 10px;">
                    Masuk Room <i class="ph ph-arrow-right"></i>
                </a>
            </div>
            @endforeach
            
            @if($approvedMemberships->isEmpty())
            <div style="text-align: center; padding: 3rem 1.5rem; background: var(--bg-color); border: 1px dashed var(--border-color); border-radius: 16px;">
                <i class="ph ph-student text-secondary" style="font-size: 2.5rem; opacity: 0.3; margin-bottom: 0.75rem; display: block;"></i>
                <p class="text-secondary" style="font-size: 0.875rem; margin: 0;">Anda belum bergabung secara aktif di UKM manapun.</p>
                <a href="/member/join" class="btn" style="margin-top: 1rem; font-size: 0.8rem; font-weight: 700; background: var(--accent-light); color: var(--accent-color); border: 1px solid var(--border-color); border-radius: 8px;">Cari UKM &rarr;</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Upcoming Schedules -->
    <div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem; margin-bottom: 0 !important;">
        <h3 class="mb-4" style="font-size: 1.1rem; font-weight: 750; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-clock" style="color: var(--accent-color); font-size: 1.25rem;"></i> Agenda & Jadwal Terdekat
        </h3>
        
        <div style="display: grid; gap: 0.5rem; position: relative;">
            @foreach($upcomingEvents as $event)
            <div class="timeline-item">
                <div style="font-weight: 700; font-size: 0.925rem; color: var(--text-primary); line-height: 1.4;">{{ $event->title }}</div>
                <div style="font-size: 0.75rem; color: var(--accent-color); font-weight: 700; margin-top: 0.15rem;">
                    {{ optional($event->ukm)->name }}
                </div>
                <div style="font-size: 0.725rem; color: var(--text-secondary); margin-top: 0.25rem; display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                    <span style="display: inline-flex; align-items: center; gap: 0.15rem;">
                        <i class="ph ph-calendar"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                    </span>
                    @if($event->location)
                        <span>•</span>
                        <span style="display: inline-flex; align-items: center; gap: 0.15rem;">
                            <i class="ph ph-map-pin"></i> {{ $event->location }}
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
            
            @if($upcomingEvents->isEmpty())
            <div style="text-align: center; padding: 3rem 1.5rem; background: var(--bg-color); border: 1px dashed var(--border-color); border-radius: 16px;">
                <i class="ph ph-calendar-blank text-secondary" style="font-size: 2.5rem; opacity: 0.3; margin-bottom: 0.75rem; display: block;"></i>
                <p class="text-secondary" style="font-size: 0.875rem; margin: 0;">Tidak ada agenda kegiatan terdekat.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Bottom Panel: Application Status -->
<div class="card" style="border-top: 3px solid var(--warning-color); padding: 1.5rem; margin-top: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 750; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-hourglass-low" style="color: var(--warning-color); font-size: 1.25rem;"></i> Status Pengajuan Gabung UKM Baru
        </h3>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table" style="font-size: 0.85rem;">
            <thead>
                <tr>
                    <th>Nama UKM</th>
                    <th>Peran Dituju</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status Review</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingMemberships as $membership)
                <tr>
                    <td style="font-weight: 750; color: var(--text-primary);">{{ $membership->ukm->name }}</td>
                    <td>
                        <span class="badge badge-info" style="font-size: 0.7rem; font-weight: 700;">Anggota</span>
                    </td>
                    <td style="color: var(--text-secondary);">
                        {{ $membership->created_at ? $membership->created_at->format('d M Y') : '-' }}
                    </td>
                    <td>
                        <span class="badge badge-warning" style="font-size: 0.7rem; font-weight: 700; background: #fff8e6; color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);">
                            Dalam Review Pengurus
                        </span>
                    </td>
                </tr>
                @endforeach
                
                @if($pendingMemberships->isEmpty())
                <tr>
                    <td colspan="4" class="text-secondary text-center py-6" style="font-style: italic;">
                        Tidak ada pendaftaran UKM yang sedang ditinjau.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection