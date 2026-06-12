@extends('layouts.app')

@section('title', 'LPJ — Laporan Pertanggungjawaban')
@section('header', 'LPJ — Laporan Pertanggungjawaban')

@section('content')

{{-- Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <a href="{{ route('ukm.reports') }}" style="display:inline-flex;align-items:center;gap:0.35rem;color:var(--accent-color);text-decoration:none;font-weight:600;font-size:0.875rem;margin-bottom:0.35rem;">
            <i class="ph ph-arrow-left"></i> Kembali ke Pusat Laporan
        </a>
        <h3 style="margin:0;font-weight:800;font-size:1.25rem;">LPJ — Laporan Pertanggungjawaban Kepengurusan</h3>
        <p style="margin:0;font-size:0.8rem;color:var(--text-secondary);">
            Periode: {{ date('d M Y', strtotime($startDate)) }} — {{ date('d M Y', strtotime($endDate)) }}
        </p>
    </div>
    <a href="{{ route('ukm.reports.lpj.print') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" target="_blank"
       class="btn" style="padding:0.6rem 1.1rem;font-weight:700;border-radius:8px;display:inline-flex;align-items:center;gap:0.35rem;background:#f59e0b;color:white;text-decoration:none;">
        <i class="ph ph-printer"></i> Cetak / PDF
    </a>
</div>

{{-- Period Filter --}}
<form method="GET" action="{{ route('ukm.reports.lpj') }}" style="margin-bottom:1.5rem;">
    <div class="card" style="padding:1.25rem;">
        <h5 style="font-weight:800;font-size:0.9rem;margin:0 0 1rem;"><i class="ph ph-calendar-check" style="color:#f59e0b;"></i> Periode Kepengurusan</h5>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;align-items:end;">
            <div>
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
            </div>
            <div>
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.6rem;font-weight:700;border-radius:8px;">
                    <i class="ph ph-arrows-clockwise"></i> Perbarui
                </button>
            </div>
        </div>
    </div>
</form>

{{-- Summary Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #3b82f6;">
        <div style="font-size:1.75rem;font-weight:800;color:#3b82f6;">{{ $programs->count() }}</div>
        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Program Kerja</div>
    </div>
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #10b981;">
        <div style="font-size:1.75rem;font-weight:800;color:#10b981;">{{ $performances->count() }}</div>
        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Penampilan</div>
    </div>
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #10b981;">
        <div style="font-size:1.75rem;font-weight:800;color:#10b981;">Rp {{ number_format($income, 0, ',', '.') }}</div>
        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Pemasukan</div>
    </div>
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #ef4444;">
        <div style="font-size:1.75rem;font-weight:800;color:#ef4444;">Rp {{ number_format($expense, 0, ',', '.') }}</div>
        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Pengeluaran</div>
    </div>
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid var(--accent-color);">
        <div style="font-size:1.75rem;font-weight:800;color:var(--accent-color);">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Saldo Akhir</div>
    </div>
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #8b5cf6;">
        <div style="font-size:1.75rem;font-weight:800;color:#8b5cf6;">{{ $members->count() }}</div>
        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Anggota Aktif</div>
    </div>
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #f59e0b;">
        <div style="font-size:1.75rem;font-weight:800;color:#f59e0b;">{{ $inventories->count() }}</div>
        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Inventaris</div>
    </div>
    <div class="card" style="padding:1.25rem;text-align:center;border-top:3px solid #06b6d4;">
        <div style="font-size:1.75rem;font-weight:800;color:#06b6d4;">{{ $letters->count() }}</div>
        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);">Surat</div>
    </div>
</div>

{{-- Section 1: Program Kerja --}}
<div class="card" style="padding:1.5rem;margin-bottom:1.5rem;">
    <h5 style="font-weight:800;font-size:1rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="ph-fill ph-target" style="color:#3b82f6;"></i> Ringkasan Program Kerja
    </h5>
    <table class="table" style="font-size:0.85rem;">
        <thead><tr><th>#</th><th>Nama Program</th><th>Divisi</th><th>Tipe</th><th>Tanggal</th><th>Status</th><th>Laporan</th></tr></thead>
        <tbody>
            @forelse($programs as $i => $prog)
            <tr>
                <td>{{ $i+1 }}</td>
                <td style="font-weight:600;">{{ $prog->name }}</td>
                <td>{{ $prog->division }}</td>
                <td>
                    @php $tc = ['Internal'=>'#6366f1','Event'=>'#0ea5e9','Competition'=>'#f59e0b','Performance'=>'#10b981']; $c = $tc[$prog->activity_type] ?? '#888'; @endphp
                    <span style="background:{{ $c }}22;color:{{ $c }};padding:0.2rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:700;">{{ $prog->activity_type }}</span>
                </td>
                <td>{{ $prog->start_date ? date('d M Y', strtotime($prog->start_date)) : '-' }}</td>
                <td><span class="badge" style="font-weight:700;">{{ $prog->status }}</span></td>
                <td>
                    @if($prog->report)
                    <span class="badge" style="background:rgba(16,185,129,0.1);color:var(--success-color);font-weight:700;">Ada</span>
                    @else
                    <span class="badge" style="background:var(--border-color);color:var(--text-muted);">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Tidak ada program kerja pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Section 2: Penampilan --}}
<div class="card" style="padding:1.5rem;margin-bottom:1.5rem;">
    <h5 style="font-weight:800;font-size:1rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="ph-fill ph-microphone-stage" style="color:#10b981;"></i> Ringkasan Penampilan
    </h5>
    <table class="table" style="font-size:0.85rem;">
        <thead><tr><th>#</th><th>Judul</th><th>Venue</th><th>Tanggal</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($performances as $i => $perf)
            <tr>
                <td>{{ $i+1 }}</td>
                <td style="font-weight:600;">{{ $perf->title }}</td>
                <td>{{ $perf->venue }}</td>
                <td>{{ $perf->performance_date ? date('d M Y', strtotime($perf->performance_date)) : '-' }}</td>
                <td>
                    @php $sc = $perf->status === 'Selesai' ? '#10b981' : ($perf->status === 'Berlangsung' ? '#f59e0b' : '#6366f1'); @endphp
                    <span style="background:{{ $sc }}22;color:{{ $sc }};padding:0.2rem 0.5rem;border-radius:4px;font-size:0.75rem;font-weight:700;">{{ $perf->status }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Tidak ada penampilan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Section 3: Keuangan --}}
