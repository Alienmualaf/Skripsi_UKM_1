@extends('layouts.app')

@section('title', 'Dashboard Administrator')
@section('header', 'Dashboard Administrator')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<style>
    .admin-stat-card {
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
    .admin-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    .admin-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }
    .grid-4-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
</style>

<!-- Stat Grid -->
<div class="grid-4-stats">
    <!-- Total User -->
    <div class="card admin-stat-card">
        <div class="admin-icon-box" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
            <i class="ph ph-users"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total User</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalUsers }}</div>
        </div>
    </div>

    <!-- Total Anggota -->
    <div class="card admin-stat-card">
        <div class="admin-icon-box" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
            <i class="ph ph-users-three"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Anggota</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalMembers }}</div>
        </div>
    </div>

    <!-- Total Program Kerja -->
    <div class="card admin-stat-card">
        <div class="admin-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="ph ph-presentation-chart"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Program Kerja</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalPrograms }}</div>
        </div>
    </div>

    <!-- Total Penampilan -->
    <div class="card admin-stat-card">
        <div class="admin-icon-box" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
            <i class="ph ph-microphone-stage"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Penampilan</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalPerformances }}</div>
        </div>
    </div>
</div>

<!-- Widgets Section -->
<div class="card" style="padding: 1.75rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.2rem; display: flex; align-items: center; gap: 0.6rem; color: var(--text-primary);">
            <i class="ph ph-clock-counter-clockwise" style="color: var(--accent-color); font-size: 1.4rem;"></i> Aktivitas Terbaru
        </h4>
        <a href="{{ route('admin.logs.activity') }}" class="btn btn-secondary" style="font-size: 0.75rem; font-weight: 700; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;">Selengkapnya &rarr;</a>
    </div>
    
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @forelse($recentActivities as $act)
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 1rem; background: var(--bg-color); border-radius: 10px; border: 1px solid var(--border-color); transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--accent-color)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <div>
                    <div style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">
                        {{ $act->username }} 
                        <span style="font-weight: 600; font-size: 0.75rem; color: var(--text-secondary); background: var(--border-color); padding: 0.15rem 0.5rem; border-radius: 6px; margin-left: 0.5rem;">{{ $act->role }}</span>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">{{ $act->activity }}</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--accent-color);">{{ $act->module }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.25rem;">{{ $act->created_at->diffForHumans() }}</div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted" style="font-size: 0.9rem; padding: 2rem 0;">Belum ada log aktivitas hari ini.</div>
        @endforelse
    </div>
</div>
@endsection