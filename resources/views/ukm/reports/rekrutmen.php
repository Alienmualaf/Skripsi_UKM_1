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
        <i class="ph ph-printer"></i> Cetak / PDF
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('ukm.reports.rekrutmen') }}" style="margin-bottom:1.5rem;">
    <div class="card" style="padding:1.25rem;">
        <h5 style="font-weight:800;font-size:0.9rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="ph ph-funnel" style="color:#8b5cf6;"></i> Filter
        </h5>
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
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" style="font-size:0.875rem;">
            </div>
            <div>
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" style="font-size:0.875rem;">
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

{{-- Table --}}
<div class="card" style="padding:1.5rem;">
    <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;"><i class="ph ph-users-three" style="color:#8b5cf6;"></i> Daftar Anggota</h5>
    <table class="table" style="font-size:0.875rem;">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NPM</th>
                <th>Fakultas</th>
                <th>Program Studi</th>
                <th>Angkatan</th>
                <th>Klasifikasi Suara</th>
                <th>Tanggal Bergabung</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $i => $m)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-weight:600;">{{ $m->name }}</td>
                <td>{{ $m->npm ?? '-' }}</td>
                <td>{{ $m->faculty ?? '-' }}</td>
                <td>{{ $m->major ?? '-' }}</td>
                <td>{{ $m->class_year ?? '-' }}</td>
                <td>
                    @if($m->voiceClassification)
                    @php $vc = $m->voiceClassification->name; $cl = $voiceColors[$vc] ?? '#888'; @endphp
                    <span style="background:{{ $cl }}22;color:{{ $cl }};padding:0.2rem 0.6rem;border-radius:4px;font-weight:700;font-size:0.75rem;">{{ $vc }}</span>
                    @else
                    <span style="color:var(--text-muted);font-size:0.8rem;">-</span>
                    @endif
                </td>
                <td>{{ date('d M Y', strtotime($m->created_at)) }}</td>
                <td><span class="badge" style="background:rgba(16,185,129,0.1);color:var(--success-color);font-weight:700;">{{ $m->status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada data anggota.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
