@extends('layouts.app')

@section('title', 'Laporan Program Kerja (LPJ)')
@section('header', 'LPJ - ' . $program->name)

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <a href="{{ route('pengurus.programs.show', $program->id) }}" style="display: inline-flex; align-items: center; gap: 0.35rem; color: var(--accent-color); text-decoration: none; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem;">
            <i class="ph ph-arrow-left"></i> Kembali ke Detail Program
        </a>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Laporan Pertanggungjawaban (LPJ)</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem;">Program: <strong>{{ $program->name }}</strong> | Divisi: {{ $program->division }}</p>
    </div>
    @if($report)
    <div style="display: flex; gap: 0.5rem;">
        <form action="{{ route('pengurus.programs.report.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?');" style="margin: 0;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" style="padding: 0.6rem 1rem; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-trash"></i> Hapus LPJ
            </button>
        </form>
    </div>
    @endif
</div>

<div style="display: grid; grid-template-columns: 1fr 360px; gap: 1.5rem; align-items: start;">
    <!-- Form LPJ -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-file-text" style="color: var(--accent-color);"></i>
            {{ $report ? 'Edit Laporan LPJ' : 'Buat Laporan LPJ Baru' }}
        </h4>

        <form action="{{ route('pengurus.programs.report.store', $program->id) }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Judul Laporan <span style="color: var(--danger-color);">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ $report->title ?? 'LPJ ' . $program->name }}" required>
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Ringkasan Eksekutif</label>
                <textarea name="executive_summary" class="form-control" rows="4" placeholder="Ringkasan singkat tentang keseluruhan program kerja...">{{ $report->executive_summary ?? '' }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Deskripsi Kegiatan</label>
                <textarea name="activities_description" class="form-control" rows="5" placeholder="Jelaskan secara rinci kegiatan-kegiatan yang telah dilaksanakan...">{{ $report->activities_description ?? '' }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Realisasi Anggaran</label>
                <textarea name="budget_realization" class="form-control" rows="4" placeholder="Rincian penggunaan anggaran yang telah direalisasikan...">{{ $report->budget_realization ?? '' }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Hambatan & Kendala</label>
                    <textarea name="obstacles" class="form-control" rows="4" placeholder="Kendala yang dihadapi selama pelaksanaan...">{{ $report->obstacles ?? '' }}</textarea>
                </div>
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Rekomendasi & Tindak Lanjut</label>
                    <textarea name="recommendations" class="form-control" rows="4" placeholder="Saran dan rekomendasi untuk kegiatan berikutnya...">{{ $report->recommendations ?? '' }}</textarea>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Anggaran Terealisasi (Rp)</label>
                    <input type="number" name="realized_budget" class="form-control" value="{{ $report->realized_budget ?? 0 }}" min="0" step="1000">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Status Laporan <span style="color: var(--danger-color);">*</span></label>
                    <select name="status" class="form-control" required>
                        @foreach(['Draft', 'Submitted', 'Approved'] as $s)
                            <option value="{{ $s }}" {{ ($report->status ?? 'Draft') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700; border-radius: 8px; font-size: 0.9375rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-floppy-disk"></i> Simpan Laporan LPJ
            </button>
        </form>
    </div>

    <!-- Ringkasan Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <div class="card" style="padding: 1.25rem;">
            <h5 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 0.875rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Info Program</h5>
            <div style="font-size: 0.875rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary);">Divisi</span>
                    <strong>{{ $program->division }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary);">Jenis</span>
                    <strong>{{ $program->activity_type }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary);">Anggaran</span>
                    <strong>Rp {{ number_format($program->budget, 0, ',', '.') }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary);">Status</span>
                    <strong>{{ $program->status }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary);">Progress</span>
                    <strong>{{ $program->progress }}%</strong>
                </div>
                @if($report)
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary);">Realisasi</span>
                    <strong>Rp {{ number_format($report->realized_budget, 0, ',', '.') }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-secondary);">Efisiensi</span>
                    <strong style="color: {{ $program->budget > 0 && $report->realized_budget <= $program->budget ? 'var(--success-color)' : 'var(--danger-color)' }};">
                        {{ $program->budget > 0 ? number_format((1 - $report->realized_budget / $program->budget) * 100, 1) . '%' : '-' }}
                    </strong>
                </div>
                @endif
            </div>
        </div>

        @if($report)
        <div class="card" style="padding: 1.25rem;">
            <h5 style="margin: 0 0 0.75rem 0; font-weight: 800; font-size: 0.875rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Info Laporan</h5>
            <div style="font-size: 0.8125rem; color: var(--text-secondary);">
                <p style="margin: 0 0 0.25rem;">Dibuat oleh: <strong style="color: var(--text-primary);">{{ $report->creator->name ?? 'Admin' }}</strong></p>
                <p style="margin: 0 0 0.25rem;">Terakhir diperbarui: <strong style="color: var(--text-primary);">{{ date('d M Y H:i', strtotime($report->updated_at)) }}</strong></p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