<div class="card" style="padding:1.5rem;margin-bottom:1.5rem;">
    <h5 style="font-weight:800;font-size:1rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="ph-fill ph-money" style="color:#10b981;"></i> Rekapitulasi Keuangan
    </h5>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        <div>
            <p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin:0 0 0.75rem;">Pemasukan</p>
            @foreach($finances->where('type','income') as $f)
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.35rem 0;border-bottom:1px solid var(--border-color);">
                <span>{{ $f->title }}</span>
                <span style="font-weight:700;color:#10b981;">Rp {{ number_format($f->amount, 0, ',', '.') }}</span>
            </div>
            @endforeach
            <div style="display:flex;justify-content:space-between;font-size:0.875rem;padding:0.5rem 0;font-weight:800;">
                <span>Total</span><span style="color:#10b981;">Rp {{ number_format($income, 0, ',', '.') }}</span>
            </div>
        </div>
        <div>
            <p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin:0 0 0.75rem;">Pengeluaran</p>
            @foreach($finances->where('type','expense') as $f)
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;padding:0.35rem 0;border-bottom:1px solid var(--border-color);">
                <span>{{ $f->title }}</span>
                <span style="font-weight:700;color:#ef4444;">Rp {{ number_format($f->amount, 0, ',', '.') }}</span>
            </div>
            @endforeach
            <div style="display:flex;justify-content:space-between;font-size:0.875rem;padding:0.5rem 0;font-weight:800;">
                <span>Total</span><span style="color:#ef4444;">Rp {{ number_format($expense, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div style="border-top:2px solid var(--border-color);margin-top:1rem;padding-top:1rem;display:flex;justify-content:space-between;font-size:1rem;font-weight:800;">
        <span>Saldo Akhir</span>
        <span style="color:{{ $saldo >= 0 ? 'var(--accent-color)' : '#ef4444' }}">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
    </div>
</div>

{{-- Section 4: Inventaris --}}
<div class="card" style="padding:1.5rem;margin-bottom:1.5rem;">
    <h5 style="font-weight:800;font-size:1rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="ph-fill ph-package" style="color:#f59e0b;"></i> Rekapitulasi Inventaris
    </h5>
    <table class="table" style="font-size:0.85rem;">
        <thead><tr><th>#</th><th>Nama</th><th>Kode</th><th>Kategori</th><th>Kondisi</th><th>Jumlah</th><th>Lokasi</th></tr></thead>
        <tbody>
            @forelse($inventories as $i => $inv)
            <tr>
                <td>{{ $i+1 }}</td>
                <td style="font-weight:600;">{{ $inv->name }}</td>
                <td>{{ $inv->code ?? '-' }}</td>
                <td>{{ $inv->category ?? '-' }}</td>
                <td>{{ $inv->condition ?? '-' }}</td>
                <td>{{ $inv->quantity }}</td>
                <td>{{ $inv->storage_location ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--text-muted);">Belum ada inventaris.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Section 5: Persuratan --}}
<div class="card" style="padding:1.5rem;margin-bottom:1.5rem;">
    <h5 style="font-weight:800;font-size:1rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="ph-fill ph-envelope" style="color:#06b6d4;"></i> Rekapitulasi Persuratan
    </h5>
    <table class="table" style="font-size:0.85rem;">
        <thead><tr><th>#</th><th>No. Surat</th><th>Tanggal</th><th>Perihal</th><th>Tujuan</th><th>Jenis</th></tr></thead>
        <tbody>
            @forelse($letters as $i => $letter)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $letter->letter_number }}</td>
                <td>{{ date('d M Y', strtotime($letter->date)) }}</td>
                <td style="font-weight:600;">{{ $letter->subject }}</td>
                <td>{{ $letter->destination ?? '-' }}</td>
                <td>
                    <span class="badge" style="font-weight:700;">{{ $letter->type }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:var(--text-muted);">Belum ada surat pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
