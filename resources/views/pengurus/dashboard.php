@extends('layouts.app')

@section('title', 'Pengurus Dashboard')
@section('header', 'Dashboard Pengurus')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pengurus.css') }}">
<style>
    .pengurus-stat-card {
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
    .pengurus-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    .pengurus-icon-box {
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
    <!-- Total Anggota Aktif -->
    <div class="card pengurus-stat-card">
        <div class="pengurus-icon-box" style="background: rgba(30, 64, 175, 0.08); color: #1e40af;">
            <i class="ph ph-users-three"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Anggota Aktif</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalMembers }}</div>
        </div>
    </div>

    <!-- Total Program Kerja Aktif -->
    <div class="card pengurus-stat-card">
        <div class="pengurus-icon-box" style="background: rgba(197, 160, 89, 0.12); color: #c5a059;">
            <i class="ph ph-presentation-chart"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Proker Aktif</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalPrograms }}</div>
        </div>
    </div>

    <!-- Total Penampilan Aktif -->
    <div class="card pengurus-stat-card">
        <div class="pengurus-icon-box" style="background: rgba(30, 64, 175, 0.08); color: #1e40af;">
            <i class="ph ph-microphone-stage"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Penampilan Aktif</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalPerformances }}</div>
        </div>
    </div>

    <!-- Saldo Kas Saat Ini -->
    <div class="card pengurus-stat-card">
        <div class="pengurus-icon-box" style="background: rgba(197, 160, 89, 0.12); color: #c5a059;">
            <i class="ph ph-wallet"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Saldo Kas Saat Ini</h4>
            <div style="font-size: 1.5rem; font-weight: 800; color: #c5a059; margin-top: 0.15rem;">Rp{{ number_format($netBalance, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Agenda Terdekat -->
    <div class="card" style="padding: 1.75rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
        <h3 style="margin: 0 0 1.5rem 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-calendar-blank" style="color: var(--accent-color);"></i> Agenda Terdekat
        </h3>
        <div class="table-wrapper" style="margin: 0; border: none; box-shadow: none; padding: 0;">
            <table class="table" style="font-size: 0.85rem; width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Penampilan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingAgendas as $agenda)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $agenda->title }}</td>
                        <td style="color: var(--text-secondary);">{{ date('d-m-Y', strtotime($agenda->performance_date)) }}</td>
                        <td style="color: var(--text-secondary);">{{ $agenda->performance_time }} WIB</td>
                        <td style="color: var(--text-secondary);">{{ $agenda->venue }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-secondary text-center py-4">Belum ada agenda terdekat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pengumuman Terbaru -->
    <div class="card" style="padding: 1.75rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
        <h3 style="margin: 0 0 1.5rem 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-megaphone" style="color: var(--accent-color);"></i> Pengumuman Terbaru
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($announcements as $announce)
                <div style="padding: 1rem; background: var(--bg-color); border-radius: 10px; border: 1px solid var(--border-color); transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--accent-color)'" onmouseout="this.style.borderColor='var(--border-color)'">
                    <div style="font-weight: 700; font-size: 0.95rem; color: var(--text-primary);">{{ $announce->title }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem; line-height: 1.4;">{{ Str::limit(strip_tags($announce->content), 120) }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.75rem; display: flex; justify-content: space-between;">
                        <span>Oleh: {{ $announce->creator ? $announce->creator->name : 'Pengurus' }}</span>
                        <span>{{ $announce->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted" style="font-size: 0.9rem; padding: 2.5rem 0;">Belum ada pengumuman terbaru.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
