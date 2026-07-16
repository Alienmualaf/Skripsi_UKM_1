@extends('layouts.app')

@section('title', 'Laporan Kegiatan — ' . $program->name)
@section('header', 'Laporan Kegiatan')

@section('content')

<style>
    .stats-grid-mobile {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
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
        .stats-grid-mobile {
            grid-template-columns: 1fr !important;
        }
        .kop-logo {
            height: 50px !important;
        }
    }
</style>

{{-- Breadcrumb + Actions --}}
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
    <div>
        <a href="{{ route('ukm.reports') }}" style="display:inline-flex;align-items:center;gap:0.35rem;color:var(--accent-color);text-decoration:none;font-weight:600;font-size:0.875rem;margin-bottom:0.35rem;">
            <i class="ph ph-arrow-left"></i> Kembali ke Pusat Laporan
        </a>
        <h3 style="margin:0;font-weight:800;font-size:1.25rem;">{{ $program->name }}</h3>
        <p style="margin:0;font-size:0.8rem;color:var(--text-secondary);">
            Divisi {{ $program->division }} &bull;
            @php $colors = ['Internal'=>'#6366f1','Event'=>'#0ea5e9','Competition'=>'#f59e0b','Performance'=>'#10b981']; $c = $colors[$program->activity_type] ?? '#888'; @endphp
            <span style="color:{{ $c }};font-weight:700;">{{ $program->activity_type }}</span>
        </p>
    </div>
    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
        @if($program->report)
            <a href="{{ route('ukm.reports.kegiatan.print', $program->id) }}" target="_blank"
               class="btn" style="padding:0.6rem 1rem;font-weight:700;border-radius:8px;display:inline-flex;align-items:center;gap:0.35rem;background:var(--bg-color);border:1px solid var(--border-color);color:var(--text-primary);text-decoration:none;">
                <i class="ph ph-download-simple"></i> Download LPJ
            </a>
            @if(auth()->user()->isAdminUkm() || auth()->user()->isSuperAdmin())
            <form action="{{ route('ukm.reports.kegiatan.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger" style="padding:0.6rem 1rem;font-weight:700;border-radius:8px;display:inline-flex;align-items:center;gap:0.35rem;">
                    <i class="ph ph-trash"></i> Hapus
                </button>
            </form>
            @endif
        @endif
    </div>
</div>

