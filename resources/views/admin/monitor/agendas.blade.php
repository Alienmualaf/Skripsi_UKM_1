@extends('layouts.app')

@section('title', 'Monitor Penampilan & Proker')
@section('header', 'Monitoring Penampilan & Proker')

@section('content')
<style>
    /* Responsive Table Override for Mobile View */
    @media (max-width: 768px) {
        /* Reduce card padding on mobile for extra space */
        .card {
            padding: 1rem !important;
        }

        /* Force table elements to display as block */
        .responsive-table, 
        .responsive-table thead, 
        .responsive-table tbody, 
        .responsive-table th, 
        .responsive-table td, 
        .responsive-table tr { 
            display: block !important; 
            width: 100% !important;
            min-width: 0 !important;
            box-sizing: border-box;
        }
        
        /* Hide traditional table headers */
        .responsive-table thead { 
            display: none !important;
        }
        
        /* Format rows as cards */
        .responsive-table tr { 
            margin-bottom: 1.25rem;
            border: 1px solid var(--border-color) !important;
            border-radius: var(--radius-md) !important;
            padding: 0.75rem 1rem !important;
            background: var(--surface-color);
            box-shadow: var(--shadow-sm);
        }
        
        .responsive-table tr:hover td {
            background-color: transparent !important;
            color: inherit !important;
        }
        
        /* Style individual data cells */
        .responsive-table td { 
            text-align: right !important;
            padding: 0.6rem 0 !important;
            border-bottom: 1px solid var(--border-color) !important;
            position: relative;
            font-size: 0.85rem !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 1rem;
            min-height: 2.5rem;
            white-space: normal !important;
        }
        
        /* Remove bottom border from last cell in row card */
        .responsive-table td:last-child {
            border-bottom: none !important;
            padding-bottom: 0.25rem !important;
            margin-top: 0.5rem;
        }
        
        /* Display data-label as inline label on mobile */
        .responsive-table td::before { 
            content: attr(data-label);
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-align: left;
            flex-shrink: 0;
            max-width: 40%;
        }

        /* Value styling inside td for proper wrap */
        .responsive-table td > span,
        .responsive-table td > div {
            text-align: right !important;
            word-break: break-word;
            max-width: 60%;
            white-space: normal !important;
            display: inline-block;
        }

        /* Center action buttons container */
        .responsive-table td .btn-container {
            width: 100%;
            max-width: 100% !important;
            justify-content: flex-end !important;
            display: flex;
            gap: 0.35rem;
        }
    }
</style>

<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Override Data: Penampilan & Program Kerja</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Pengawasan seluruh penampilan (performance), program kerja tahunan pengurus, dan jadwal acara UKM PSUP.</p>
</div>

<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- 1. DATA PENAMPILAN -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-microphone-stage" style="color: var(--accent-color);"></i> Database Penampilan (Performance)
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none; overflow: visible;">
            <table class="table responsive-table">
                <thead>
                    <tr>
                        <th>Judul Penampilan</th>
                        <th>Program Kerja</th>
                        <th>Tanggal Penampilan</th>
                        <th>Tempat / Venue</th>
                        <th>Status</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendas as $a)
                    <tr>
                        <td data-label="Judul Penampilan" style="font-weight: 700; color: var(--text-primary);">
                            <span>{{ $a->title }}</span>
                        </td>
                        <td data-label="Program Kerja">
                            <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: 700;">{{ $a->program ? $a->program->name : 'N/A' }}</span>
                        </td>
                        <td data-label="Tanggal Penampilan">
                            <span>{{ $a->performance_date ? date('d M Y', strtotime($a->performance_date)) : '-' }}</span>
                        </td>
                        <td data-label="Tempat / Venue">
                            <span>{{ $a->venue ?? '-' }}</span>
                        </td>
                        <td data-label="Status">
                            @if($a->performance_date && strtotime($a->performance_date) < time())
                                <span style="color: var(--text-muted); font-size: 0.8125rem;"><span style="width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; display: inline-block;"></span> Selesai</span>
                            @else
                                <span style="color: var(--success-color); font-size: 0.8125rem; font-weight: bold;"><span style="width: 6px; height: 6px; border-radius: 50%; background: var(--success-color); display: inline-block;"></span> Mendatang</span>
                            @endif
                        </td>
                        <td data-label="Override Aksi">
                            <div class="btn-container">
                                <a href="{{ route('pengurus.programs.show', $a->program_id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-eye"></i> Detail</a>
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'performance', 'id' => $a->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa penampilan ini? Tindakan ini tidak bisa dibatalkan.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Tidak ada data penampilan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $agendas->appends(['agendas_page' => $agendas->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

    <!-- 2. DATA PROGRAM KERJA -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-presentation-chart" style="color: var(--accent-color);"></i> Database Program Kerja Pengurus
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none; overflow: visible;">
            <table class="table responsive-table">
                <thead>
                    <tr>
                        <th>Nama Program</th>
                        <th>Target Selesai</th>
                        <th>PJ (Penanggung Jawab)</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($programs as $p)
                    <tr>
                        <td data-label="Nama Program" style="font-weight: 700; color: var(--text-primary);">
                            <span>{{ $p->name }}</span>
                        </td>
                        <td data-label="Target Selesai">
                            <span>{{ $p->target_date ? date('d M Y', strtotime($p->target_date)) : '-' }}</span>
                        </td>
                        <td data-label="PJ (Penanggung Jawab)" style="font-weight: 600;">
                            <span>{{ $p->pic ?? '-' }}</span>
                        </td>
                        <td data-label="Override Aksi">
                            <div class="btn-container">
                                <a href="{{ route('pengurus.programs.edit', $p->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'program', 'id' => $p->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa program kerja ini? Tindakan ini tidak bisa dibatalkan.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">Tidak ada data program kerja.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $programs->appends(['programs_page' => $programs->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

</div>
@endsection
