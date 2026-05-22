@extends('layouts.app')

@section('title', 'Admin UKM Dashboard')
@section('header', 'Pusat Manajemen UKM: ' . $ukm->name)

@section('content')
@php
    $waPhone = null;
    if ($ukm->phone) {
        $waPhone = preg_replace('/[^0-9]/', '', $ukm->phone);
        if (str_starts_with($waPhone, '0')) {
            $waPhone = '62' . substr($waPhone, 1);
        }
    }
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
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.25rem;
    }
    
    .room-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 1rem;
        }
        
        .room-gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
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
                <span style="background: rgba(254, 191, 36, 0.25); color: #fbbf24; border: 1px solid rgba(254, 191, 36, 0.4); padding: 0.4rem 0.8rem; border-radius: 12px; font-weight: 800; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.35rem; text-transform: uppercase; letter-spacing: 0.05em; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-crown" style="font-size: 0.95rem;"></i> Administrator UKM
                </span>
            </div>
            
            <p style="margin: 0 0 1.5rem 0; color: rgba(255, 255, 255, 0.85); line-height: 1.6; font-size: 1rem; font-weight: 400; max-width: 700px;">{{ $ukm->description }}</p>
            
            <!-- Quick Info Metrics -->
            <div class="room-hero-stats">
                <div style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); color: white; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-users-three" style="color: rgba(255, 255, 255, 0.7);"></i> {{ $totalMembers }} Anggota Aktif
                </div>
                <div style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); color: white; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-briefcase" style="color: rgba(255, 255, 255, 0.7);"></i> {{ $ukm->coaches()->where('category', 'pelatih')->count() }} Pelatih
                </div>
                <div style="padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); color: white; border-radius: 12px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; backdrop-filter: blur(5px);">
                    <i class="ph-fill ph-calendar-blank" style="color: rgba(255, 255, 255, 0.7);"></i> {{ $totalEvents }} Agenda
                </div>
            </div>
        </div>
        
        <!-- Primary Action Buttons -->
        <div class="room-hero-actions">
            <a href="/ukm/classroom" class="btn" style="background: white; color: var(--accent-color); padding: 0.85rem 1.75rem; border-radius: 14px; font-weight: 800; font-size: 0.95rem; border: none; box-shadow: 0 8px 16px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s; text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 20px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)';">
                <i class="ph-bold ph-presentation" style="font-size: 1.2rem;"></i> PUSAT KEGIATAN
            </a>
            <a href="/ukm/profile" class="btn" style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.25); padding: 0.85rem 1.75rem; border-radius: 14px; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s; backdrop-filter: blur(5px); text-decoration: none;" onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.transform='none';">
                <i class="ph ph-gear" style="font-size: 1.2rem;"></i> PENGATURAN PROFIL
            </a>
        </div>
    </div>
</div>

