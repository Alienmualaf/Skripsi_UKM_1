@extends('layouts.app')

@section('title', 'Arsip Surat Baru')
@section('header', 'Arsip Surat Baru')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Arsipkan Surat Baru</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Catat informasi surat masuk or keluar dan unggah softcopy dokumen pendukungnya.</p>
</div>

<div class="card" style="max-width: 600px; padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-envelope-simple" style="color: var(--accent-color);"></i> Form Arsip Surat
    </h4>

    <form action="{{ route('pengurus.letters.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Jenis / Tipe Surat</label>
            <select name="type" class="form-control" required style="padding: 0.65rem;">
                <option value="">-- Pilih Jenis Surat --</option>
                @foreach($types as $t)
                    <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            @error('type') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nomor Surat</label>
            <input type="text" name="letter_number" class="form-control" required value="{{ old('letter_number') }}" placeholder="Contoh: 012/PSUP/VI/2026" style="padding: 0.65rem;">
            @error('letter_number') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Perihal / Subjek Surat</label>
            <input type="text" name="subject" class="form-control" required value="{{ old('subject') }}" placeholder="Contoh: Permohonan Peminjaman Ruang Latihan" style="padding: 0.65rem;">
            @error('subject') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tujuan / Asal Surat</label>
            <input type="text" name="destination" class="form-control" required value="{{ old('destination') }}" placeholder="Contoh: Rektorat Universitas Pancasila / Dekan Fakultas Teknik" style="padding: 0.65rem;">
            @error('destination') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tanggal Surat</label>
            <input type="date" name="date" class="form-control" required value="{{ old('date', date('Y-m-d')) }}" style="padding: 0.65rem;">
            @error('date') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Kategori Surat</label>
            <select name="related_to" id="related_to" class="form-control" required style="padding: 0.65rem;">
                <option value="Umum" {{ old('related_to') === 'Umum' ? 'selected' : '' }}>Umum</option>
                <option value="Program Kerja" {{ old('related_to') === 'Program Kerja' ? 'selected' : '' }}>Program Kerja</option>
            </select>
            @error('related_to') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4" id="program_select_wrapper" style="display: {{ old('related_to') === 'Program Kerja' ? 'block' : 'none' }};">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Program Kerja</label>
            <select name="program_id" class="form-control" style="padding: 0.65rem;">
                <option value="">-- Pilih Program Kerja --</option>
                @foreach($programs as $prog)
                    <option value="{{ $prog->id }}" {{ old('program_id') == $prog->id ? 'selected' : '' }}>
                        {{ $prog->name }} (s.d. {{ date('d-m-Y', strtotime($prog->end_date)) }})
                    </option>
                @endforeach
            </select>
            @error('program_id') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-6">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Unggah Berkas PDF (Wajib)</label>
            <input type="file" name="file_path" class="form-control" required accept="application/pdf" style="padding: 0.5rem;">
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Format: PDF (Maks. 5MB).</p>
            @error('file_path') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Simpan Surat</button>
            <a href="{{ route('pengurus.letters.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('related_to').addEventListener('change', function() {
        var wrapper = document.getElementById('program_select_wrapper');
        if (this.value === 'Program Kerja') {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
            wrapper.querySelector('select').value = '';
        }
    });
</script>
@endsection
