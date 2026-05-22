@extends('layouts.app')

@section('title', 'Kelola Absensi: ' . $session->title)
@section('header', 'Absensi Pelatih & Staf')

@section('content')
<div class="card mb-4 animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h4 style="margin: 0; font-weight: 700;">{{ $session->title }}</h4>
            <p style="margin: 0.25rem 0 0 0; color: var(--text-secondary); font-size: 0.875rem;">
                {{ $session->date->format('d M Y') }} • Agenda: {{ $event->title }}
            </p>
        </div>
        <div>
            <a href="/ukm/classroom/{{ $event->id }}?tab=attendances" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: 12px; font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card mb-4 animate-fade-in" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.5rem; border-radius: 12px; font-weight: 600;">
        <i class="ph-fill ph-warning-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        Terjadi kesalahan: {{ $errors->first() }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr; gap: 2rem; align-items: start;" class="coach-attendance-grid">
    <!-- KOLOM KIRI: FORM ABSEN BARU (KONSISTEN DENGAN ELEMEN FORM ANGGOTA) -->
    <div class="card animate-fade-in" style="border: 1px solid var(--border-color); padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-color);">
            <i class="ph ph-user-plus" style="color: var(--accent-color);"></i> Catat Kehadiran Baru
        </h3>

        <form action="{{ route('coach-attendances.session-store', $session->id) }}" method="POST" id="attendanceForm">
            @csrf

            <!-- KATEGORI SELECTOR -->
            <div class="form-group mb-4">
                <label style="font-weight: 700; display: block; margin-bottom: 0.5rem;">Kategori Pelatih / Staf</label>
                <select name="category" class="form-control" style="border-radius: 12px; height: 48px; border: 1.5px solid var(--border-color);" id="select-category" onchange="toggleCategoryFields(this.value)" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="pelatih">Pelatih</option>
                    <option value="bph">BPH</option>
                    <option value="lainnya">Lainnya / Tamu</option>
                </select>
            </div>

            <!-- KONDISIONAL FIELD: PELATIH -->
            <div class="form-group mb-4" id="group-pelatih" style="display: none;">
                <label style="font-weight: 700; display: block; margin-bottom: 0.5rem;">Pilih Pelatih</label>
                <select name="coach_id" class="form-control" style="border-radius: 12px; height: 48px; border: 1.5px solid var(--border-color);" id="select-pelatih" disabled>
                    <option value="" disabled {{ $coaches->count() !== 1 ? 'selected' : '' }}>-- Pilih Pelatih --</option>
                    @foreach($coaches as $c)
                    <option value="{{ $c->id }}" {{ $coaches->count() === 1 ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @if($coaches->count() === 1)
                    <p style="font-size: 0.75rem; color: var(--success-color); margin-top: 0.25rem; font-weight: 600;">
                        <i class="ph-fill ph-check-circle"></i> Hanya ada 1 Pelatih, otomatis terpilih!
                    </p>
                @elseif($coaches->isEmpty())
                    <p style="font-size: 0.75rem; color: var(--danger-color); margin-top: 0.25rem; font-weight: 600;">
                        <i class="ph ph-warning-circle"></i> Tidak ada pelatih terdaftar. Silakan tambahkan di menu Kelola Pelatih.
                    </p>
                @endif
            </div>

            <!-- KONDISIONAL FIELD: BPH -->
            <div class="form-group mb-4" id="group-bph" style="display: none;">
                <label style="font-weight: 700; display: block; margin-bottom: 0.5rem;">Pilih BPH</label>
                <select name="coach_id" class="form-control" style="border-radius: 12px; height: 48px; border: 1.5px solid var(--border-color);" id="select-bph" disabled>
                    <option value="" disabled selected>-- Pilih BPH --</option>
                    @foreach($bphs as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
                @if($bphs->isEmpty())
                    <p style="font-size: 0.75rem; color: var(--danger-color); margin-top: 0.25rem; font-weight: 600;">
                        <i class="ph ph-warning-circle"></i> Tidak ada staf BPH terdaftar.
                    </p>
                @endif
            </div>

            <!-- KONDISIONAL FIELD: LAINNYA / MANUAL -->
            <div id="group-lainnya" style="display: none;">
                <div class="form-group mb-4">
                    <label style="font-weight: 700; display: block; margin-bottom: 0.5rem;">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama pelatih tamu / staf" style="border-radius: 12px; height: 48px; border: 1.5px solid var(--border-color);" id="input-name" disabled>
                </div>
                <div class="form-group mb-4">
                    <label style="font-weight: 700; display: block; margin-bottom: 0.5rem;">Keterangan / Notes <span style="color: var(--danger-color);">*</span></label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Wajib Diisi (contoh: Mengisi materi latihan fisik tambahan)" style="border-radius: 12px; border: 1.5px solid var(--border-color);" id="input-notes" disabled></textarea>
                </div>
            </div>

            <!-- STATUS KEHADIRAN (100% KONSISTEN DENGAN LAYOUT RADIO ABSENSI ANGGOTA) -->
            <div class="form-group mb-5">
                <label style="font-weight: 700; display: block; margin-bottom: 0.5rem;">Status Kehadiran</label>
                <div style="display: flex; gap: 1.25rem; align-items: center; padding: 0.5rem 0;">
                    @foreach([
                        'hadir' => ['label' => 'Hadir', 'color' => 'var(--success-color)'],
                        'izin' => ['label' => 'Izin', 'color' => 'var(--warning-color)'],
                        'tidak hadir' => ['label' => 'Tidak hadir', 'color' => 'var(--danger-color)']
                    ] as $val => $cfg)
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 0.4rem; font-weight: 600; font-size: 0.875rem;">
                        <input type="radio" name="status" value="{{ $val }}" required
                               style="width: 1.1rem; height: 1.1rem; accent-color: {{ $cfg['color'] }};">
                        {{ $cfg['label'] }}
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px; border-radius: 12px; font-weight: 800; font-size: 1rem; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                <i class="ph ph-floppy-disk"></i> Simpan Absensi
            </button>
        </form>
    </div>

    <!-- KOLOM KANAN: DAFTAR ABSENSI TERISI -->
    <div class="card animate-fade-in" style="border: 1px solid var(--border-color); padding: 1.5rem; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-color);">
            <i class="ph ph-article" style="color: var(--accent-color);"></i> Log Kehadiran Pelatih & Staf
        </h3>

        <div class="table-wrapper">
            <table class="table" style="vertical-align: middle;">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-color);">
                            {{ $att->coach ? $att->coach->name : $att->name }}
                        </td>
                        <td>
                            <span class="badge" style="text-transform: uppercase; font-size: 0.7rem; font-weight: 700; background: var(--accent-light); color: var(--accent-color);">
                                {{ $att->category }}
                            </span>
                        </td>
                        <td>
                            @if($att->status === 'hadir')
                                <span class="badge" style="background: #e6fbf1; color: #03a85a; font-weight: 700;">Hadir</span>
                            @elseif($att->status === 'izin')
                                <span class="badge" style="background: #fff8e6; color: #f59e0b; font-weight: 700;">Izin</span>
                            @else
                                <span class="badge" style="background: #fef2f2; color: #dc2626; font-weight: 700;">Tidak hadir</span>
                            @endif
                        </td>
                        <td style="font-size: 0.8125rem; color: var(--text-secondary); max-width: 150px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            {{ $att->notes ?: '-' }}
                        </td>
                        <td>
                            <div style="display: flex; justify-content: center;">
                                <form action="{{ route('coach-attendances.destroy', $att->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat absen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background: #fee2e2; color: #dc2626; padding: 0.4rem 0.6rem; border-radius: 8px; font-size: 0.75rem; border: none; cursor: pointer; transition: all 0.2s;">
                                        <i class="ph ph-trash" style="font-size: 0.9rem;"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-secondary); padding: 4rem;">
                            <i class="ph ph-users-three" style="font-size: 3rem; opacity: 0.2;"></i>
                            <p style="margin-top: 1rem;">Belum ada pelatih/staf yang diabsen.</p>
                            <p style="font-size: 0.8125rem; opacity: 0.8;">Gunakan form di sebelah kiri untuk mencatat absensi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Styling layout grid */
    @media (min-width: 992px) {
        .coach-attendance-grid {
            grid-template-columns: 4fr 6fr;
        }
    }
</style>

<script>
    // Toggle active classes and validations on category selection
    function toggleCategoryFields(category) {
        // Toggle dropdowns & inputs
        const pGroup = document.getElementById('group-pelatih');
        const bGroup = document.getElementById('group-bph');
        const lGroup = document.getElementById('group-lainnya');
        
        const pSelect = document.getElementById('select-pelatih');
        const bSelect = document.getElementById('select-bph');
        const lName = document.getElementById('input-name');
        const lNotes = document.getElementById('input-notes');

        if (category === 'pelatih') {
            pGroup.style.display = 'block';
            pSelect.disabled = false;
            pSelect.required = true;

            bGroup.style.display = 'none';
            bSelect.disabled = true;
            bSelect.required = false;

            lGroup.style.display = 'none';
            lName.disabled = true;
            lName.required = false;
            lNotes.disabled = true;
            lNotes.required = false;
        } else if (category === 'bph') {
            pGroup.style.display = 'none';
            pSelect.disabled = true;
            pSelect.required = false;

            bGroup.style.display = 'block';
            bSelect.disabled = false;
            bSelect.required = true;

            lGroup.style.display = 'none';
            lName.disabled = true;
            lName.required = false;
            lNotes.disabled = true;
            lNotes.required = false;
        } else if (category === 'lainnya') {
            pGroup.style.display = 'none';
            pSelect.disabled = true;
            pSelect.required = false;

            bGroup.style.display = 'none';
            bSelect.disabled = true;
            bSelect.required = false;

            lGroup.style.display = 'block';
            lName.disabled = false;
            lName.required = true;
            lNotes.disabled = false;
            lNotes.required = true; // notes is strictly required for Lainnya!
        } else {
            pGroup.style.display = 'none';
            pSelect.disabled = true;
            pSelect.required = false;

            bGroup.style.display = 'none';
            bSelect.disabled = true;
            bSelect.required = false;

            lGroup.style.display = 'none';
            lName.disabled = true;
            lName.required = false;
            lNotes.disabled = true;
            lNotes.required = false;
        }
    }

    // Set listener on page load to initialize styled classes
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Category selections
        const categorySelect = document.getElementById('select-category');
        if (categorySelect && categorySelect.value) {
            toggleCategoryFields(categorySelect.value);
        }
    });
</script>
@endsection
