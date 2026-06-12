@extends('layouts.app')

@section('title', 'Monitor Gigs & Presensi')
@section('header', 'Monitoring Gigs & Presensi')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Override Data: Job, Persuratan & Presensi</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Pengawasan seluruh tawaran menyanyi/tampil (Gigs), persuratan masuk/keluar, dan lembar daftar hadir presensi latihan anggota UKM PSUP.</p>
</div>

<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- 1. DATA JOB / PENAMPILAN -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-microphone-stage" style="color: var(--accent-color);"></i> Database Job Penampilan / Gigs
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Acara</th>
                        <th>Tanggal Tampil</th>
                        <th>Lokasi</th>
                        <th>Fee / Bayaran</th>
                        <th>Status</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $j)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $j->title }}</td>
                        <td>{{ $j->date ? $j->date->format('d M Y') : '-' }}</td>
                        <td>{{ $j->location ?? '-' }}</td>
                        <td style="font-weight: bold; color: var(--accent-color);">Rp{{ number_format($j->fee ?? 0, 0, ',', '.') }}</td>
                        <td>
                            @if($j->status === 'Confirmed')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16,185,129,0.15); font-weight: bold;">Confirmed</span>
                            @else
                                <span class="badge" style="background: #fff8e6; color: #f59e0b; border: 1px solid rgba(245,158,11,0.15); font-weight: bold;">{{ $j->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                                <a href="{{ route('pengurus.jobs.edit', $j->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'job', 'id' => $j->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa job ini?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Tidak ada data job penampilan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $jobs->appends(['jobs_page' => $jobs->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

    <!-- 2. DATA PERSURATAN -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-envelope" style="color: var(--accent-color);"></i> Database Surat Masuk / Keluar
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>Perihal / Judul</th>
                        <th>Kategori</th>
                        <th>Asal / Tujuan</th>
                        <th>Tanggal Surat</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letters as $l)
                    <tr>
                        <td style="font-family: monospace; font-weight: 700;">{{ $l->letter_number }}</td>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $l->subject }}</td>
                        <td>
                            @if($l->type === 'Incoming')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16,185,129,0.15); font-weight: bold;">Masuk</span>
                            @else
                                <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.15); font-weight: bold;">Keluar</span>
                            @endif
                        </td>
                        <td>{{ $l->sender_receiver ?? '-' }}</td>
                        <td style="font-size: 0.8125rem; color: var(--text-secondary);">{{ $l->date ? $l->date->format('d-m-Y') : '-' }}</td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                                <a href="{{ route('pengurus.letters.edit', $l->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'letter', 'id' => $l->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa surat ini? File arsip terkait di disk juga akan dilepas.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Tidak ada data persuratan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $letters->appends(['letters_page' => $letters->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

    <!-- 3. DATA PRESENSI LATIHAN -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-check-square" style="color: var(--accent-color);"></i> Database Sesi Presensi Latihan
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sesi Pertemuan / Agenda</th>
                        <th>Kode Sesi</th>
                        <th>Status Presensi</th>
                        <th>Anggota Terdaftar</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $a)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $a->agenda ? $a->agenda->title : 'Pertemuan Latihan' }}</td>
                        <td style="font-family: monospace; font-weight: bold;">{{ $a->session_code ?? '-' }}</td>
                        <td>
                            @if($a->is_active)
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16,185,129,0.15); font-weight: bold;">Sesi Dibuka</span>
                            @else
                                <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: bold;">Ditutup</span>
                            @endif
                        </td>
                        <td style="font-weight: bold; color: var(--accent-color);">{{ $a->details_count }} anggota hadir/absen</td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                                <a href="{{ route('pengurus.attendances.edit', $a->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'attendance', 'id' => $a->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa sesi presensi ini? Seluruh lembar absen anggota di sesi ini akan hangus.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Tidak ada data sesi presensi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $attendances->appends(['attendances_page' => $attendances->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

</div>
@endsection
