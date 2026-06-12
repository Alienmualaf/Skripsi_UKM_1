@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('header', 'Laporan Keuangan')

@section('content')

{{-- Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <a href="{{ route('ukm.reports') }}" style="display:inline-flex;align-items:center;gap:0.35rem;color:var(--accent-color);text-decoration:none;font-weight:600;font-size:0.875rem;margin-bottom:0.35rem;">
            <i class="ph ph-arrow-left"></i> Kembali ke Pusat Laporan
        </a>
        <h3 style="margin:0;font-weight:800;font-size:1.25rem;">Laporan Keuangan Organisasi</h3>
    </div>
    <a href="{{ route('ukm.reports.keuangan.print') }}?{{ http_build_query(request()->all()) }}" target="_blank"
       class="btn" style="padding:0.6rem 1.1rem;font-weight:700;border-radius:8px;display:inline-flex;align-items:center;gap:0.35rem;background:#10b981;color:white;text-decoration:none;">
        <i class="ph ph-printer"></i> Cetak / PDF
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('ukm.reports.keuangan') }}" style="margin-bottom:1.5rem;">
    <div class="card" style="padding:1.25rem;">
        <h5 style="font-weight:800;font-size:0.9rem;margin:0 0 1rem;"><i class="ph ph-funnel" style="color:#10b981;"></i> Filter</h5>

        {{-- Mode Tabs --}}
        <div style="display:flex;gap:0.5rem;margin-bottom:1rem;">
            @foreach(['custom'=>'Rentang Tanggal','bulanan'=>'Bulanan','tahunan'=>'Tahunan'] as $mode => $label)
            <button type="button" onclick="switchMode('{{ $mode }}')" id="mode-{{ $mode }}"
                style="padding:0.4rem 1rem;border-radius:6px;font-size:0.8rem;font-weight:700;cursor:pointer;border:1.5px solid {{ request('filter_mode','custom') === $mode ? 'var(--accent-color)' : 'var(--border-color)' }};background:{{ request('filter_mode','custom') === $mode ? 'var(--accent-light)' : 'var(--bg-color)' }};color:{{ request('filter_mode','custom') === $mode ? 'var(--accent-color)' : 'var(--text-secondary)' }};">
                {{ $label }}
            </button>
            @endforeach
            <input type="hidden" name="filter_mode" id="filter_mode" value="{{ request('filter_mode','custom') }}">
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;align-items:end;">

            {{-- Custom Date --}}
            <div id="field-start_date" style="{{ request('filter_mode','custom') !== 'custom' ? 'display:none' : '' }}">
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" style="font-size:0.875rem;">
            </div>
            <div id="field-end_date" style="{{ request('filter_mode','custom') !== 'custom' ? 'display:none' : '' }}">
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" style="font-size:0.875rem;">
            </div>

            {{-- Bulanan --}}
            <div id="field-bulan" style="{{ request('filter_mode') !== 'bulanan' ? 'display:none' : '' }}">
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Bulan</label>
                <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}" style="font-size:0.875rem;">
            </div>

            {{-- Tahunan --}}
            <div id="field-tahun" style="{{ request('filter_mode') !== 'tahunan' ? 'display:none' : '' }}">
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tahun</label>
                <select name="tahun" class="form-control" style="font-size:0.875rem;">
                    <option value="">Semua</option>
                    @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.6rem;font-weight:700;border-radius:8px;">
                    <i class="ph ph-magnifying-glass"></i> Terapkan
                </button>
            </div>
            <div>
                <a href="{{ route('ukm.reports.keuangan') }}" class="btn" style="width:100%;padding:0.6rem;font-weight:700;border-radius:8px;background:var(--bg-color);border:1px solid var(--border-color);text-decoration:none;display:block;text-align:center;">Reset</a>
            </div>
        </div>
    </div>
</form>

{{-- Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="card" style="padding:1.25rem;border-left:4px solid #10b981;">
        <p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin:0 0 0.4rem;">Total Pemasukan</p>
        <p style="font-size:1.5rem;font-weight:800;color:#10b981;margin:0;">Rp {{ number_format($income, 0, ',', '.') }}</p>
    </div>
    <div class="card" style="padding:1.25rem;border-left:4px solid #ef4444;">
        <p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin:0 0 0.4rem;">Total Pengeluaran</p>
        <p style="font-size:1.5rem;font-weight:800;color:#ef4444;margin:0;">Rp {{ number_format($expense, 0, ',', '.') }}</p>
    </div>
    <div class="card" style="padding:1.25rem;border-left:4px solid {{ $saldo >= 0 ? 'var(--accent-color)' : '#ef4444' }};">
        <p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin:0 0 0.4rem;">Saldo Akhir</p>
        <p style="font-size:1.5rem;font-weight:800;color:{{ $saldo >= 0 ? 'var(--accent-color)' : '#ef4444' }};margin:0;">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
    </div>
    <div class="card" style="padding:1.25rem;border-left:4px solid #f59e0b;">
        <p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin:0 0 0.4rem;">Transaksi</p>
        <p style="font-size:1.5rem;font-weight:800;color:#f59e0b;margin:0;">{{ $finances->count() }} data</p>
    </div>
</div>

{{-- Transaction Tables --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    {{-- Income --}}
    <div class="card" style="padding:1.25rem;">
        <h5 style="font-weight:800;font-size:0.95rem;margin:0 0 1rem;color:#10b981;"><i class="ph ph-trend-up"></i> Pemasukan</h5>
        <table class="table" style="font-size:0.8rem;">
            <thead><tr><th>Tanggal</th><th>Keterangan</th><th style="text-align:right;">Jumlah</th></tr></thead>
            <tbody>
                @forelse($finances->where('type','income') as $f)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($f->transaction_date)) }}</td>
                    <td>{{ $f->title }}</td>
                    <td style="text-align:right;font-weight:700;color:#10b981;">Rp {{ number_format($f->amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--text-muted);">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Expense --}}
    <div class="card" style="padding:1.25rem;">
        <h5 style="font-weight:800;font-size:0.95rem;margin:0 0 1rem;color:#ef4444;"><i class="ph ph-trend-down"></i> Pengeluaran</h5>
        <table class="table" style="font-size:0.8rem;">
            <thead><tr><th>Tanggal</th><th>Keterangan</th><th style="text-align:right;">Jumlah</th></tr></thead>
            <tbody>
                @forelse($finances->where('type','expense') as $f)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($f->transaction_date)) }}</td>
                    <td>{{ $f->title }}</td>
                    <td style="text-align:right;font-weight:700;color:#ef4444;">Rp {{ number_format($f->amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--text-muted);">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function switchMode(mode) {
    document.getElementById('filter_mode').value = mode;
    ['custom','bulanan','tahunan'].forEach(m => {
        const btn = document.getElementById('mode-' + m);
        btn.style.borderColor = m === mode ? 'var(--accent-color)' : 'var(--border-color)';
        btn.style.background  = m === mode ? 'var(--accent-light)' : 'var(--bg-color)';
        btn.style.color       = m === mode ? 'var(--accent-color)' : 'var(--text-secondary)';
    });
    document.getElementById('field-start_date').style.display = mode === 'custom' ? '' : 'none';
    document.getElementById('field-end_date').style.display   = mode === 'custom' ? '' : 'none';
    document.getElementById('field-bulan').style.display      = mode === 'bulanan' ? '' : 'none';
    document.getElementById('field-tahun').style.display      = mode === 'tahunan' ? '' : 'none';
}
</script>
@endsection
