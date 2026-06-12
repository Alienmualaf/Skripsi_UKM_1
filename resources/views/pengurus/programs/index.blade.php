@extends('layouts.app')

@section('title', 'Program, Penampilan & Job')
@section('header', 'Manajemen Program & Penampilan')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Aktivitas & Program Kerja</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola target tahunan, penampilan konser, penugasan delegasi, dan performa tim PSUP.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('pengurus.programs.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
            <i class="ph ph-plus"></i> Tambah Proker & Job
        </a>
    </div>
</div>

<!-- Beautiful Tab Navigation -->
<div class="card" style="padding: 0.5rem; margin-bottom: 1.5rem; background: var(--surface-color); border: 1px solid var(--border-color); border-radius: 12px;">
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <button onclick="switchTab('proker')" id="tab-btn-proker" class="tab-button active" style="flex: 1; min-width: 150px; padding: 0.75rem 1rem; border: none; background: transparent; border-radius: 8px; font-weight: 700; color: var(--text-secondary); cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
            <i class="ph ph-chart-bar" style="font-size: 1.2rem;"></i>
            Program Kerja ({{ $proker->count() }})
        </button>
        <button onclick="switchTab('penampilan')" id="tab-btn-penampilan" class="tab-button" style="flex: 1; min-width: 150px; padding: 0.75rem 1rem; border: none; background: transparent; border-radius: 8px; font-weight: 700; color: var(--text-secondary); cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
            <i class="ph ph-microphone-stage" style="font-size: 1.2rem;"></i>
            Penampilan & Job ({{ $performances->count() + $jobs->count() }})
        </button>
    </div>
</div>

<!-- Tab Content: Program Kerja -->
<div id="tab-content-proker" class="tab-pane active animate-fade-in">
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-target" style="color: var(--accent-color);"></i> Proker Kepengurusan
        </h4>

        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Program</th>
                        <th>Jenis</th>
                        <th>Kategori / Scope</th>
                        <th>Lokasi</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>LPJ</th>
                        <th style="width: 180px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proker as $program)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">
                            <a href="{{ route('pengurus.programs.show', $program->id) }}" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.35rem;">
                                @if($program->activity_type === 'Competition')
                                    <i class="ph ph-trophy" style="color: #f59e0b;"></i>
                                @elseif($program->activity_type === 'Event')
                                    <i class="ph ph-calendar-star" style="color: #0ea5e9;"></i>
                                @else
                                    <i class="ph ph-folder" style="color: #6366f1;"></i>
                                @endif
                                {{ $program->name }}
                            </a>
                        </td>
                        <td>
                            @php
                                $typeColors = ['Event' => '#0ea5e9', 'Competition' => '#f59e0b', 'Performance' => '#10b981'];
                                $c = $typeColors[$program->activity_type] ?? 'var(--accent-color)';
                            @endphp
                            <span class="badge" style="background: {{ $c }}22; color: {{ $c }}; font-weight: 700; border: 1px solid {{ $c }}33; font-size: 0.75rem;">{{ $program->activity_type }}</span>
                        </td>
                        <td>
                            @if($program->activity_type === 'Event')
                                <span class="badge" style="background: #e2e8f0; color: #475569; font-weight: 600;">{{ $program->event_category ?? 'Internal' }}</span>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.875rem;">—</span>
                            @endif
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.875rem;">
                            {{ $program->venue ?? 'Belum ditentukan' }}
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.8125rem;">
                            {{ date('d/m/y', strtotime($program->start_date)) }} – {{ date('d/m/y', strtotime($program->end_date)) }}
                        </td>
                        <td>
                            @if($program->status === 'Selesai')
                                <span class="badge" style="background: rgba(16,185,129,0.1); color: var(--success-color); font-weight: bold;">Selesai</span>
                            @elseif($program->status === 'Berjalan')
                                <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: bold;">Berjalan</span>
                            @else
                                <span class="badge" style="background: #fff8e6; color: #f59e0b; font-weight: bold;">Persiapan</span>
                            @endif
                        </td>
                        <td>
                            @if($program->report)
                                <span style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; font-weight: 700; color: var(--success-color);">
                                    <i class="ph ph-check-circle"></i> Ada
                                </span>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; flex-wrap: wrap;">
                                <a href="{{ route('pengurus.programs.show', $program->id) }}" class="btn" style="background: var(--accent-light); color: var(--accent-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; text-decoration: none;">
                                    <i class="ph ph-eye"></i> Detail
                                </a>
                                <a href="{{ route('pengurus.programs.edit', $program->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px;">
                                    <i class="ph ph-pencil-simple"></i>
                                </a>
                                <form action="{{ route('pengurus.programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Hapus program kerja ini?');" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px;">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-8">Belum ada program kerja terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tab Content: Penampilan & Job -->
<div id="tab-content-penampilan" class="tab-pane animate-fade-in" style="display: none;">
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-microphone-stage" style="color: #10b981;"></i> Penampilan & Job Vokal
        </h4>

        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Penampilan / Job</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>LPJ</th>
                        <th>Classroom</th>
                        <th style="width: 200px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 1. Program-linked Performances --}}
                    @foreach($performances as $program)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">
                            <a href="{{ route('pengurus.programs.show', $program->id) }}" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.35rem;">
                                <i class="ph ph-microphone-stage" style="color: #10b981;"></i>
                                {{ $program->name }}
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 0.7rem; margin-left: 0.5rem; font-weight: 700;">Proker</span>
                            </a>
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.8125rem;">
                            {{ date('d-m-Y', strtotime($program->start_date)) }}
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.875rem;">
                            {{ $program->performance->venue ?? 'Belum ditentukan' }}
                        </td>
                        <td>
                            @if($program->status === 'Selesai')
                                <span class="badge" style="background: rgba(16,185,129,0.1); color: var(--success-color); font-weight: bold;">Selesai</span>
                            @elseif($program->status === 'Berjalan')
                                <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: bold;">Berjalan</span>
                            @else
                                <span class="badge" style="background: #fff8e6; color: #f59e0b; font-weight: bold;">Persiapan</span>
                            @endif
                        </td>
                        <td>
                            @if($program->report)
                                <span style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; font-weight: 700; color: var(--success-color);">
                                    <i class="ph ph-check-circle"></i> Selesai
                                </span>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted);">Belum Dibuat</span>
                            @endif
                        </td>
                        <td>
                            @if($program->performance && $program->performance->classroom)
                                <a href="{{ route('pengurus.programs.performance.classroom.show', [$program->id, $program->performance->id]) }}" class="badge badge-approved" style="text-decoration: none; font-weight: bold;">
                                    <i class="ph ph-chalkboard"></i> Buka Classroom
                                </a>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; flex-wrap: wrap;">
                                <a href="{{ route('pengurus.programs.show', $program->id) }}" class="btn" style="background: var(--accent-light); color: var(--accent-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; text-decoration: none;">
                                    <i class="ph ph-eye"></i> Detail
                                </a>
                                <a href="{{ route('pengurus.programs.edit', $program->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px;">
                                    <i class="ph ph-pencil-simple"></i> Edit
                                </a>
                                <form action="{{ route('pengurus.programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Hapus penampilan ini?');" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px;">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- 2. External Jobs --}}
                    @foreach($jobs as $job)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">
                            <div style="display: flex; align-items: center; gap: 0.35rem;">
                                <i class="ph ph-music-notes-simple" style="color: var(--accent-color);"></i>
                                {{ $job->title }}
                                <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: var(--accent-color); font-size: 0.7rem; margin-left: 0.5rem; font-weight: 700;">Job Eksternal</span>
                            </div>
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.8125rem;">
                            {{ $job->performance_date ? $job->performance_date->format('d-m-Y') : ($job->date ? date('d-m-Y', strtotime($job->date)) : '-') }}
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.875rem;">
                            {{ $job->venue ?? $job->location ?? 'Belum ditentukan' }}
                        </td>
                        <td>
                            @php
                                $jobDate = $job->performance_date ? $job->performance_date->toDateString() : ($job->date ? date('Y-m-d', strtotime($job->date)) : null);
                                $todayDate = now()->toDateString();
                            @endphp
                            @if($jobDate)
                                @if($todayDate > $jobDate)
                                    <span class="badge" style="background: rgba(16,185,129,0.1); color: var(--success-color); font-weight: bold;">Selesai</span>
                                @elseif($todayDate == $jobDate)
                                    <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: bold;">Berjalan</span>
                                @else
                                    <span class="badge" style="background: #fff8e6; color: #f59e0b; font-weight: bold;">Persiapan</span>
                                @endif
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">—</span>
                        </td>
                        <td>
                            @if($job->classroom)
                                <a href="{{ route('pengurus.jobs.classroom.show', $job->id) }}" class="badge badge-approved" style="text-decoration: none; font-weight: bold;">
                                    <i class="ph ph-chalkboard"></i> Buka Classroom
                                </a>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <a href="{{ route('pengurus.jobs.edit', $job->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('pengurus.jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan ini?');" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px;"><i class="ph ph-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if($performances->count() == 0 && $jobs->count() == 0)
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-8">Belum ada agenda penampilan konser atau job terdaftar.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.tab-button {
    background: transparent;
    border: none;
    outline: none;
    color: var(--text-secondary);
    transition: all 0.25s ease;
}
.tab-button:hover {
    color: var(--text-primary);
    background: rgba(0, 0, 0, 0.03);
}
.tab-button.active {
    color: #fff !important;
    background: var(--accent-color) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}
[data-theme="dark"] .tab-button:hover {
    background: rgba(255, 255, 255, 0.05);
}
[data-theme="dark"] .tab-button.active {
    background: var(--accent-color) !important;
}
</style>

<script>
function switchTab(tabId) {
    // Hide all tab content panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.style.display = 'none';
        pane.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show current tab content pane
    const targetPane = document.getElementById('tab-content-' + tabId);
    if (targetPane) {
        targetPane.style.display = 'block';
        targetPane.classList.add('active');
    }
    
    // Set active class to current button
    const targetBtn = document.getElementById('tab-btn-' + tabId);
    if (targetBtn) {
        targetBtn.classList.add('active');
    }

    // Update URL parameter without reloading page
    const url = new URL(window.location);
    url.searchParams.set('tab', tabId);
    window.history.pushState({}, '', url);
}

// Handle initial tab on page load based on query params
window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const activityType = urlParams.get('activity_type');
    const tabParam = urlParams.get('tab');
    
    if (activityType === 'Performance' || tabParam === 'penampilan' || tabParam === 'job') {
        switchTab('penampilan');
    } else {
        switchTab('proker');
    }
});
</script>
@endsection
