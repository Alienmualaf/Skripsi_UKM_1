@extends('layouts.app')

@section('title', 'Tambah Proker & Job')
@section('header', 'Tambah Proker & Job PSUP')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('pengurus.programs.index') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; color: var(--accent-color); text-decoration: none; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem;">
        <i class="ph ph-arrow-left"></i> Kembali ke Program & Penampilan
    </a>
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Tambah Program Kerja & Job Baru</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Masukkan rencana program kerja kepengurusan atau penugasan job penampilan PSUP.</p>
</div>

@if($errors->any())
    <div class="card mb-4" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.5rem; border-radius: var(--radius-md);">
        <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.875rem;">
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="card" style="max-width: 680px; padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-target" style="color: var(--accent-color);"></i> Form Program Kerja & Job Baru
    </h4>

    <form action="{{ route('pengurus.programs.store') }}" method="POST">
        @csrf

        <div class="form-group" style="margin-bottom: 1.25rem;">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Program / Penampilan / Job <span style="color: var(--danger-color);">*</span></label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Contoh: Konser Tahunan PSUP 2026 atau Pengisian Acara Wisuda">
        </div>

        <div class="form-group" style="margin-bottom: 1.25rem;">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Jenis Kegiatan <span style="color: var(--danger-color);">*</span></label>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                @php
                    $actTypes = [
                        'Event' => ['icon' => 'ph-calendar-star', 'color' => '#0ea5e9', 'desc' => 'Event / Acara Resmi'],
                        'Competition' => ['icon' => 'ph-trophy', 'color' => '#f59e0b', 'desc' => 'Perlombaan / Kompetisi'],
                        'Performance' => ['icon' => 'ph-microphone-stage', 'color' => '#10b981', 'desc' => 'Penampilan / Job Vokal'],
                    ];
                @endphp
                @foreach($actTypes as $type => $cfg)
                <label style="cursor: pointer; border: 2px solid var(--border-color); border-radius: 10px; padding: 0.85rem 0.5rem; text-align: center; transition: all 0.15s;"
                       onclick="this.parentElement.querySelectorAll('label').forEach(l => l.style.borderColor = 'var(--border-color)'); this.style.borderColor = '{{ $cfg['color'] }}';">
                    <input type="radio" name="activity_type" value="{{ $type }}" {{ old('activity_type', 'Event') === $type ? 'checked' : '' }} style="display: none;">
                    <i class="ph {{ $cfg['icon'] }}" style="font-size: 1.5rem; color: {{ $cfg['color'] }}; display: block; margin-bottom: 0.25rem;"></i>
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-primary);">{{ $type === 'Performance' ? 'Penampilan & Job' : $type }}</span>
                    <span style="font-size: 0.7rem; color: var(--text-muted); display: block;">{{ $cfg['desc'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Event Category Selection (Only displayed when Event is chosen) -->
        <div id="event-category-container" style="display: none; margin-bottom: 1.25rem;">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Kategori Event <span style="color: var(--danger-color);">*</span></label>
            <div style="display: flex; gap: 1.5rem; padding: 0.5rem 0;">
                <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 600; color: var(--text-primary);">
                    <input type="radio" name="event_category" value="Internal" {{ old('event_category', 'Internal') === 'Internal' ? 'checked' : '' }} style="width: auto; margin: 0; cursor: pointer;">
                    Internal
                </label>
                <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 600; color: var(--text-primary);">
                    <input type="radio" name="event_category" value="External" {{ old('event_category') === 'External' ? 'checked' : '' }} style="width: auto; margin: 0; cursor: pointer;">
                    Eksternal
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.25rem;">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Lokasi / Tempat Pelaksanaan (Venue) <span style="color: var(--danger-color);">*</span></label>
            <input type="text" name="venue" class="form-control" required value="{{ old('venue') }}" placeholder="Contoh: GSG Universitas Pancasila, Zoom Meeting, dll.">
        </div>

        <div class="form-group" style="margin-bottom: 1.25rem;">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi & Gambaran Kegiatan</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan tujuan dan gambaran program kerja / penampilan ini...">{{ old('description') }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tanggal Mulai Pelaksanaan <span style="color: var(--danger-color);">*</span></label>
                <input type="date" id="start_date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
            </div>
            <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tanggal Selesai Pelaksanaan <span style="color: var(--danger-color);">*</span></label>
                <input type="date" id="end_date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
            </div>
        </div>

        <div id="performance-time-container" style="display: none; margin-bottom: 1.25rem;">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Jam Pelaksanaan <span style="color: var(--danger-color);">*</span></label>
            <input type="time" name="performance_time" class="form-control" value="{{ old('performance_time', '19:00') }}" style="max-width: 200px;">
            <span style="font-size: 0.75rem; color: var(--text-secondary); display: block; margin-top: 0.25rem;">Waktu atau jam pelaksanaan penampilan / lomba.</span>
        </div>

        <div class="form-group" style="margin-bottom: 1.25rem;">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Penanggung Jawab (PJ) Kegiatan</label>
            <input type="text" name="pic" class="form-control" value="{{ old('pic') }}" placeholder="Nama Penanggung Jawab">
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 700; font-size: 0.875rem; color: var(--text-primary);">
                <input type="checkbox" name="show_on_landing" value="1" {{ old('show_on_landing') ? 'checked' : '' }} style="width: auto; margin: 0; cursor: pointer;">
                Tampilkan di Landing Page
            </label>
            <span style="font-size: 0.75rem; color: var(--text-secondary); display: block; margin-left: 1.5rem;">Jika dicentang, program kerja ini akan ditampilkan pada section "Penampilan & Kegiatan" di landing page publik.</span>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.5rem; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-floppy-disk"></i> Simpan Program Kerja / Job
            </button>
            <a href="{{ route('pengurus.programs.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 8px; text-decoration: none; color: var(--text-primary);">Batal</a>
        </div>
    </form>
</div>

<script>
function toggleEventFields() {
    const categoryContainer = document.getElementById('event-category-container');
    const timeContainer = document.getElementById('performance-time-container');
    const checkedRadio = document.querySelector('input[name="activity_type"]:checked');
    
    if (checkedRadio) {
        if (checkedRadio.value === 'Event') {
            categoryContainer.style.display = 'block';
            categoryContainer.querySelectorAll('input').forEach(el => el.required = true);
            
            timeContainer.style.display = 'none';
            timeContainer.querySelector('input').required = false;
        } else if (checkedRadio.value === 'Performance' || checkedRadio.value === 'Competition') {
            categoryContainer.style.display = 'none';
            categoryContainer.querySelectorAll('input').forEach(el => el.required = false);
            
            timeContainer.style.display = 'block';
            timeContainer.querySelector('input').required = true;
        } else {
            categoryContainer.style.display = 'none';
            categoryContainer.querySelectorAll('input').forEach(el => el.required = false);
            
            timeContainer.style.display = 'none';
            timeContainer.querySelector('input').required = false;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const typeColors = { 'Event': '#0ea5e9', 'Competition': '#f59e0b', 'Performance': '#10b981' };
    document.querySelectorAll('input[name="activity_type"]').forEach(radio => {
        if (radio.checked) {
            radio.closest('label').style.borderColor = typeColors[radio.value] || 'var(--accent-color)';
        }
        radio.addEventListener('change', () => {
            document.querySelectorAll('input[name="activity_type"]').forEach(r => {
                r.closest('label').style.borderColor = 'var(--border-color)';
            });
            radio.closest('label').style.borderColor = typeColors[radio.value] || 'var(--accent-color)';
            toggleEventFields();
        });
    });

    toggleEventFields();

    // Client-side date validation
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    if (startDateInput && endDateInput) {
        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }
        startDateInput.addEventListener('change', () => {
            endDateInput.min = startDateInput.value;
            if (endDateInput.value && endDateInput.value < startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        });
    }
});
</script>
@endsection
