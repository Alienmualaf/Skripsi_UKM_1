@extends('layouts.app')

@section('title', 'Cetak Laporan')
@section('header', 'Cetak Laporan')

@section('content')
<style>
    .report-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        align-items: stretch;
    }
    
    @media (max-width: 1200px) {
        .report-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 768px) {
        .report-grid { grid-template-columns: 1fr; }
    }

    .report-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        background: #fff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .report-card h4 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border-radius: 0.5rem;
        border: 1.5px solid var(--border-color);
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        border-color: var(--accent-color);
        outline: none;
    }

    .btn-group {
        display: flex;
        gap: 0.75rem;
        margin-top: auto;
        padding-top: 1rem;
    }

    .btn-report {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: filter 0.2s;
    }

    .lpj-section {
        grid-column: span 3;
        margin-top: 1rem;
    }

    @media (max-width: 1200px) {
        .lpj-section { grid-column: span 2; }
    }
    @media (max-width: 768px) {
        .lpj-section { grid-column: span 1; }
    }

    .lpj-card {
        border-top: 5px solid var(--warning-color);
    }
</style>

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
