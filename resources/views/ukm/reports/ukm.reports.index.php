@extends('layouts.app')

@section('title', 'Cetak Laporan')
@section('header', 'Cetak Laporan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ukm.css') }}">

<div class="report-grid">
    <!-- Laporan Kegiatan -->
    <div class="report-card">
        <div>
            <h4 style="color: var(--accent-color);"><i class="ph-fill ph-calendar"></i> Laporan Kegiatan</h4>
            <form action="/ukm/reports/export" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="type" value="events">
                <div class="form-group">
                    <label class="form-label">Pilih Agenda (Wajib)</label>
                    <select name="event_id" class="form-control" required>
                        <option value="">-- Pilih Agenda --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                    <small class="text-secondary" style="display: block; margin-top: 0.5rem; font-size: 0.75rem;">
                        Laporan kegiatan sekarang fokus per-agenda untuk detail yang lebih akurat.
                    </small>
                </div>
                <div class="btn-group">
                    <button type="submit" name="mode" value="preview" class="btn-report" style="background: var(--bg-color); border: 1.5px solid var(--border-color); color: var(--text-primary);"><i class="ph ph-eye"></i> Preview</button>
                    <button type="submit" name="mode" value="download" class="btn-report" style="background: var(--accent-color); color: #fff;"><i class="ph ph-download-simple"></i> Unduh</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Laporan Rekrutmen & Anggota -->
    <div class="report-card">
        <div>
            <h4 style="color: var(--primary-color);"><i class="ph-fill ph-users"></i> Laporan Rekrutmen</h4>
            <form action="/ukm/reports/export" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="type" value="memberships">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="btn-group">
                    <button type="submit" name="mode" value="preview" class="btn-report" style="background: var(--bg-color); border: 1.5px solid var(--border-color); color: var(--text-primary);"><i class="ph ph-eye"></i> Preview</button>
                    <button type="submit" name="mode" value="download" class="btn-report" style="background: var(--primary-color); color: #000000ff;"><i class="ph ph-download-simple"></i> Unduh</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Laporan Keuangan -->
    <div class="report-card">
        <div>
            <h4 style="color: #10b981;"><i class="ph-fill ph-money"></i> Laporan Keuangan</h4>
            <form action="/ukm/reports/export" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="type" value="finances">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control">
                </div>
                <div class="btn-group">
                    <button type="submit" name="mode" value="preview" class="btn-report" style="background: var(--bg-color); border: 1.5px solid var(--border-color); color: var(--text-primary);"><i class="ph ph-eye"></i> Preview</button>
                    <button type="submit" name="mode" value="download" class="btn-report" style="background: #10b981; color: #fff;"><i class="ph ph-download-simple"></i> Unduh</button>
                </div>
            </form>
        </div>
    </div>

    <!-- LPJ Keseluruhan -->
    <div class="lpj-section">
        <div class="report-card lpj-card">
            <div style="display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 300px;">
                    <h4 style="color: var(--warning-color);"><i class="ph-fill ph-book-bookmark"></i> LPJ Program Kerja</h4>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6; margin-bottom: 0;">
                        Menghasilkan laporan pertanggungjawaban komprehensif yang menggabungkan seluruh data kegiatan, 
                        rincian keuangan per periode, daftar anggota aktif, hingga lampiran surat-surat resmi yang telah disetujui.
                    </p>
                </div>
                <div style="flex: 1; min-width: 300px;">
                    <form action="/ukm/reports/export" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="type" value="lpj">
                        <div class="form-group">
                            <label class="form-label">Pilih Periode LPJ (Wajib)</label>
                            <div style="display: flex; gap: 0.5rem;">
                                <input type="date" name="start_date" class="form-control" required>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button type="submit" name="mode" value="preview" class="btn-report" style="background: var(--bg-color); border: 1.5px solid var(--border-color); color: var(--text-primary);"><i class="ph ph-eye"></i> Preview LPJ</button>
                            <button type="submit" name="mode" value="download" class="btn-report" style="background: var(--warning-color); color: #fff;"><i class="ph ph-download-simple"></i> Unduh LPJ Lengkap</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
