@extends('layouts.app')

@section('title', 'LPJ — Laporan Pertanggungjawaban')
@section('header', 'LPJ — Laporan Pertanggungjawaban')

@section('content')
<style>
    .lpj-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .lpj-finance-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 1024px) {
        .lpj-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
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
        .lpj-finance-grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
    }
    @media (max-width: 640px) {
        .lpj-stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>


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
        <i class="ph ph-download-simple"></i> Download LPJ
    </a>
</div>

{{-- Period Filter --}}
<form method="GET" action="{{ route('ukm.reports.lpj') }}" style="margin-bottom:1.5rem;">
    <div class="card" style="padding:1.25rem;">
        <h5 style="font-weight:800;font-size:0.9rem;margin:0 0 1rem;"><i class="ph ph-calendar-check" style="color:#f59e0b;"></i> Periode Kepengurusan</h5>
        @if ($errors->has('end_date'))
            <div style="background:#fef2f2; border:1px solid #fee2e2; color:#ef4444; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:0.85rem; font-weight:600;">
                <i class="ph ph-warning-circle" style="vertical-align:middle; margin-right:4px;"></i> {{ $errors->first('end_date') }}
            </div>
        @endif
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;align-items:end;">
            <div>
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate }}" required>
            </div>
            <div>
                <label style="font-size:0.8rem;font-weight:700;display:block;margin-bottom:0.35rem;">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate }}" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.6rem;font-weight:700;border-radius:8px;">
                    <i class="ph ph-arrows-clockwise"></i> Perbarui
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if (startDateInput && endDateInput) {
            // Set initial min date for end date input
            endDateInput.min = startDateInput.value;

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

{{-- Summary Stats --}}
<div class="lpj-stats-grid">
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

{{-- Section 1 & 2: Program Kerja & Penyanyi (repeating for each proker) --}}
<h4 style="font-weight:800; font-size:1.15rem; margin:1.5rem 0 1rem 0; display:flex; align-items:center; gap:0.5rem; color:var(--text-primary);">
    <i class="ph-fill ph-target" style="color:#3b82f6;"></i> Laporan Pelaksanaan Program Kerja (Proker)
</h4>

@forelse($programs as $i => $prog)
<div class="card" style="padding:1.75rem; margin-bottom:1.5rem; border-left:4px solid var(--accent-color);">
    <h5 style="font-weight:800; font-size:1.1rem; margin:0 0 1.25rem 0; color:var(--text-primary); display:flex; justify-content:space-between; align-items:center;">
        <span>{{ $i+1 }}. {{ $prog->name }}</span>
        @php 
            $tc = ['Internal'=>'#6366f1','Event'=>'#0ea5e9','Competition'=>'#f59e0b','Performance'=>'#10b981']; 
            $c = $tc[$prog->activity_type] ?? '#888'; 
        @endphp
        <span style="background:{{ $c }}22; color:{{ $c }}; padding:0.25rem 0.6rem; border-radius:6px; font-size:0.75rem; font-weight:700;">{{ $prog->activity_type }}</span>
    </h5>

    {{-- 1. NAMA KEGIATAN, PENANGGUNG JAWAB, TANGGAL, & TEMPAT --}}
    <div style="margin-bottom:1.5rem; background:var(--bg-color); border:1px solid var(--border-color); padding:1rem 1.25rem; border-radius:8px;">
        <h6 style="font-weight:700; font-size:0.875rem; margin:0 0 0.75rem 0; text-transform:uppercase; color:var(--text-secondary); display:flex; align-items:center; gap:0.35rem;">
            <i class="ph ph-info"></i> 1. Detail Kegiatan
        </h6>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; font-size:0.875rem;">
            <div>
                <span style="color:var(--text-secondary); display:block; font-size:0.75rem; font-weight:600; text-transform:uppercase;">Nama Kegiatan</span>
                <span style="font-weight:700; color:var(--text-primary);">{{ $prog->name }}</span>
            </div>
            <div>
                <span style="color:var(--text-secondary); display:block; font-size:0.75rem; font-weight:600; text-transform:uppercase;">Penanggung Jawab</span>
                <span style="font-weight:700; color:var(--text-primary);">{{ $prog->pic ?? '-' }}</span>
            </div>
            <div>
                <span style="color:var(--text-secondary); display:block; font-size:0.75rem; font-weight:600; text-transform:uppercase;">Tanggal</span>
                <span style="font-weight:700; color:var(--text-primary);">{{ $prog->start_date ? date('d M Y', strtotime($prog->start_date)) : '-' }} @if($prog->end_date && $prog->end_date !== $prog->start_date) s.d. {{ date('d M Y', strtotime($prog->end_date)) }} @endif</span>
            </div>
            <div>
                <span style="color:var(--text-secondary); display:block; font-size:0.75rem; font-weight:600; text-transform:uppercase;">Tempat</span>
                <span style="font-weight:700; color:var(--text-primary);">{{ $prog->venue ?? '-' }}</span>
            </div>
        </div>
        @if($prog->description)
        <div style="margin-top: 1rem; border-top: 1px solid var(--border-color); padding-top: 0.75rem; font-size: 0.85rem; line-height:1.6; color:var(--text-secondary);">
            <strong>Deskripsi:</strong> {{ $prog->description }}
        </div>
        @endif
    </div>

    {{-- 2. DAFTAR PENYANYI YANG IKUT (Only if NOT Event) --}}
    @if(in_array(strtolower($prog->activity_type), ['competition', 'performance']))
    <div style="margin-bottom:0.5rem;">
        <h6 style="font-weight:700; font-size:0.875rem; margin:0 0 0.75rem 0; text-transform:uppercase; color:var(--text-secondary); display:flex; align-items:center; gap:0.35rem;">
            <i class="ph ph-users"></i> 2. Daftar Penyanyi yang Ikut
        </h6>
        @php
            $mems = null;
            if ($prog->performance && $prog->performance->classroom) {
                $mems = $prog->performance->classroom->members;
            }
        @endphp
        @if($mems && $mems->count() > 0)
            <div class="table-wrapper" style="margin:0; box-shadow:none; border:1px solid var(--border-color);">
                <table class="table" style="font-size:0.825rem; margin:0;">
                    <thead>
                        <tr><th>#</th><th>Nama Penyanyi</th><th>Klasifikasi Suara</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($mems as $idx => $m)
                        <tr>
                            <td>{{ $idx+1 }}</td>
                            <td style="font-weight:700; color:var(--text-primary);">{{ $m->name }}</td>
                            <td style="font-weight:600; color:var(--text-primary);">{{ $m->voiceClassification->name ?? '-' }}</td>
                            <td>
                                <span class="badge" style="background:rgba(16,185,129,0.1); color:var(--success-color); font-weight:700;">{{ $m->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="font-size:0.825rem; color:var(--text-muted); font-style:italic; margin:0.5rem 0;">Belum ada daftar penyanyi yang ditentukan.</p>
        @endif
    </div>
    @endif
</div>
@empty
<div class="card" style="padding:2rem; text-align:center; color:var(--text-muted);">
    Tidak ada program kerja pada periode ini.
</div>
@endforelse

{{-- Section 3: Keuangan --}}
<div class="card" style="padding:1.5rem;margin-bottom:1.5rem;">
    <h5 style="font-weight:800;font-size:1rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="ph-fill ph-money" style="color:#10b981;"></i> 3. Rekapitulasi Keuangan Selama Satu Periode
    </h5>
    <div class="lpj-finance-grid">
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
        <i class="ph-fill ph-package" style="color:#f59e0b;"></i> 4. Rekapitulasi Inventaris
    </h5>
    <div class="table-wrapper" style="margin-bottom:0;border:none;padding:0;box-shadow:none;">
        <table class="table" style="font-size:0.85rem; width: 100%;">
            <thead>
                <tr>
                    <th class="hidden-mobile" style="width: 40px;">#</th>
                    <th>Nama</th>
                    <th class="hidden-mobile">Kategori</th>
                    <th class="hidden-mobile">Kondisi</th>
                    <th style="width: 70px;">Jumlah</th>
                    <th class="hidden-mobile">Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $i => $inv)
                <tr>
                    <td class="hidden-mobile">{{ $i+1 }}</td>
                    <td style="font-weight:600; color: var(--text-primary);">{{ $inv->name }}</td>
                    <td class="hidden-mobile">{{ $inv->category ?? '-' }}</td>
                    <td class="hidden-mobile">{{ $inv->condition ?? '-' }}</td>
                    <td style="font-weight: 700;">{{ $inv->quantity }}</td>
                    <td class="hidden-mobile">{{ $inv->storage_location ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada inventaris.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Section 5: Persuratan --}}
<div class="card" style="padding:1.5rem;margin-bottom:1.5rem;">
    <h5 style="font-weight:800;font-size:1rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="ph-fill ph-envelope" style="color:#06b6d4;"></i> 5. Rekapitulasi Persuratan
    </h5>
    <div class="table-wrapper" style="margin-bottom:0;border:none;padding:0;box-shadow:none;">
        <table class="table" style="font-size:0.85rem; width: 100%;">
            <thead>
                <tr>
                    <th class="hidden-mobile" style="width: 40px;">#</th>
                    <th>No. Surat</th>
                    <th class="hidden-mobile" style="width: 100px;">Tanggal</th>
                    <th>Perihal</th>
                    <th class="hidden-mobile">Tujuan</th>
                    <th class="hidden-mobile" style="width: 80px;">Jenis</th>
                </tr>
            </thead>
            <tbody>
                @forelse($letters as $i => $letter)
                <tr>
                    <td class="hidden-mobile">{{ $i+1 }}</td>
                    <td style="font-family:monospace; font-weight:600; color: var(--text-primary);">{{ $letter->letter_number }}</td>
                    <td class="hidden-mobile">{{ date('d M Y', strtotime($letter->date)) }}</td>
                    <td style="font-weight:600; color: var(--text-primary);">{{ $letter->subject }}</td>
                    <td class="hidden-mobile">{{ $letter->destination ?? '-' }}</td>
                    <td class="hidden-mobile">
                        <span class="badge" style="font-weight:700;">{{ $letter->type }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada surat pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