<!-- Statistical Summary Row -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="card stat-card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-users"></i> Anggota Aktif
        </div>
        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0.5rem;">{{ $totalMembers }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Mahasiswa aktif</div>
    </div>

    <div class="card stat-card" style="border-top: 3px solid var(--warning-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-user-plus"></i> Pendaftar Baru
        </div>
        <div class="stat-value text-warning" style="font-size: 1.5rem; font-weight: 800; margin-top: 0.5rem;">{{ $pendingMembers }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Menunggu persetujuan</div>
    </div>

    <div class="card stat-card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-calendar"></i> Total Kegiatan
        </div>
        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: var(--accent-color); margin-top: 0.5rem;">{{ $totalEvents }}</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Agenda organisasi</div>
    </div>

    <div class="card stat-card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-package"></i> Barang Aset
        </div>
        <div class="stat-value" style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0.5rem;">{{ $totalInventories }} Pcs</div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Unit inventaris</div>
    </div>

    <div class="card stat-card" style="border-top: 3px solid var(--{{ $netBalance >= 0 ? 'success' : 'danger' }}-color); padding: 1.25rem; margin-bottom: 0 !important;">
        <div class="stat-label" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-wallet"></i> Saldo Kas Bersih
        </div>
        <div class="stat-value" style="font-size: 1.2rem; font-weight: 800; color: var(--{{ $netBalance >= 0 ? 'success' : 'danger' }}-color); margin-top: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Rp {{ number_format($netBalance, 0, ',', '.') }}">
            Rp {{ number_format($netBalance, 0, ',', '.') }}
        </div>
        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Sisa anggaran kas</div>
    </div>
</div>

<!-- Merged Grid Layout -->
<div class="room-grid-layout" style="margin-top: 0;">
    <!-- Left Column (Main Charts & Content) -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">
        
        <!-- Analytical Charts Panel -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            <!-- Chart 1: Financial Split -->
            <div class="card" style="border-top: 3px solid var(--success-color); padding: 1.5rem; margin-bottom: 0 !important;">
                <h4 style="margin-bottom: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; font-size: 1rem;">
                    <i class="ph ph-chart-pie" style="color: var(--success-color);"></i> Mutasi & Perputaran Anggaran
                </h4>
                <div style="height: 220px; position: relative;">
                    <canvas id="financeRatioChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Komposisi berdasarkan Klasifikasi -->
            <div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem; margin-bottom: 0 !important;">
                <h4 style="margin-bottom: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; font-size: 1rem;">
                    <i class="ph ph-users-three" style="color: var(--accent-color);"></i> Komposisi Klasifikasi Anggota
                </h4>
                @if(count($classificationLabels) <= 1 && ($classificationCounts[0] ?? 0) == 0)
                    <div style="height: 220px; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 0.75rem;">
                        <i class="ph ph-chart-pie-slice" style="font-size: 2.5rem; color: var(--border-color);"></i>
                        <p style="color: var(--text-secondary); font-size: 0.875rem; text-align: center; margin: 0;">Belum ada data klasifikasi.<br>Atur klasifikasi di <a href="/ukm/profile" style="color: var(--accent-color); font-weight: 600;">Profil UKM</a>.</p>
                    </div>
                @else
                    <div style="height: 220px; position: relative;">
                        <canvas id="memberStructureChart"></canvas>
                    </div>
                @endif
            </div>
        </div>

        <!-- Agenda Kegiatan Terbaru Table -->
        <div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem; margin-bottom: 0 !important;">
            <div class="flex justify-between items-center mb-4">
                <h3 style="margin: 0; font-size: 1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph ph-calendar-blank" style="color: var(--accent-color);"></i> Agenda & Sesi Kegiatan Terbaru
                </h3>
                <a href="/ukm/events" class="btn" style="background: var(--accent-light); color: var(--accent-color); font-size: 0.75rem; padding: 0.4rem 0.8rem; font-weight: 600; border-radius: 0.375rem;">
                    Kelola Kegiatan
                </a>
            </div>
            <div class="table-wrapper">
                <table class="table" style="font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal Mulai</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td style="font-weight: 700; color: var(--text-primary);">
                                <a href="/ukm/classroom/{{ $event->id }}" style="color: var(--accent-color); text-decoration: none; font-family: 'Outfit', sans-serif;">
                                    {{ $event->title }}
                                </a>
                            </td>
                            <td style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}</td>
                            <td style="color: var(--text-secondary);">{{ $event->location ?? '-' }}</td>
                            <td>
                                @php
                                    $startDate = \Carbon\Carbon::parse($event->start_date);
                                    $endDate = \Carbon\Carbon::parse($event->end_date);
                                    $now = now();
                                    
                                    if ($now->lt($startDate)) {
                                        $calculatedStatus = 'upcoming';
                                    } elseif ($now->gt($endDate)) {
                                        $calculatedStatus = 'completed';
                                    } else {
                                        $calculatedStatus = 'ongoing';
                                    }
                                @endphp
                                @if($calculatedStatus == 'upcoming')
                                    <span class="badge badge-pending">Upcoming</span>
                                @elseif($calculatedStatus == 'ongoing')
                                    <span class="badge badge-warning">Ongoing</span>
                                @else
                                    <span class="badge badge-approved">Completed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if(count($events) == 0)
                        <tr><td colspan="4" class="text-secondary text-center py-4">Belum ada agenda terdaftar.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Visi & Misi Card -->
        @if($ukm->vision || $ukm->mission)
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--surface-color); padding: 2rem; margin-bottom: 0 !important;">
            <h3 style="font-weight: 800; font-size: 1.5rem; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); letter-spacing: -0.02em;">
                <i class="ph-fill ph-flag-banner" style="color: var(--accent-color); font-size: 1.75rem;"></i> Visi & Misi UKM
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 1.75rem;">
                @if($ukm->vision)
                <div style="background: var(--bg-color); border-left: 4px solid var(--accent-color); padding: 1.25rem 1.5rem; border-radius: 4px 16px 16px 4px; box-shadow: var(--shadow-sm);">
                    <h4 style="font-weight: 700; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Visi</h4>
                    <p style="font-size: 1.15rem; font-weight: 600; line-height: 1.6; font-style: italic; color: var(--text-primary);">"{{ $ukm->vision }}"</p>
                </div>
                @endif
                
                @if($ukm->mission)
                <div style="background: var(--bg-color); border-left: 4px solid var(--accent-color); padding: 1.25rem 1.5rem; border-radius: 4px 16px 16px 4px; box-shadow: var(--shadow-sm);">
                    <h4 style="font-weight: 700; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Misi</h4>
                    <div style="white-space: pre-line; line-height: 1.7; font-size: 1.05rem; color: var(--text-primary); font-weight: 600;">{{ $ukm->mission }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Dokumentasi Kegiatan Card -->
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--surface-color); padding: 2rem; margin-bottom: 0 !important;">
            <h3 style="font-weight: 800; font-size: 1.5rem; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); letter-spacing: -0.02em;">
                <i class="ph-fill ph-image" style="color: var(--warning-color); font-size: 1.75rem;"></i> Dokumentasi Kegiatan
            </h3>
            
            <div class="room-gallery-grid">
                @php
                    $galleries = App\Models\Gallery::where('ukm_id', $ukm->id)->latest()->limit(4)->get();
                @endphp
                @forelse($galleries as $gal)
                <div style="border-radius: 16px; overflow: hidden; position: relative; aspect-ratio: 4/3; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='var(--shadow-md)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)';">
                    @if($gal->type === 'photo')
                        <img src="{{ Storage::url($gal->file_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <video src="{{ Storage::url($gal->file_path) }}" style="width: 100%; height: 100%; object-fit: cover;"></video>
                        <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.3); color: white;">
                            <i class="ph-fill ph-play-circle" style="font-size: 2.5rem;"></i>
                        </div>
                    @endif
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 0.75rem; background: linear-gradient(transparent, rgba(0,0,0,0.85)); color: white; font-size: 0.75rem; font-weight: 600; line-height: 1.4;">
                        <span style="display: block; opacity: 0.8; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--warning-color); margin-bottom: 0.15rem;">{{ $gal->type }}</span>
                        {{ Str::limit($gal->title, 30) }}
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

    <!-- Right Column (Sidebar Shortcuts & Info) -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">
        
        <!-- Pintasan Pengelola -->
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 2px solid rgba(254, 191, 36, 0.4); background: var(--surface-color); padding: 1.5rem; margin-bottom: 0 !important;">
            <h4 style="font-weight: 800; font-size: 1.15rem; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary); letter-spacing: -0.02em;">
                <i class="ph-fill ph-gear-six" style="color: #eab308; font-size: 1.4rem;"></i> Pintasan Pengelola
            </h4>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <a href="/ukm/members" class="btn btn-secondary" style="justify-content: flex-start; text-align: left; padding: 0.75rem 1rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; width: 100%; transition: all 0.2s;" onmouseover="this.style.background='var(--accent-light)'; this.style.color='var(--accent-color)';" onmouseout="this.style.background='var(--surface-color)'; this.style.color='var(--text-primary)';">
                    <i class="ph-bold ph-users" style="font-size: 1.1rem;"></i> Kelola Anggota
                    @if($pendingMembers > 0)
                        <span style="margin-left: auto; background: var(--danger-color); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.65rem; font-weight: 800;">{{ $pendingMembers }}</span>
                    @endif
                </a>
                <a href="/calendar" class="btn btn-secondary" style="justify-content: flex-start; text-align: left; padding: 0.75rem 1rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; width: 100%; transition: all 0.2s;" onmouseover="this.style.background='var(--accent-light)'; this.style.color='var(--accent-color)';" onmouseout="this.style.background='var(--surface-color)'; this.style.color='var(--text-primary)';">
                    <i class="ph-bold ph-calendar" style="font-size: 1.1rem;"></i> Agenda & Presensi
                </a>
                <a href="/ukm/finance" class="btn btn-secondary" style="justify-content: flex-start; text-align: left; padding: 0.75rem 1rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; width: 100%; transition: all 0.2s;" onmouseover="this.style.background='var(--accent-light)'; this.style.color='var(--accent-color)';" onmouseout="this.style.background='var(--surface-color)'; this.style.color='var(--text-primary)';">
                    <i class="ph-bold ph-money" style="font-size: 1.1rem;"></i> Keuangan & Kas
                </a>
                <a href="/ukm/classroom" class="btn btn-secondary" style="justify-content: flex-start; text-align: left; padding: 0.75rem 1rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; width: 100%; transition: all 0.2s;" onmouseover="this.style.background='var(--accent-light)'; this.style.color='var(--accent-color)';" onmouseout="this.style.background='var(--surface-color)'; this.style.color='var(--text-primary)';">
                    <i class="ph-bold ph-books" style="font-size: 1.1rem;"></i> Classroom & Materi
                </a>
                <a href="/ukm/reports" class="btn btn-secondary" style="justify-content: flex-start; text-align: left; padding: 0.75rem 1rem; border-radius: 12px; font-weight: 600; font-size: 0.875rem; width: 100%; transition: all 0.2s;" onmouseover="this.style.background='var(--accent-light)'; this.style.color='var(--accent-color)';" onmouseout="this.style.background='var(--surface-color)'; this.style.color='var(--text-primary)';">
                    <i class="ph-bold ph-file-pdf" style="font-size: 1.1rem;"></i> Laporan & LPJ
                </a>
            </div>
        </div>

        <!-- Pelatih & Pengajar Card -->
        <div class="card" style="border-radius: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); background: var(--surface-color); padding: 1.5rem; margin-bottom: 0 !important;">
            <h4 style="font-weight: 800; font-size: 1.15rem; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary); letter-spacing: -0.02em;">
                <i class="ph-fill ph-briefcase" style="color: var(--success-color); font-size: 1.4rem;"></i> Pelatih Resmi UKM
            </h4>
            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @forelse($ukm->coaches->where('category', 'pelatih')->take(3) as $coach)
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    @if($coach->photo)
                        <img src="{{ asset('storage/' . $coach->photo) }}" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--accent-light); display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; color: var(--accent-color);">{{ substr($coach->name, 0, 1) }}</div>
                    @endif
                    <div>
                        <h5 style="margin: 0; font-weight: 700; font-size: 0.875rem; color: var(--text-primary);">{{ $coach->name }}</h5>
                        <p style="margin: 0; font-size: 0.75rem; color: var(--text-secondary);">{{ Str::limit($coach->skills, 25) }}</p>
                    </div>
                </div>
                @empty
                <p style="font-size: 0.8rem; color: var(--text-secondary); margin: 0; text-align: center;">Belum ada pelatih terdaftar.</p>
                @endforelse
            </div>
        </div>

        <!-- Help / Contact Card -->
        <div class="card" style="background: linear-gradient(135deg, var(--text-primary) 0%, #1e293b 100%); color: white; border: none; border-radius: 20px; padding: 1.5rem; position: relative; overflow: hidden; margin-bottom: 0 !important;">
            <div style="position: absolute; right: -20px; bottom: -20px; font-size: 5rem; opacity: 0.05; pointer-events: none;"><i class="ph ph-envelope"></i></div>
            <h4 style="font-weight: 800; font-size: 1.15rem; margin: 0 0 0.5rem 0; color: white;">Pusat Bantuan UKM</h4>
            <p style="font-size: 0.8rem; opacity: 0.85; margin: 0 0 1.25rem 0; line-height: 1.5; font-weight: 400;">Jika Anda mengalami kendala operasional atau teknis aplikasi, silakan hubungi tim administrator pusat.</p>
            @if($waPhone)
                <a href="https://wa.me/{{ $waPhone }}?text=Halo%20Admin%20Pusat%2C%20saya%20pengelola%20UKM%20{{ urlencode($ukm->name) }}%20ingin%20bertanya%20mengenai..." target="_blank" class="btn" style="background: white; color: var(--text-primary); border: none; padding: 0.65rem 1.25rem; border-radius: 10px; font-weight: 700; font-size: 0.8rem; width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.35rem; transition: all 0.2s; text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='none';">
                    <i class="ph ph-whatsapp-logo" style="font-size: 1.1rem; color: #25D366;"></i> Hubungi Bantuan Pusat
                </a>
            @else
                <a href="mailto:admin@ukm.com" class="btn" style="background: white; color: var(--text-primary); border: none; padding: 0.65rem 1.25rem; border-radius: 10px; font-weight: 700; font-size: 0.8rem; width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.35rem; transition: all 0.2s; text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='none';">
                    <i class="ph ph-paper-plane-tilt" style="font-size: 0.95rem;"></i> Kirim Email Bantuan
                </a>
            @endif
        </div>

    </div>
