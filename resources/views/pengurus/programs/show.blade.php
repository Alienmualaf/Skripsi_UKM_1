@extends('layouts.app')

@section('title', 'Detail Program Kerja')
@section('header', $program->name)

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <a href="{{ route('pengurus.programs.index') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; color: var(--accent-color); text-decoration: none; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem;">
            <i class="ph ph-arrow-left"></i> Kembali ke Program Kerja
        </a>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">{{ $program->name }}</h3>
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            @php
                $typeColors = ['Event' => '#0ea5e9', 'Competition' => '#f59e0b', 'Performance' => '#10b981'];
                $typeColor = $typeColors[$program->activity_type] ?? 'var(--accent-color)';
            @endphp
            <span class="badge" style="background: {{ $typeColor }}22; color: {{ $typeColor }}; font-weight: 700; border: 1px solid {{ $typeColor }}44;">
                <i class="ph ph-{{ $program->activity_type === 'Performance' ? 'microphone-stage' : ($program->activity_type === 'Competition' ? 'trophy' : 'calendar') }}"></i>
                {{ $program->activity_type }}
            </span>
            @if($program->status === 'Selesai')
                <span class="badge" style="background: rgba(16,185,129,0.1); color: var(--success-color); font-weight: bold;">Selesai</span>
            @elseif($program->status === 'Berjalan')
                <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: bold;">Berjalan</span>
            @else
                <span class="badge" style="background: #fff8e6; color: #f59e0b; font-weight: bold;">Perencanaan</span>
            @endif
        </div>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('pengurus.programs.edit', $program->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.6rem 1rem; font-weight: 700; border-radius: 8px; text-decoration: none; color: var(--text-primary); display: inline-flex; align-items: center; gap: 0.35rem;">
            <i class="ph ph-pencil-simple"></i> Edit
        </a>
        @if($program->report)
            <a href="{{ route('ukm.reports.kegiatan', $program->id) }}" class="btn btn-primary" style="padding: 0.6rem 1rem; font-weight: 700; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-file-text"></i> Lihat LPJ
            </a>
        @else
            @if(!false)
            <a href="{{ route('ukm.reports.kegiatan', $program->id) }}" class="btn btn-primary" style="padding: 0.65rem 1rem; font-weight: 700; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-file-text"></i> Buat LPJ
            </a>
            @endif
        @endif
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Detail Info -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-info" style="color: var(--accent-color);"></i> Informasi Program
        </h4>
        <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
            <tr>
                <td style="padding: 0.4rem 0; color: var(--text-secondary); width: 45%;">Jenis Kegiatan</td>
                <td style="padding: 0.4rem 0; font-weight: 600; color: {{ $typeColor }};">{{ $program->activity_type }}</td>
            </tr>
            @if($program->activity_type === 'Event' && $program->event_category)
            <tr>
                <td style="padding: 0.4rem 0; color: var(--text-secondary);">Kategori Event</td>
                <td style="padding: 0.4rem 0; font-weight: 600; color: var(--text-primary);">{{ $program->event_category }}</td>
            </tr>
            @endif
            <tr>
                <td style="padding: 0.4rem 0; color: var(--text-secondary);">Lokasi (Venue)</td>
                <td style="padding: 0.4rem 0; font-weight: 600; color: var(--text-primary);">{{ $program->venue ?? 'Belum ditentukan' }}</td>
            </tr>
            <tr>
                <td style="padding: 0.4rem 0; color: var(--text-secondary);">Tanggal Mulai</td>
                <td style="padding: 0.4rem 0; font-weight: 600;">{{ date('d M Y', strtotime($program->start_date)) }}</td>
            </tr>
            <tr>
                <td style="padding: 0.4rem 0; color: var(--text-secondary);">Tanggal Selesai</td>
                <td style="padding: 0.4rem 0; font-weight: 600;">{{ date('d M Y', strtotime($program->end_date)) }}</td>
            </tr>
            @if($program->target_date)
            <tr>
                <td style="padding: 0.4rem 0; color: var(--text-secondary);">Target Tanggal</td>
                <td style="padding: 0.4rem 0; font-weight: 600;">{{ date('d M Y', strtotime($program->target_date)) }}</td>
            </tr>
            @endif
            @if($program->pic)
            <tr>
                <td style="padding: 0.4rem 0; color: var(--text-secondary);">Penanggung Jawab</td>
                <td style="padding: 0.4rem 0; font-weight: 600;">{{ $program->pic }}</td>
            </tr>
            @endif
        </table>

        @if($program->description)
        <hr style="margin: 1rem 0; border: none; border-top: 1px solid var(--border-color);">
        <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0; line-height: 1.6;">{{ $program->description }}</p>
        @endif
    </div>

    <!-- LPJ Status -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-file-text" style="color: var(--accent-color);"></i> Status LPJ
        </h4>
        @if($program->report)
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">{{ $program->report->title }}</p>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('ukm.reports.kegiatan', $program->id) }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 700; border-radius: 8px;">
                    <i class="ph ph-eye"></i> Lihat LPJ
                </a>
                <a href="{{ route('ukm.reports.kegiatan.print', $program->id) }}" target="_blank" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 700; border-radius: 8px; color: var(--text-primary);">
                    <i class="ph ph-download-simple"></i> Download LPJ
                </a>
            </div>
        @else
            <div style="text-align: center; padding: 1.5rem 0;">
                <i class="ph ph-file-x" style="font-size: 2.5rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;"></i>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: {{ false ? '0' : '1rem' }};">Laporan LPJ belum dibuat{{ false ? ' oleh Pengurus' : '' }}</p>
                @if(!false)
                <a href="{{ route('ukm.reports.kegiatan', $program->id) }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 700; border-radius: 8px;">
                    <i class="ph ph-plus"></i> Buat LPJ
                </a>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- Performance Section (only if activity_type = Performance or Competition) --}}
