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
        <i class="ph ph-download-simple"></i> Download Laporan
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('ukm.reports.keuangan') }}" style="margin-bottom:1.5rem;">
    <div class="card" style="padding:1.25rem;">
        <h5 style="font-weight:800;font-size:0.9rem;margin:0 0 1rem;"><i class="ph ph-funnel" style="color:#10b981;"></i> Filter</h5>
        @if ($errors->has('end_date'))
            <div style="background:#fef2f2; border:1px solid #fee2e2; color:#ef4444; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:0.85rem; font-weight:600;">
                <i class="ph ph-warning-circle" style="vertical-align:middle; margin-right:4px;"></i> {{ $errors->first('end_date') }}
            </div>
        @endif

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
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}" style="font-size:0.875rem;">
            </div>
            <div id="field-end_date" style="{{ request('filter_mode','custom') !== 'custom' ? 'display:none' : '' }}">
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}" style="font-size:0.875rem;">
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

{{-- Transaction Table --}}
<div class="card" style="padding:1.5rem;">
    <h5 style="font-weight:800;font-size:1.1rem;margin:0 0 1.25rem;display:flex;align-items:center;gap:0.5rem;color:var(--text-primary);">
        <i class="ph ph-list-bullets" style="color:var(--accent-color);"></i> Daftar Transaksi Keuangan
    </h5>
    <div class="table-wrapper" style="margin-bottom:0;border:none;padding:0;box-shadow:none;">
        <table class="table" style="font-size:0.875rem; width: 100%;">
            <thead>
                <tr>
                    <th style="width: 80px;">Tanggal</th>
                    <th class="hidden-mobile">Jenis</th>
                    <th>Transaksi</th>
                    <th class="hidden-mobile">Digunakan Untuk</th>
                    <th class="hidden-mobile">Deskripsi</th>
                    <th style="text-align:right; width: 110px;">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($finances as $f)
                <tr>
                    <td style="color:var(--text-secondary);">{{ date('d/m/Y', strtotime($f->transaction_date)) }}</td>
                    <td class="hidden-mobile">
                        @if($f->type === 'income')
                            <span class="badge" style="background:rgba(16, 185, 129, 0.1);color:var(--success-color);border:1.5px solid rgba(16, 185, 129, 0.2);font-weight:bold;font-size:0.7rem;padding:0.25rem 0.5rem;">Pemasukan</span>
                        @else
                            <span class="badge" style="background:rgba(239, 68, 68, 0.1);color:var(--danger-color);border:1.5px solid rgba(239, 68, 68, 0.2);font-weight:bold;font-size:0.7rem;padding:0.25rem 0.5rem;">Pengeluaran</span>
                        @endif
                    </td>
                    <td style="font-weight:700;color:var(--text-primary);">{{ $f->title }}</td>
                    <td class="hidden-mobile">
                        @if($f->used_for === 'Program Kerja')
                            <span class="badge" style="background:rgba(30,64,175,0.06);color:var(--accent-color);border:1px solid rgba(30,64,175,0.12);font-weight:bold;font-size:0.7rem;padding:0.25rem 0.5rem;">
                                Proker: {{ $f->program->name ?? '-' }}
                            </span>
                        @else
                            <span class="badge" style="background:rgba(100,116,139,0.06);color:var(--text-secondary);border:1px solid rgba(100,116,139,0.12);font-weight:bold;font-size:0.7rem;padding:0.25rem 0.5rem;">
                                Umum
                            </span>
                        @endif
                    </td>
                    <td class="hidden-mobile" style="color:var(--text-secondary);max-width:250px;white-space:normal;word-break:break-word;font-size:0.8125rem;">
                        {{ $f->description ?? '-' }}
                    </td>
                    <td style="text-align:right;font-weight:800;color:{{ $f->type === 'income' ? 'var(--success-color)' : 'var(--danger-color)' }};">
                        {{ $f->type === 'income' ? '+' : '-' }}Rp {{ number_format($f->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Tidak ada data transaksi keuangan yang sesuai filter.</td>
                </tr>
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
