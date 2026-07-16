@extends('layouts.app')
@section('title', 'Pusat Latihan Saya')
@section('header', 'Pusat Latihan Saya')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ukm.css') }}">
<style>
    .classroom-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)) !important;
        gap: 1.25rem !important;
    }
    .classroom-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 0.5rem !important;
        margin-top: 0.85rem !important;
        margin-bottom: 1rem !important;
    }
    .cls-stat-box {
        background: var(--surface-color);
        border-radius: 8px;
        padding: 0.6rem 0.25rem;
        text-align: center;
    }
    .cls-stat-box .stat-num {
        font-size: 1.2rem;
        font-weight: 800;
        display: block;
        line-height: 1.2;
    }
    .cls-stat-box .stat-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--text-muted);
        display: block;
    }

    @media (max-width: 768px) {
        .classroom-grid {
            grid-template-columns: 1fr !important;
        }
        .classroom-card-header {
            padding: 0.9rem 1.1rem !important;
        }
        .classroom-card-body {
            padding: 0.9rem 1.1rem !important;
        }
        .classroom-stats-grid {
            grid-template-columns: repeat(3, 1fr) !important;
        }
        .classroom-card-body p {
            margin-bottom: 0.4rem !important;
            font-size: 0.8rem !important;
        }
    }
</style>

<div style="margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;">
    <div>
        <h3 style="font-size:1.25rem;font-weight:800;color:var(--text-primary);margin:0 0 0.25rem 0;">Daftar Pusat Latihan Saya</h3>
        <p style="margin:0;color:var(--text-secondary);font-size:0.875rem;">Akses materi latihan, jadwal presensi, target lagu, dan pengumuman untuk setiap penampilan dan lomba yang Anda ikuti.</p>
    </div>
</div>

@if($classrooms->count() > 0)
    <div class="classroom-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.25rem;margin-bottom:2rem;">
        @foreach($classrooms as $cls)
        @php
            $perf = $cls->performance;
            $isJob = $perf && is_null($perf->program_id);
            $prog = $perf?->program;
        @endphp
        <div class="card" style="padding:0;overflow:hidden;border:1px solid var(--border-color);transition:box-shadow 0.2s,transform 0.2s;" onmouseenter="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)';this.style.transform='translateY(-2px)';" onmouseleave="this.style.boxShadow='';this.style.transform='';">
            <!-- Header Gradient -->
            <div class="classroom-card-header" style="background:linear-gradient(135deg,#00072D,#1a2556);padding:1.25rem 1.5rem;position:relative;">
                <div style="position:absolute;top:0.75rem;right:0.75rem;">
                    <span style="background:rgba(255,255,255,0.2);color:#fff;font-size:0.7rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:999px;">
                        {{ $cls->status }}
                    </span>
                </div>
                <i class="ph {{ ($prog && $prog->activity_type === 'Competition') ? 'ph-trophy' : 'ph-chalkboard' }}" style="font-size:1.75rem;color:rgba(255,255,255,0.7);display:block;margin-bottom:0.5rem;"></i>
                <h4 style="margin:0 0 0.25rem;font-weight:800;font-size:1rem;color:#fff;">{{ $cls->name }}</h4>
                @if($isJob)
                <p style="margin:0;font-size:0.8125rem;color:rgba(255,255,255,0.8);">
                    <i class="ph ph-briefcase"></i> Job: {{ $perf->title }}
                </p>
                @elseif($perf)
                <p style="margin:0;font-size:0.8125rem;color:rgba(255,255,255,0.8);">
                    <i class="ph {{ ($prog && $prog->activity_type === 'Competition') ? 'ph-trophy' : 'ph-microphone-stage' }}"></i> {{ $perf->title }}
                </p>
                @endif
            </div>

            <!-- Body -->
            <div class="classroom-card-body" style="padding:1.25rem 1.5rem;">
                @if($isJob)
                <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                    <i class="ph ph-tag"></i> Jenis: <strong style="color:var(--text-primary);">Penugasan Job / Delegasi</strong>
                </p>
                <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                    <i class="ph ph-calendar"></i> Tanggal: <strong style="color:var(--text-primary);">{{ $perf->performance_date ? date('d M Y', strtotime($perf->performance_date)) : '-' }}</strong>
                </p>
                <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                    <i class="ph ph-map-pin"></i> Lokasi: <strong style="color:var(--text-primary);">{{ $perf->venue ?? '-' }}</strong>
                </p>
                @else
                    @if($prog)
                    <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                        <i class="ph ph-tag"></i> Jenis: <strong style="color:var(--text-primary);">{{ $prog->activity_type === 'Competition' ? 'Kompetisi / Lomba' : 'Penampilan' }}</strong>
                    </p>
                    <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                        <i class="ph ph-folder"></i> Program: <strong style="color:var(--text-primary);">{{ $prog->name }}</strong>
                    </p>
                    @endif
                    @if($perf)
                    <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                        <i class="ph ph-calendar"></i> Tanggal: <strong style="color:var(--text-primary);">{{ date('d M Y', strtotime($perf->performance_date)) }}</strong>
                    </p>
                    <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                        <i class="ph ph-map-pin"></i> Venue: <strong style="color:var(--text-primary);">{{ $perf->venue }}</strong>
                    </p>
                    @endif
                @endif

                <!-- Stats -->
                <div class="classroom-stats-grid">
                    <div class="cls-stat-box">
                        <span class="stat-num" style="color:var(--accent-color);">{{ $cls->members->count() }}</span>
                        <span class="stat-label">Peserta</span>
                    </div>
                    <div class="cls-stat-box">
                        <span class="stat-num" style="color:#f59e0b;">{{ $cls->attendances->count() }}</span>
                        <span class="stat-label">Sesi</span>
                    </div>
                    <div class="cls-stat-box">
                        <span class="stat-num" style="color:#10b981;">{{ $cls->songTargets->count() }}</span>
                        <span class="stat-label">Lagu</span>
                    </div>
                </div>

                <a href="{{ route('member.classrooms.show', $cls->id) }}"
                   class="btn btn-primary"
                   style="width:100%;text-align:center;padding:0.65rem;font-weight:700;border-radius:8px;text-decoration:none;display:block;">
                    <i class="ph ph-arrow-square-in"></i> Masuk Pusat Latihan
                </a>
            </div>
        </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="card" style="padding:3rem;text-align:center;">
        <i class="ph ph-chalkboard" style="font-size:4rem;color:var(--text-muted);display:block;margin-bottom:1rem;"></i>
        <h4 style="font-weight:800;color:var(--text-primary);margin:0 0 0.5rem;">Belum Ada Pusat Latihan Aktif</h4>
        <p style="color:var(--text-secondary);font-size:0.875rem;margin:0 auto;max-width:420px;">
            Anda tidak terdaftar sebagai peserta pada penampilan atau lomba manapun yang aktif saat ini.
        </p>
    </div>
@endif

@endsection
