@extends('layouts.app')

@section('title', 'Laporan PSUP')
@section('header', 'Pusat Laporan')

@section('content')
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
        .th-action-compact {
            width: 65px !important;
        }
    }
</style>

<div style="margin-bottom:2rem;">
    <p style="color:var(--text-secondary);font-size:0.95rem;">Pilih jenis laporan yang ingin dibuat, dilihat, atau dicetak.</p>
</div>

{{-- 4 Report Type Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;margin-bottom:2.5rem;">

    {{-- 1. Laporan Kegiatan --}}
    <div class="card" style="padding:1.75rem;border-top:4px solid #3b82f6;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
            <div style="width:44px;height:44px;border-radius:10px;background:rgba(59,130,246,0.1);color:#3b82f6;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
                <i class="ph-fill ph-clipboard-text"></i>
            </div>
            <div>
                <h4 style="margin:0;font-weight:800;font-size:1rem;color:var(--text-primary);">Laporan Kegiatan</h4>
                <p style="margin:0;font-size:0.75rem;color:var(--text-secondary);">Per Program Kerja</p>
            </div>
        </div>
        <p style="font-size:0.825rem;color:var(--text-secondary);line-height:1.6;margin-bottom:1.25rem;">
            Laporan detail per Program Kerja: deskripsi, peserta, keuangan, dan persuratan terkait.
        </p>
        <div style="margin-bottom:1rem;">
            <label style="font-weight:700;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Pilih Program Kerja</label>
            <select id="selectProgram" class="form-control" style="font-size:0.875rem;">
                <option value="">-- Pilih Program Kerja --</option>
                @foreach($programs as $prog)
                    <option value="{{ $prog->id }}">
                        {{ $prog->name }}
                        @if($prog->report) ✓ @endif
                    </option>
                @endforeach
            </select>
        </div>
        <button onclick="goToKegiatan()" class="btn btn-primary" style="width:100%;padding:0.6rem;font-weight:700;border-radius:8px;">
            <i class="ph ph-arrow-right"></i> Buka Laporan
        </button>
    </div>

    {{-- 2. Laporan Rekrutmen --}}
    <div class="card" style="padding:1.75rem;border-top:4px solid #8b5cf6;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
            <div style="width:44px;height:44px;border-radius:10px;background:rgba(139,92,246,0.1);color:#8b5cf6;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
                <i class="ph-fill ph-users-three"></i>
            </div>
            <div>
                <h4 style="margin:0;font-weight:800;font-size:1rem;color:var(--text-primary);">Laporan Rekrutmen</h4>
                <p style="margin:0;font-size:0.75rem;color:var(--text-secondary);">Data Anggota Baru</p>
            </div>
        </div>
        <p style="font-size:0.825rem;color:var(--text-secondary);line-height:1.6;margin-bottom:1.25rem;">
            Daftar anggota baru berdasarkan tahun, periode, atau rentang tanggal bergabung.
        </p>
        <a href="{{ route('ukm.reports.rekrutmen') }}" class="btn" style="display:flex;align-items:center;justify-content:center;gap:0.35rem;width:100%;padding:0.6rem;font-weight:700;border-radius:8px;background:#8b5cf6;color:white;text-decoration:none;">
            <i class="ph ph-arrow-right"></i> Buka Laporan
        </a>
    </div>

    {{-- 3. Laporan Keuangan --}}
    <div class="card" style="padding:1.75rem;border-top:4px solid #10b981;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
            <div style="width:44px;height:44px;border-radius:10px;background:rgba(16,185,129,0.1);color:#10b981;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
                <i class="ph-fill ph-money"></i>
            </div>
            <div>
                <h4 style="margin:0;font-weight:800;font-size:1rem;color:var(--text-primary);">Laporan Keuangan</h4>
                <p style="margin:0;font-size:0.75rem;color:var(--text-secondary);">Kas & Transaksi</p>
            </div>
        </div>
        <p style="font-size:0.825rem;color:var(--text-secondary);line-height:1.6;margin-bottom:1.25rem;">
            Rekapitulasi keuangan organisasi: pemasukan, pengeluaran, dan saldo per periode.
        </p>
        <a href="{{ route('ukm.reports.keuangan') }}" class="btn" style="display:flex;align-items:center;justify-content:center;gap:0.35rem;width:100%;padding:0.6rem;font-weight:700;border-radius:8px;background:#10b981;color:white;text-decoration:none;">
            <i class="ph ph-arrow-right"></i> Buka Laporan
        </a>
    </div>

    {{-- 4. LPJ --}}
    <div class="card" style="padding:1.75rem;border-top:4px solid #f59e0b;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
            <div style="width:44px;height:44px;border-radius:10px;background:rgba(245,158,11,0.1);color:#f59e0b;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
                <i class="ph-fill ph-book-bookmark"></i>
            </div>
            <div>
                <h4 style="margin:0;font-weight:800;font-size:1rem;color:var(--text-primary);">LPJ</h4>
                <p style="margin:0;font-size:0.75rem;color:var(--text-secondary);">Laporan Pertanggungjawaban</p>
            </div>
        </div>
        <p style="font-size:0.825rem;color:var(--text-secondary);line-height:1.6;margin-bottom:1.25rem;">
            Laporan akhir periode kepengurusan: program kerja, penampilan, keuangan, inventaris, dan persuratan.
        </p>
        <a href="{{ route('ukm.reports.lpj') }}" class="btn" style="display:flex;align-items:center;justify-content:center;gap:0.35rem;width:100%;padding:0.6rem;font-weight:700;border-radius:8px;background:#f59e0b;color:white;text-decoration:none;">
            <i class="ph ph-arrow-right"></i> Buka LPJ
        </a>
    </div>

</div>

{{-- Program Kerja Table with report status --}}
<div class="card" style="padding:1.5rem;">
    <h4 style="font-weight:800;font-size:1rem;margin:0 0 1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="ph ph-list-checks" style="color:var(--accent-color);"></i> Status Laporan per Program Kerja
    </h4>
    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table" style="font-size:0.875rem; vertical-align: middle;">
            <thead>
                <tr>
                    <th class="hidden-mobile" style="width: 40px;">#</th>
                    <th>Program Kerja</th>
                    <th class="hidden-mobile">Divisi</th>
                    <th class="hidden-mobile">Tipe</th>
                    <th class="hidden-mobile">Tanggal</th>
                    <th style="width: 100px;">Status</th>
                    <th class="th-action-compact" style="width: 80px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $i => $prog)
                <tr>
                    <td class="hidden-mobile">{{ $i + 1 }}</td>
                    <td style="font-weight:600; color: var(--text-primary);">{{ $prog->name }}</td>
                    <td class="hidden-mobile">{{ $prog->division }}</td>
                    <td class="hidden-mobile">
                        @php $colors = ['Internal'=>'#6366f1','Event'=>'#0ea5e9','Competition'=>'#f59e0b','Performance'=>'#10b981']; $c = $colors[$prog->activity_type] ?? '#888'; @endphp
                        <span style="background:{{ $c }}22;color:{{ $c }};padding:0.2rem 0.6rem;border-radius:4px;font-weight:700;font-size:0.75rem;">{{ $prog->activity_type }}</span>
                    </td>
                    <td class="hidden-mobile">{{ $prog->start_date ? date('d M Y', strtotime($prog->start_date)) : '-' }}</td>
                    <td>
                        @if($prog->report)
                            @if($prog->report->status === 'Approved')
                                <span class="badge" style="background:rgba(16,185,129,0.1);color:var(--success-color);font-weight:700;">Disetujui</span>
                            @elseif($prog->report->status === 'Submitted')
                                <span class="badge" style="background:rgba(14,165,233,0.1);color:#0ea5e9;font-weight:700;">Diajukan</span>
                            @else
                                <span class="badge" style="background:rgba(245,158,11,0.1);color:#f59e0b;font-weight:700;">Draft</span>
                            @endif
                        @else
                            <span class="badge" style="background:var(--border-color);color:var(--text-secondary);">Belum ada</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('ukm.reports.kegiatan', $prog->id) }}" class="btn" style="padding:0.3rem 0.5rem;font-size:0.75rem;font-weight:700;border-radius:6px;text-decoration:none;background:rgba(59,130,246,0.1);color:#3b82f6;display:inline-flex;align-items:center;justify-content:center;" title="{{ $prog->report ? 'Lihat' : 'Buat' }}">
                            <i class="ph ph-eye"></i><span class="hidden-mobile"> {{ $prog->report ? 'Lihat' : 'Buat' }}</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada Program Kerja.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<script>
function goToKegiatan() {
    const val = document.getElementById('selectProgram').value;
    if (!val) { alert('Pilih Program Kerja terlebih dahulu.'); return; }
    window.location.href = '{{ url("ukm/reports/kegiatan") }}/' + val;
}
</script>
@endsection
