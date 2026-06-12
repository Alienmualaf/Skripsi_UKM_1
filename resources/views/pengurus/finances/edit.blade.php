@extends('layouts.app')

@section('title', 'Edit Transaksi')
@section('header', 'Edit Transaksi Kas')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Perbarui Transaksi</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Perbarui informasi nominal, deskripsi, or kategori transaksi kas.</p>
</div>

<div class="card" style="max-width: 600px; padding: 2rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-currency-dollar-simple" style="color: var(--accent-color);"></i> Edit Transaksi
    </h4>

    <form action="{{ route('pengurus.finances.update', $finance->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Jenis Kategori</label>
            <select name="finance_category_id" class="form-control" required style="padding: 0.65rem;">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('finance_category_id', $finance->finance_category_id) == $cat->id ? 'selected' : '' }}>
                        [{{ strtoupper($cat->type) }}] {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('finance_category_id') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Judul Transaksi</label>
            <input type="text" name="title" class="form-control" required value="{{ old('title', $finance->title) }}" placeholder="Contoh: Iuran Kas Bulanan Mei / Dana Sponsor Konser" style="padding: 0.65rem;">
            @error('title') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi (Opsional)</label>
            <textarea name="description" class="form-control" placeholder="Detail tambahan transaksi..." style="padding: 0.65rem; min-height: 80px;">{{ old('description', $finance->description) }}</textarea>
            @error('description') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nominal (Rupiah)</label>
            <input type="number" name="amount" class="form-control" required value="{{ old('amount', $finance->amount) }}" placeholder="Contoh: 150000" style="padding: 0.65rem;">
            @error('amount') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tanggal Transaksi</label>
            <input type="date" name="transaction_date" class="form-control" required value="{{ old('transaction_date', $finance->transaction_date) }}" style="padding: 0.65rem;">
            @error('transaction_date') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Digunakan Untuk</label>
            <select name="used_for" id="used_for" class="form-control" required style="padding: 0.65rem;">
                <option value="Umum" {{ old('used_for', $finance->used_for) === 'Umum' ? 'selected' : '' }}>Umum</option>
                <option value="Program Kerja" {{ old('used_for', $finance->used_for) === 'Program Kerja' ? 'selected' : '' }}>Program Kerja</option>
            </select>
            @error('used_for') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-4" id="program_select_wrapper" style="display: {{ old('used_for', $finance->used_for) === 'Program Kerja' ? 'block' : 'none' }};">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Program Kerja</label>
            <select name="program_id" class="form-control" style="padding: 0.65rem;">
                <option value="">-- Pilih Program Kerja --</option>
                @foreach($programs as $prog)
                    <option value="{{ $prog->id }}" {{ old('program_id', $finance->program_id) == $prog->id ? 'selected' : '' }}>
                        {{ $prog->name }} (s.d. {{ date('d-m-Y', strtotime($prog->end_date)) }})
                    </option>
                @endforeach
            </select>
            @error('program_id') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="form-group mb-6">
            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Bukti Transaksi (Opsional)</label>
            @if($finance->receipt_file)
                <div style="margin-bottom: 0.5rem; font-size: 0.8rem; color: var(--text-secondary);">
                    File Saat Ini: <a href="{{ asset('storage/' . $finance->receipt_file) }}" target="_blank">Lihat Bukti</a>
                </div>
            @endif
            <input type="file" name="receipt_file" class="form-control" style="padding: 0.5rem;">
            @error('receipt_file') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700;">Simpan Perubahan</button>
            <a href="{{ route('pengurus.finances.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.65rem 1.25rem; font-weight: 700;">Batal</a>
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