@if($program->activity_type === 'Performance' || $program->activity_type === 'Competition')
<div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem; border: 2px solid rgba(16,185,129,0.3);">
    <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-microphone-stage" style="color: #10b981;"></i> Data Penampilan / Lomba
    </h4>

    @if($program->performance)
        @php $perf = $program->performance; @endphp
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin: 0 0 0.25rem;">Judul</p>
                <p style="font-size: 0.9375rem; font-weight: 700; color: var(--text-primary); margin: 0;">{{ $perf->title }}</p>
            </div>
            <div>
                <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin: 0 0 0.25rem;">Venue</p>
                <p style="font-size: 0.9375rem; font-weight: 700; color: var(--text-primary); margin: 0;">{{ $perf->venue }}</p>
            </div>
            <div>
                <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin: 0 0 0.25rem;">Tanggal</p>
                <p style="font-size: 0.9375rem; font-weight: 700; color: var(--text-primary); margin: 0;">{{ date('d M Y', strtotime($perf->performance_date)) }}</p>
            </div>
            <div>
                <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin: 0 0 0.25rem;">Status</p>
                <span class="badge" style="font-weight: 700; background: {{ $perf->status === 'Selesai' ? 'rgba(16,185,129,0.1)' : 'rgba(16,185,129,0.1)' }}; color: #10b981;">{{ $perf->status }}</span>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="{{ route('pengurus.programs.performance.classroom.show', [$program->id, $perf->id]) }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none; padding: 0.6rem 1.1rem; font-weight: 700; border-radius: 8px;">
                <i class="ph ph-chalkboard"></i> Buka Pusat Latihan
            </a>
            <button onclick="document.getElementById('editPerfModal').style.display='flex'" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.6rem 1rem; font-weight: 700; border-radius: 8px; color: var(--text-primary); cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-pencil-simple"></i> Edit Penampilan / Lomba
            </button>
            <form action="{{ route('pengurus.programs.performance.destroy', [$program->id, $perf->id]) }}" method="POST" onsubmit="return confirm('Hapus data penampilan / lomba ini?');" style="display: inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" style="padding: 0.6rem 1rem; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="ph ph-trash"></i> Hapus
                </button>
            </form>
        </div>

        {{-- Edit Performance Modal --}}
        <div id="editPerfModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div class="card" style="width: 100%; max-width: 540px; padding: 2rem; margin: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h4 style="margin: 0; font-weight: 800;">Edit Data Penampilan / Lomba</h4>
                    <button onclick="document.getElementById('editPerfModal').style.display='none'" style="background: none; border: none; cursor: pointer; font-size: 1.25rem; color: var(--text-secondary);">&times;</button>
                </div>
                <form action="{{ route('pengurus.programs.performance.update', [$program->id, $perf->id]) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Judul Penampilan / Lomba</label>
                        <input type="text" name="title" class="form-control" value="{{ $perf->title }}" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Tanggal</label>
                            <input type="date" name="performance_date" class="form-control" value="{{ $perf->performance_date }}" required>
                        </div>
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Waktu</label>
                            <input type="time" name="performance_time" class="form-control" value="{{ $perf->performance_time }}">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Venue / Lokasi</label>
                        <input type="text" name="venue" class="form-control" value="{{ $perf->venue }}" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Status</label>
                            <select name="status" class="form-control" required>
                                @foreach(['Persiapan','Berlangsung','Selesai'] as $s)
                                    <option value="{{ $s }}" {{ $perf->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ $perf->description }}</textarea>
                    </div>
                    <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                        <button type="button" onclick="document.getElementById('editPerfModal').style.display='none'" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.6rem 1rem; font-weight: 700; border-radius: 8px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 8px;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    @else
        {{-- Create performance form --}}
        @if(false)
            <div style="text-align: center; padding: 1.5rem 0;">
                <i class="ph ph-microphone-stage" style="font-size: 2.5rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;"></i>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0;">Belum ada data penampilan / lomba. Data penampilan / lomba harus ditambahkan oleh Pengurus.</p>
            </div>
        @else
            <div style="text-align: center; padding: 1rem 0 0.5rem;">
                <i class="ph ph-microphone-stage" style="font-size: 2.5rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;"></i>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.25rem;">Belum ada data penampilan / lomba. Isi data berikut untuk membuat penampilan / lomba.</p>
            </div>
            <form action="{{ route('pengurus.programs.performance.store', $program->id) }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Judul Penampilan / Lomba <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Nama acara/konser/lomba" required>
                    </div>
                    <div>
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Venue <span style="color: var(--danger-color);">*</span></label>
                        <input type="text" name="venue" class="form-control" placeholder="Lokasi penampilan/lomba" required>
                    </div>
                    <div>
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Tanggal Penampilan / Lomba <span style="color: var(--danger-color);">*</span></label>
                        <input type="date" name="performance_date" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Waktu</label>
                        <input type="time" name="performance_time" class="form-control">
                    </div>
                    <div>
                        <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Status <span style="color: var(--danger-color);">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="Persiapan">Persiapan</option>
                            <option value="Berlangsung">Berlangsung</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" style="font-weight: 700; font-size: 0.8125rem;">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat mengenai penampilan/lomba ini..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.5rem; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="ph ph-plus"></i> Simpan Data Penampilan / Lomba
                </button>
            </form>
        @endif
    @endif
</div>
@endif

@if(session('success'))
    <div style="position: fixed; bottom: 1.5rem; right: 1.5rem; background: var(--success-color); color: #fff; padding: 1rem 1.5rem; border-radius: 10px; font-weight: 700; font-size: 0.875rem; box-shadow: 0 4px 12px rgba(16,185,129,0.35); z-index: 9999; display: flex; align-items: center; gap: 0.5rem; animation: slideUp 0.3s ease;">
        <i class="ph ph-check-circle"></i> {{ session('success') }}
    </div>
@endif
@endsection