@if(session('success'))
<div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:var(--success-color);padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-weight:600;">
    <i class="ph ph-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="display:grid;grid-template-columns:1fr;gap:1.5rem;align-items:start;">

    {{-- Main Report Card --}}
    <div class="card" style="padding:2.5rem; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.05); background:#fff; border:1px solid var(--border-color);">
        
        {{-- Kop Surat Resmi PSUP (inside the page preview!) --}}
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:3px double #000; padding-bottom:1rem; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; justify-content:center; text-align:center;">
            <img src="{{ asset('images/senat.png') }}" alt="Logo Senat" class="kop-logo" style="height:70px; width:auto;">
            <div style="text-align:center; flex-grow:1; font-family:'Times New Roman', Times, serif; color:#000; min-width:200px;">
                <h4 style="margin:0; font-size:1.15rem; font-weight:bold; letter-spacing:0.5px;">PADUAN SUARA UNIVERSITAS PANCASILA</h4>
                <h5 style="margin:2px 0 0; font-size:0.95rem; font-weight:bold;">SENAT MAHASISWA UNIVERSITAS PANCASILA</h5>
            </div>
            <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP" class="kop-logo" style="height:70px; width:auto;">
        </div>

        {{-- Title --}}
        <div style="text-align:center; margin-bottom:2rem;">
            <h3 style="font-family:'Times New Roman', Times, serif; font-size:1.35rem; font-weight:bold; text-transform:uppercase; margin:0;">
                LAPORAN KEGIATAN {{ $program->activity_type === 'Event' ? 'EVENT' : ($program->activity_type === 'Competition' ? 'LOMBA' : 'PENAMPILAN / JOB') }}
            </h3>
            <p style="margin:5px 0 0; font-size:0.9rem; color:var(--text-secondary);">Program Kerja: {{ $program->name }}</p>
        </div>

        {{-- Content based on type --}}
        @if(in_array(strtolower($program->activity_type), ['event', 'internal']))
            {{-- EVENT STRUCTURE --}}
            <div>
                
                {{-- 1. Nama Event & 2. Tempat --}}
                <div style="margin-bottom:2rem;">
                    <h5 style="font-weight:800; font-size:0.95rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem; margin-bottom:1rem; color:var(--text-primary); display:flex; align-items:center; gap:0.5rem;">
                        <i class="ph ph-info" style="color:var(--accent-color);"></i> 1. DETAIL EVENT
                    </h5>
                    <table style="width:100%; font-size:0.875rem; border-collapse:collapse;">
                        <tr>
                            <td style="padding:0.5rem; border:none; width:25%; color:var(--text-secondary); font-weight:600;">Nama Event</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary); font-weight:700;">: {{ $program->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding:0.5rem; border:none; color:var(--text-secondary); font-weight:600;">Tempat / Lokasi</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary);">: {{ $program->venue ?? 'Sekretariat PSUP / Universitas Pancasila' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:0.5rem; border:none; color:var(--text-secondary); font-weight:600;">Tanggal Pelaksanaan</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary);">: {{ date('d F Y', strtotime($program->start_date)) }} @if($program->end_date && $program->end_date !== $program->start_date) s.d. {{ date('d F Y', strtotime($program->end_date)) }} @endif</td>
                        </tr>
                        <tr>
                            <td style="padding:0.5rem; border:none; color:var(--text-secondary); font-weight:600;">Deskripsi</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary); line-height:1.6;">: {{ $program->description ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- 3. Keuangan --}}
                <div style="margin-bottom:2rem;">
                    <h5 style="font-weight:800; font-size:0.95rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem; margin-bottom:1rem; color:var(--text-primary); display:flex; align-items:center; gap:0.5rem;">
                        <i class="ph ph-coins" style="color:#10b981;"></i> 2. REALISASI KEUANGAN
                    </h5>
                    @php
                        $income  = $finances->where('type','income')->sum('amount');
                        $expense = $finances->where('type','expense')->sum('amount');
                        $saldo   = $income - $expense;
                    @endphp
                    <div class="stats-grid-mobile">
                        <div style="background:rgba(16,185,129,0.04); border:1px solid rgba(16,185,129,0.1); padding:1rem; border-radius:10px; text-align:center;">
                            <span style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; display:block; margin-bottom:0.25rem;">Total Pemasukan</span>
                            <span style="font-size:1.1rem; font-weight:800; color:#10b981;">Rp {{ number_format($income, 0, ',', '.') }}</span>
                        </div>
                        <div style="background:rgba(239,68,68,0.04); border:1px solid rgba(239,68,68,0.1); padding:1rem; border-radius:10px; text-align:center;">
                            <span style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; display:block; margin-bottom:0.25rem;">Total Pengeluaran</span>
                            <span style="font-size:1.1rem; font-weight:800; color:#ef4444;">Rp {{ number_format($expense, 0, ',', '.') }}</span>
                        </div>
                        <div style="background:rgba(59,130,246,0.04); border:1px solid rgba(59,130,246,0.1); padding:1rem; border-radius:10px; text-align:center;">
                            <span style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; display:block; margin-bottom:0.25rem;">Saldo Akhir</span>
                            <span style="font-size:1.1rem; font-weight:800; color:var(--accent-color);">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @if($finances->count())
                        <div class="table-wrapper" style="margin-bottom:0;border:none;padding:0;box-shadow:none;">
                            <table class="table" style="font-size:0.85rem; width:100%;">
                                <thead>
                                    <tr>
                                        <th style="width:80px;">Tanggal</th>
                                        <th>Keterangan</th>
                                        <th class="hidden-mobile" style="width:100px;">Jenis</th>
                                        <th style="text-align:right; width:120px;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($finances as $f)
                                    <tr>
                                        <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                                        <td style="color:var(--text-primary); font-weight:600;">{{ $f->description }}</td>
                                        <td class="hidden-mobile">
                                            <span class="badge" style="background:{{ $f->type === 'income' ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }}; color:{{ $f->type === 'income' ? 'var(--success-color)' : '#ef4444' }}; font-weight:700;">
                                                {{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                            </span>
                                        </td>
                                        <td style="text-align:right; font-weight:700; color:{{ $f->type === 'income' ? 'var(--success-color)' : '#ef4444' }};">
                                            {{ $f->type === 'income' ? '+' : '-' }}Rp {{ number_format($f->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="text-align:center; font-size:0.8rem; color:var(--text-muted); margin:1rem 0;">Tidak ada catatan transaksi keuangan untuk kegiatan ini.</p>
                    @endif
                </div>

                {{-- 4. Persuratan --}}
                <div>
                    <h5 style="font-weight:800; font-size:0.95rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem; margin-bottom:1rem; color:var(--text-primary); display:flex; align-items:center; gap:0.5rem;">
                        <i class="ph ph-envelope" style="color:#f59e0b;"></i> 3. DOKUMEN PERSURATAN
                    </h5>
                    @if($letters->count())
                        <div class="table-wrapper" style="margin-bottom:0;border:none;padding:0;box-shadow:none;">
                            <table class="table" style="font-size:0.85rem; width:100%;">
                                <thead>
                                    <tr>
                                        <th>No. Surat</th>
                                        <th class="hidden-mobile" style="width:100px;">Tanggal</th>
                                        <th>Perihal</th>
                                        <th class="hidden-mobile">Tujuan / Asal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($letters as $l)
                                    <tr>
                                        <td style="font-family:monospace; font-weight:600; color:var(--text-primary);">{{ $l->letter_number }}</td>
                                        <td class="hidden-mobile">{{ date('d-m-Y', strtotime($l->date)) }}</td>
                                        <td>{{ $l->subject }}</td>
                                        <td class="hidden-mobile">{{ $l->destination ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="text-align:center; font-size:0.8rem; color:var(--text-muted); margin:1rem 0;">Belum ada dokumen persuratan yang tertaut dengan kegiatan ini.</p>
                    @endif
                </div>

            </div>
        @else
            {{-- LOMBA, PENAMPILAN, JOB STRUCTURE --}}
            <div>
                
                {{-- 1. Nama Lomba/Penampilan/Job, 2. Penanggung Jawab, 3. Tanggal, 4. Tempat --}}
                <div style="margin-bottom:2rem;">
                    <h5 style="font-weight:800; font-size:0.95rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem; margin-bottom:1rem; color:var(--text-primary); display:flex; align-items:center; gap:0.5rem;">
                        <i class="ph ph-info" style="color:var(--accent-color);"></i> 1. DETAIL KEGIATAN
                    </h5>
                    <table style="width:100%; font-size:0.875rem; border-collapse:collapse;">
                        <tr>
                            <td style="padding:0.5rem; border:none; width:25%; color:var(--text-secondary); font-weight:600;">Nama Kegiatan</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary); font-weight:700;">: {{ $program->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding:0.5rem; border:none; width:25%; color:var(--text-secondary); font-weight:600;">Penanggung Jawab (PIC)</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary); font-weight:700;">: {{ $program->pic ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:0.5rem; border:none; width:25%; color:var(--text-secondary); font-weight:600;">Tanggal Pelaksanaan</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary);">: {{ date('d F Y', strtotime($program->start_date)) }} @if($program->end_date && $program->end_date !== $program->start_date) s.d. {{ date('d F Y', strtotime($program->end_date)) }} @endif</td>
                        </tr>
                        <tr>
                            <td style="padding:0.5rem; border:none; width:25%; color:var(--text-secondary); font-weight:600;">Tempat / Lokasi</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary);">: {{ $program->venue ?? 'Sekretariat PSUP / Universitas Pancasila' }}</td>
                        </tr>
                        @if($program->performance && $program->performance->classroom && $program->performance->classroom->trainer)
                        <tr>
                            <td style="padding:0.5rem; border:none; width:25%; color:var(--text-secondary); font-weight:600;">Pelatih</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary); font-weight:700;">: {{ $program->performance->classroom->trainer->name }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding:0.5rem; border:none; width:25%; color:var(--text-secondary); font-weight:600;">Deskripsi</td>
                            <td style="padding:0.5rem; border:none; color:var(--text-primary); line-height:1.6;">: {{ $program->description ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- 5. Daftar Penyanyi yang Ikut --}}
                <div style="margin-bottom:2rem;">
                    @php
                        $mems = null;
                        if ($program->performance && $program->performance->classroom) {
                            $mems = $program->performance->classroom->members;
                        }
                    @endphp
                    <h5 style="font-weight:800; font-size:0.95rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem; margin-bottom:1rem; color:var(--text-primary); display:flex; align-items:center; gap:0.5rem;">
                        <i class="ph ph-users" style="color:#8b5cf6;"></i> 2. DAFTAR PENYANYI YANG MENGIKUTI @if($mems) ({{ $mems->count() }} Orang) @endif
                    </h5>
                    @if($mems && $mems->count())
                        <div class="table-wrapper" style="margin-bottom:0;border:none;padding:0;box-shadow:none;">
                            <table class="table" style="font-size:0.85rem; width:100%;">
                                <thead>
                                    <tr>
                                        <th class="hidden-mobile" style="width: 40px;">#</th>
                                        <th>Nama Lengkap</th>
                                        <th>Suara</th>
                                        <th class="hidden-mobile">Status</th>
                                        <th class="hidden-mobile" style="text-align:center;">Hadir / Sesi</th>
                                        <th style="text-align:center; width: 80px;">Rasio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mems as $i => $m)
                                    @php
                                        $classroom = $program->performance->classroom;
                                        $total = $classroom ? $classroom->attendances->count() : 0;
                                        $hadir = 0;
                                        if ($classroom) {
                                            foreach($classroom->attendances as $att) {
                                                $detail = $att->details->where('member_id', $m->id)->first();
                                                if ($detail && strtolower($detail->status) === 'hadir') {
                                                    $hadir++;
                                                }
                                            }
                                        }
                                        $percentage = $total > 0 ? ($hadir / $total) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="hidden-mobile">{{ $i+1 }}</td>
                                        <td style="font-weight:700; color:var(--text-primary);">{{ $m->name }}</td>
                                        <td>{{ $m->voiceClassification->name ?? '-' }}</td>
                                        <td class="hidden-mobile">
                                            <span class="badge" style="background:rgba(16,185,129,0.1); color:var(--success-color); font-weight:700;">
                                                {{ $m->status }}
                                            </span>
                                        </td>
                                        <td class="hidden-mobile" style="text-align:center;">{{ $hadir }} / {{ $total }}</td>
                                        <td style="text-align:center; font-weight:bold; color:{{ $percentage >= 75 ? 'var(--success-color)' : ($percentage >= 50 ? 'var(--warning-color)' : 'var(--danger-color)') }};">
                                            {{ $total > 0 ? round($percentage, 1) . '%' : '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="text-align:center; font-size:0.8rem; color:var(--text-muted); margin:1rem 0;">Belum ada daftar penyanyi yang ditentukan untuk penampilan / kegiatan ini.</p>
                    @endif
                </div>

                {{-- 6. Keuangan --}}
                <div style="margin-bottom:2rem;">
                    <h5 style="font-weight:800; font-size:0.95rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem; margin-bottom:1rem; color:var(--text-primary); display:flex; align-items:center; gap:0.5rem;">
                        <i class="ph ph-coins" style="color:#10b981;"></i> 3. REALISASI KEUANGAN
                    </h5>
                    @php
                        $income  = $finances->where('type','income')->sum('amount');
                        $expense = $finances->where('type','expense')->sum('amount');
                        $saldo   = $income - $expense;
                    @endphp
                    <div class="stats-grid-mobile">
                        <div style="background:rgba(16,185,129,0.04); border:1px solid rgba(16,185,129,0.1); padding:1rem; border-radius:10px; text-align:center;">
                            <span style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; display:block; margin-bottom:0.25rem;">Total Pemasukan</span>
                            <span style="font-size:1.1rem; font-weight:800; color:#10b981;">Rp {{ number_format($income, 0, ',', '.') }}</span>
                        </div>
                        <div style="background:rgba(239,68,68,0.04); border:1px solid rgba(239,68,68,0.1); padding:1rem; border-radius:10px; text-align:center;">
                            <span style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; display:block; margin-bottom:0.25rem;">Total Pengeluaran</span>
                            <span style="font-size:1.1rem; font-weight:800; color:#ef4444;">Rp {{ number_format($expense, 0, ',', '.') }}</span>
                        </div>
                        <div style="background:rgba(59,130,246,0.04); border:1px solid rgba(59,130,246,0.1); padding:1rem; border-radius:10px; text-align:center;">
                            <span style="font-size:0.75rem; color:var(--text-secondary); font-weight:600; display:block; margin-bottom:0.25rem;">Saldo Akhir</span>
                            <span style="font-size:1.1rem; font-weight:800; color:var(--accent-color);">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @if($finances->count())
                        <div class="table-wrapper" style="margin-bottom:0;border:none;padding:0;box-shadow:none;">
                            <table class="table" style="font-size:0.85rem; width:100%;">
                                <thead>
                                    <tr>
                                        <th style="width:80px;">Tanggal</th>
                                        <th>Keterangan</th>
                                        <th class="hidden-mobile" style="width:100px;">Jenis</th>
                                        <th style="text-align:right; width:120px;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($finances as $f)
                                    <tr>
                                        <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                                        <td style="color:var(--text-primary); font-weight:600;">{{ $f->description }}</td>
                                        <td class="hidden-mobile">
                                            <span class="badge" style="background:{{ $f->type === 'income' ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }}; color:{{ $f->type === 'income' ? 'var(--success-color)' : '#ef4444' }}; font-weight:700;">
                                                {{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                            </span>
                                        </td>
                                        <td style="text-align:right; font-weight:700; color:{{ $f->type === 'income' ? 'var(--success-color)' : '#ef4444' }};">
                                            {{ $f->type === 'income' ? '+' : '-' }}Rp {{ number_format($f->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="text-align:center; font-size:0.8rem; color:var(--text-muted); margin:1rem 0;">Tidak ada catatan transaksi keuangan untuk kegiatan ini.</p>
                    @endif
                </div>

                {{-- 7. Persuratan --}}
                <div>
                    <h5 style="font-weight:800; font-size:0.95rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem; margin-bottom:1rem; color:var(--text-primary); display:flex; align-items:center; gap:0.5rem;">
                        <i class="ph ph-envelope" style="color:#f59e0b;"></i> 4. DOKUMEN PERSURATAN
                    </h5>
                    @if($letters->count())
                        <div class="table-wrapper" style="margin-bottom:0;border:none;padding:0;box-shadow:none;">
                            <table class="table" style="font-size:0.85rem; width:100%;">
                                <thead>
                                    <tr>
                                        <th>No. Surat</th>
                                        <th class="hidden-mobile" style="width:100px;">Tanggal</th>
                                        <th>Perihal</th>
                                        <th class="hidden-mobile">Tujuan / Asal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($letters as $l)
                                    <tr>
                                        <td style="font-family:monospace; font-weight:600; color:var(--text-primary);">{{ $l->letter_number }}</td>
                                        <td class="hidden-mobile">{{ date('d-m-Y', strtotime($l->date)) }}</td>
                                        <td>{{ $l->subject }}</td>
                                        <td class="hidden-mobile">{{ $l->destination ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="text-align:center; font-size:0.8rem; color:var(--text-muted); margin:1rem 0;">Belum ada dokumen persuratan yang tertaut dengan kegiatan ini.</p>
                    @endif
                </div>

            </div>
        @endif

    </div>
</div>
@endsection
