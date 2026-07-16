@extends('layouts.app')

@section('title', 'Laporan Rekrutmen')
@section('header', 'Laporan Rekrutmen')

@section('content')

{{-- Header + Print --}}
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <a href="{{ route('ukm.reports') }}" style="display:inline-flex;align-items:center;gap:0.35rem;color:var(--accent-color);text-decoration:none;font-weight:600;font-size:0.875rem;margin-bottom:0.35rem;">
            <i class="ph ph-arrow-left"></i> Kembali ke Pusat Laporan
        </a>
        <h3 style="margin:0;font-weight:800;font-size:1.25rem;">Laporan Rekrutmen Anggota</h3>
        <p style="margin:0;font-size:0.8rem;color:var(--text-secondary);">{{ $members->count() }} anggota ditemukan</p>
    </div>
    <a href="{{ route('ukm.reports.rekrutmen.print') }}?{{ http_build_query(request()->all()) }}" target="_blank"
       class="btn" style="padding:0.6rem 1.1rem;font-weight:700;border-radius:8px;display:inline-flex;align-items:center;gap:0.35rem;background:#8b5cf6;color:white;text-decoration:none;">
        <i class="ph ph-download-simple"></i> Download Laporan
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('ukm.reports.rekrutmen') }}" style="margin-bottom:1.5rem;">
    <div class="card" style="padding:1.25rem;">
        <h5 style="font-weight:800;font-size:0.9rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="ph ph-funnel" style="color:#8b5cf6;"></i> Filter
        </h5>
        @if ($errors->has('end_date'))
            <div style="background:#fef2f2; border:1px solid #fee2e2; color:#ef4444; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:0.85rem; font-weight:600;">
                <i class="ph ph-warning-circle" style="vertical-align:middle; margin-right:4px;"></i> {{ $errors->first('end_date') }}
            </div>
        @endif
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;align-items:end;">
            <div>
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tahun Bergabung</label>
                <select name="tahun" class="form-control" style="font-size:0.875rem;">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}" style="font-size:0.875rem;">
            </div>
            <div>
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}" style="font-size:0.875rem;">
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.6rem;font-weight:700;border-radius:8px;">
                    <i class="ph ph-magnifying-glass"></i> Terapkan
                </button>
            </div>
            <div>
                <a href="{{ route('ukm.reports.rekrutmen') }}" class="btn" style="width:100%;padding:0.6rem;font-weight:700;border-radius:8px;background:var(--bg-color);border:1px solid var(--border-color);text-decoration:none;display:block;text-align:center;">
                    Reset
                </a>
            </div>
        </div>
    </div>
</form>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;margin-bottom:1.5rem;">
    @php
        $byVoice = $members->groupBy(fn($m) => $m->voiceClassification->name ?? 'Belum Diklasifikasi');
        $voiceColors = ['Sopran'=>'#ec4899','Alto'=>'#f59e0b','Tenor'=>'#3b82f6','Bass'=>'#6366f1'];
    @endphp
    <div class="card" style="padding:1rem;text-align:center;">
        <div style="font-size:1.75rem;font-weight:800;color:var(--accent-color);">{{ $members->count() }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Total Anggota</div>
    </div>
    @foreach($byVoice as $voice => $list)
    <div class="card" style="padding:1rem;text-align:center;">
        <div style="font-size:1.75rem;font-weight:800;color:{{ $voiceColors[$voice] ?? 'var(--accent-color)' }};">{{ $list->count() }}</div>
        <div style="font-size:0.75rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">{{ $voice }}</div>
    </div>
    @endforeach
</div>

<style>
    @media (max-width: 768px) {
        .table {
            display: table !important;
            table-layout: fixed !important;
            width: 100% !important;
        }
        thead, tbody, tr {
            min-width: auto !important;
            display: table-row-group !important;
        }
        thead {
            display: table-header-group !important;
        }
        tr {
            display: table-row !important;
        }
        .table td, .table th {
            padding: 0.5rem 0.35rem !important;
            font-size: 0.75rem !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }
    }
</style>

{{-- Table --}}
<div class="card" style="padding:1.5rem;">
    <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;"><i class="ph ph-users-three" style="color:#8b5cf6;"></i> Daftar Anggota</h5>
    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table" style="font-size:0.875rem; vertical-align: middle; width: 100%;">
            <thead>
                <tr>
                    <th class="hidden-mobile" style="width: 40px;">#</th>
                    <th>Nama</th>
                    <th style="width: 90px;">NPM</th>
                    <th class="hidden-mobile">Fakultas</th>
                    <th class="hidden-mobile">Program Studi</th>
                    <th class="hidden-mobile">Angkatan</th>
                    <th style="width: 100px;">Suara</th>
                    <th class="hidden-mobile">Tanggal Bergabung</th>
                    <th class="hidden-mobile">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $i => $m)
                <tr>
                    <td class="hidden-mobile">{{ $i + 1 }}</td>
                    <td style="font-weight:600; color: var(--text-primary);">{{ $m->name }}</td>
                    <td>{{ $m->npm ?? '-' }}</td>
                    <td class="hidden-mobile">{{ $m->faculty ?? '-' }}</td>
                    <td class="hidden-mobile">{{ $m->major ?? '-' }}</td>
                    <td class="hidden-mobile">{{ $m->class_year ?? '-' }}</td>
                    <td>
                        @if($m->voiceClassification)
                        @php $vc = $m->voiceClassification->name; $cl = $voiceColors[$vc] ?? '#888'; @endphp
                        <span style="background:{{ $cl }}22;color:{{ $cl }};padding:0.2rem 0.6rem;border-radius:4px;font-weight:700;font-size:0.75rem;">{{ $vc }}</span>
                        @else
                        <span style="color:var(--text-muted);font-size:0.8rem;">-</span>
                        @endif
                    </td>
                    <td class="hidden-mobile">{{ date('d M Y', strtotime($m->created_at)) }}</td>
                    <td class="hidden-mobile"><span class="badge" style="background:rgba(16,185,129,0.1);color:var(--success-color);font-weight:700;">{{ $m->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-secondary py-4">Belum ada data anggota.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if (startDateInput && endDateInput) {
            // Set initial min date for end date input
            if (startDateInput.value) {
                endDateInput.min = startDateInput.value;
            }

            // Update min date for end date when start date changes
            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
            });
        }
    });
</script>
@endsection
