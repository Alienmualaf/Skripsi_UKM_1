@extends('layouts.app')

@section('title', 'Dashboard Anggota')
@section('header', 'Dashboard Anggota')

@section('content')
<link rel="stylesheet" href="{{ asset('css/member.css') }}">
<style>
    .member-stat-card {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        border-radius: 16px;
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 0 !important;
    }
    .member-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    .member-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }
    .grid-3-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
</style>

<!-- Welcome & Voice Classification Card -->
<div class="card mb-6" style="padding: 2rem; background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%); color: white; border-radius: 16px; margin-bottom: 1.5rem;">
    <h2 style="margin: 0; font-size: 1.75rem; font-weight: 800; font-family: 'Georgia', serif; color: #ffffff !important;">Selamat Datang, {{ auth()->user()->name }}!</h2>
    <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 0.95rem; font-weight: 600; color: #ffffff !important;">
        NPM: {{ $member->npm ?? '-' }} | Klasifikasi Suara: 
        <span style="background: rgba(255,255,255,0.25); padding: 0.25rem 0.55rem; border-radius: 6px; font-weight: 800; color: #ffffff !important;">
            {{ $voice }}
        </span>
    </p>
</div>

<!-- Stats Row -->
<div class="grid-3-stats">
    <!-- Klasifikasi Suara -->
    <div class="card member-stat-card">
        <div class="member-icon-box" style="background: rgba(30, 64, 175, 0.08); color: #1e40af;">
            <i class="ph ph-microphone"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Klasifikasi Suara</h4>
            <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $voice }}</div>
        </div>
    </div>

    <!-- Classroom Saya -->
    <div class="card member-stat-card">
        <div class="member-icon-box" style="background: rgba(197, 160, 89, 0.12); color: #c5a059;">
            <i class="ph ph-chalkboard"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Classroom Saya</h4>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ count($classrooms) }} Kelas</div>
        </div>
    </div>

    <!-- Penampilan yang Diikuti -->
    <div class="card member-stat-card">
        <div class="member-icon-box" style="background: rgba(30, 64, 175, 0.08); color: #1e40af;">
            <i class="ph ph-microphone-stage"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Penampilan Diikuti</h4>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ count($activeJobs) }} Acara</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Left column: Classroom & Penampilan -->
    <div class="md:col-span-2" style="display: flex; flex-direction: column;">
        
        <!-- Classroom Saya -->
        <div class="card" style="padding: 1.75rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
            <h3 style="margin: 0 0 1.25rem 0; font-size: 1.1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <i class="ph ph-chalkboard" style="color: var(--accent-color);"></i> Classroom Saya
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem;">
                @forelse($classrooms as $room)
                    <a href="{{ route('member.classrooms.show', $room->id) }}" style="text-decoration: none; padding: 1rem; border: 1px solid var(--border-color); border-radius: 12px; background: var(--bg-color); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--accent-color)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='none';">
                        <div>
                            <span style="font-size: 0.65rem; font-weight: bold; background: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 0.15rem 0.45rem; border-radius: 4px; text-transform: uppercase; margin-bottom: 0.5rem; display: inline-block;">
                                {{ $room->performance && is_null($room->performance->program_id) ? 'Job' : 'Performance' }}
                            </span>
                            <h4 style="margin: 0; font-weight: 700; font-size: 0.95rem; color: var(--text-primary); line-height: 1.3;">{{ $room->name }}</h4>
                            <p style="margin: 0.35rem 0 0 0; font-size: 0.75rem; color: var(--text-secondary); line-height: 1.4; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                {{ $room->description ?? 'Tidak ada deskripsi kelas.' }}
                            </p>
                        </div>
                        <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px dashed var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600;">Masuk Kelas</span>
                            <i class="ph ph-caret-right" style="color: var(--accent-color); font-weight: bold;"></i>
                        </div>
                    </a>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 2rem 0; color: var(--text-secondary); font-size: 0.85rem;">
                        <p>Anda belum bergabung di classroom manapun.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Penampilan yang Diikuti -->
        <div class="card" style="padding: 1.75rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
            <h3 style="margin: 0 0 1.25rem 0; font-size: 1.1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <i class="ph ph-microphone-stage" style="color: var(--accent-color);"></i> Penampilan yang Diikuti
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($activeJobs as $job)
                    <div style="padding: 1rem; border: 1px solid var(--border-color); border-radius: 12px; background: var(--bg-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <h4 style="margin: 0; font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">{{ $job->title }}</h4>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.75rem; color: var(--text-secondary);">
                                <i class="ph ph-map-pin" style="margin-right: 0.25rem;"></i>{{ $job->location }}
                            </p>
                        </div>
                        <div style="font-size: 0.8rem; font-weight: bold; color: var(--accent-color); background: rgba(59, 130, 246, 0.1); padding: 0.35rem 0.65rem; border-radius: 8px;">
                            <i class="ph ph-calendar" style="margin-right: 0.25rem;"></i>{{ date('d-m-Y', strtotime($job->date)) }}
                        </div>
                    </div>
                @empty
                    <p style="font-size: 0.85rem; color: var(--text-secondary); text-align: center; margin: 2rem 0;">Belum ada penugasan penampilan mendatang.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Right column: Rehearsal schedule & Announcements -->
    <div class="md:col-span-1" style="display: flex; flex-direction: column;">
        
        <!-- Jadwal Latihan Terdekat -->
        <div class="card" style="padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <i class="ph ph-calendar-blank" style="color: var(--accent-color);"></i> Latihan Terdekat
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($upcomingAgendas as $agenda)
                    <div style="padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-color);">
                        <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-primary);">{{ $agenda->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            <i class="ph ph-clock" style="margin-right: 0.25rem;"></i>{{ date('d-m-Y', strtotime($agenda->date)) }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.1rem;">
                            Waktu: {{ date('H:i', strtotime($agenda->start_time)) }} WIB
                        </div>
                    </div>
                @empty
                    <p style="font-size: 0.75rem; color: var(--text-muted); text-align: center; margin: 1rem 0;">Belum ada jadwal latihan terdekat.</p>
                @endforelse
            </div>
        </div>

        <!-- Pengumuman Terbaru -->
        <div class="card" style="padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <i class="ph ph-megaphone" style="color: var(--accent-color);"></i> Pengumuman Terbaru
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($announcements as $announce)
                    <div style="padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-color);">
                        <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-primary);">{{ $announce->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem; line-height: 1.3;">{{ Str::limit(strip_tags($announce->content), 80) }}</div>
                        <div style="font-size: 0.65rem; color: var(--text-muted); margin-top: 0.5rem; text-align: right;">{{ $announce->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <p style="font-size: 0.75rem; color: var(--text-muted); text-align: center; margin: 1rem 0;">Belum ada pengumuman terbaru.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
