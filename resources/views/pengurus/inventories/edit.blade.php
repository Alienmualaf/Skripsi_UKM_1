@extends('layouts.app')

@section('title', 'Edit Barang')
@section('header', 'Edit Barang Inventaris')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Perbarui Barang Inventaris</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Perbarui data unit, nama barang, kategori, atau status kelaikan aset.</p>
</div>

<div class="card" style="max-width: 600px; padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-package" style="color: var(--accent-color);"></i> Edit Barang
    </h4>

    <form action="{{ route('pengurus.inventories.update', $inventory->id) }}" method="POST">
        @csrf
        @method('PUT')
        


        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Barang / Aset</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $inventory->name) }}" placeholder="Contoh: Stand Partitur Besi Hitam" style="padding: 0.65rem;">
            @error('name') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Kategori Barang</label>
            <select name="category" class="form-control" required style="padding: 0.65rem;">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $c)
                    <option value="{{ $c }}" {{ old('category', $inventory->category) === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
            @error('category') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Jumlah Total Stok (pcs)</label>
            <input type="number" name="quantity" class="form-control" required value="{{ old('quantity', $inventory->quantity) }}" placeholder="Contoh: 10" style="padding: 0.65rem;">
            @error('quantity') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Lokasi Penyimpanan</label>
            <input type="text" name="storage_location" class="form-control" required value="{{ old('storage_location', $inventory->storage_location) }}" placeholder="Contoh: Lemari A Ruang UKM" style="padding: 0.65rem;">
            @error('storage_location') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Kondisi Barang</label>
            <select name="condition" class="form-control" required style="padding: 0.65rem;">
                <option value="Baik" {{ old('condition', $inventory->condition) === 'Baik' ? 'selected' : '' }}>Baik (Siap digunakan)</option>
                <option value="Rusak" {{ old('condition', $inventory->condition) === 'Rusak' ? 'selected' : '' }}>Rusak (Butuh perbaikan)</option>
                <option value="Hilang" {{ old('condition', $inventory->condition) === 'Hilang' ? 'selected' : '' }}>Hilang</option>
            </select>
            @error('condition') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Digunakan Untuk</label>
            <select name="used_for" id="used_for" class="form-control" required style="padding: 0.65rem;">
                <option value="Umum" {{ old('used_for', $inventory->used_for) === 'Umum' ? 'selected' : '' }}>Umum</option>
                <option value="Program Kerja" {{ old('used_for', $inventory->used_for) === 'Program Kerja' ? 'selected' : '' }}>Program Kerja</option>
            </select>
            @error('used_for') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-6" id="program_select_wrapper" style="display: {{ old('used_for', $inventory->used_for) === 'Program Kerja' ? 'block' : 'none' }};">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Program Kerja</label>
            <select name="program_id" class="form-control" style="padding: 0.65rem;">
                <option value="">-- Pilih Program Kerja --</option>
                @foreach($programs as $prog)
                    <option value="{{ $prog->id }}" {{ old('program_id', $inventory->program_id) == $prog->id ? 'selected' : '' }}>
                        {{ $prog->name }} (s.d. {{ date('d-m-Y', strtotime($prog->end_date)) }})
                    </option>
                @endforeach
            </select>
            @error('program_id') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Simpan Perubahan</button>
            <a href="{{ route('pengurus.inventories.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('used_for').addEventListener('change', function() {
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