</div>

<!-- Load Chart.js -->
<script src="{{ asset('js/chart.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const style = getComputedStyle(document.documentElement);
        const accentColor = style.getPropertyValue('--accent-color').trim() || '#3b82f6';
        const successColor = style.getPropertyValue('--success-color').trim() || '#10b981';
        const dangerColor = style.getPropertyValue('--danger-color').trim() || '#ef4444';
        
        // 1. Finance Ratio
        const financeCtx = document.getElementById('financeRatioChart').getContext('2d');
        new Chart(financeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pemasukan Kas', 'Pengeluaran Kas'],
                datasets: [{
                    data: [{{ $totalIncome }}, {{ $totalExpense }}],
                    backgroundColor: [successColor + 'd0', dangerColor + 'd0'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 12, color: '#64748b', font: { size: 11 } }
                    }
                }
            }
        });

        // 2. Komposisi Klasifikasi
        const classLabels = {!! json_encode($classificationLabels) !!};
        const classData = {!! json_encode($classificationCounts) !!};
        const hasData = classData.some(v => v > 0);

        if (hasData && document.getElementById('memberStructureChart')) {
            const classColors = [
                accentColor + 'd0',
                successColor + 'd0',
                '#f59e0bd0',
                '#8b5cf6d0',
                '#ec4899d0',
                '#06b6d4d0',
                '#14b8a6d0',
                '#f97316d0',
                '#6366f1d0',
                '#a855f7d0',
                '#94a3b8d0'
            ];

            const memberCtx = document.getElementById('memberStructureChart').getContext('2d');
            new Chart(memberCtx, {
                type: 'pie',
                data: {
                    labels: classLabels,
                    datasets: [{
                        data: classData,
                        backgroundColor: classLabels.map((_, i) => classColors[i % classColors.length]),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { boxWidth: 12, color: '#64748b', font: { size: 11 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + context.raw + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection