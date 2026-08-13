@extends('layouts.app')

@section('title', 'Peminjaman Barang')
@section('header', 'Peminjaman Barang PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card mb-4 animate-fade-in" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.875rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">
            @if(isset($inventory))
                Peminjaman Aset: {{ $inventory->name }}
            @else
                Log Peminjaman Barang PSUP
            @endif
        </h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">
            @if(isset($inventory))
                Catat peminjaman baru dan lihat riwayat peminjaman untuk item ini.
            @else
                Tinjau riwayat peminjaman alat/aset dari seluruh anggota.
            @endif
        </p>
    </div>
    <a href="{{ route('pengurus.inventories.index') }}" class="btn btn-secondary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
        <i class="ph ph-arrow-left"></i> Kembali ke Aset
    </a>
</div>

<div class="grid grid-cols-1 {{ (isset($inventory) && !false) ? 'md:grid-cols-3' : '' }} gap-6">
    <!-- Log Peminjaman List -->
    <div class="card {{ (isset($inventory) && !false) ? 'md:col-span-2' : '' }}" style="padding: 1.5rem; height: fit-content;">
        <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-hand-holding-box" style="color: var(--accent-color);"></i> Log Aktivitas Peminjaman
        </h4>

        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pihak Peminjam</th>
                        @if(!isset($inventory))
                            <th>Barang</th>
                        @endif
                        <th>Jumlah</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Surat</th>
                        <th>Status</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $loan->borrower_name ?? ($loan->member->name ?? 'Pihak Luar') }}</td>
                        @if(!isset($inventory))
                            <td style="font-weight: 600; color: var(--text-primary);">{{ $loan->inventory->name ?? 'Barang Terhapus' }}</td>
                        @endif
                        <td style="color: var(--text-secondary);">{{ $loan->quantity }} pcs</td>
                        <td style="color: var(--text-secondary);">{{ date('d-m-Y', strtotime($loan->loan_date)) }}</td>
                        <td style="color: var(--text-secondary);">{{ $loan->return_date ? date('d-m-Y', strtotime($loan->return_date)) : '-' }}</td>
                        <td>
                            @if($loan->loan_letter)
                                <a href="{{ asset('storage/' . $loan->loan_letter) }}" target="_blank" class="btn" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; background: var(--accent-light); color: var(--accent-color); border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2; margin: 0 0 0 0.5rem;">
                                    <i class="ph ph-file-text"></i> Surat
                                </a>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($loan->status === 'Dipinjam')
                                <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: bold;">Dipinjam</span>
                            @else
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); font-weight: bold;">Dikembalikan</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                @if($loan->status === 'Dipinjam')
                                    <form action="{{ route('pengurus.inventories.loans.return', $loan->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tandai barang ini sebagai sudah dikembalikan?');">
                                        @csrf
                                        <input type="hidden" name="actual_return_date" value="{{ date('Y-m-d') }}">
                                        <input type="hidden" name="condition_on_return" value="Baik">
                                        <button type="submit" class="btn btn-primary" style="background: var(--success-color); color: #fff; padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; border-radius: 6px;"><i class="ph ph-arrow-counter-clockwise"></i> Kembalikan</button>
                                    </form>
                                @else
                                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: bold;">Selesai</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ isset($inventory) ? '8' : '9' }}" class="text-center text-secondary py-4">Belum ada riwayat peminjaman barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Catat Peminjaman Baru Form (Only shown if specific inventory is loaded) -->
    @if(isset($inventory) && !false)
    <div class="card md:col-span-1" style="padding: 1.5rem; height: fit-content;">
        <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-plus" style="color: var(--accent-color);"></i> Catat Peminjaman
        </h4>
        <p style="font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.4;">
            Stok saat ini: <strong>{{ $inventory->quantity }} pcs</strong>
        </p>

        @if($inventory->quantity > 0)
        <form action="{{ route('pengurus.inventories.loans.store', $inventory->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Nama / Pihak Peminjam</label>
                <input type="text" name="borrower_name" class="form-control" placeholder="Contoh: BEM FT / Pihak Dekanat" required value="{{ old('borrower_name') }}" style="padding: 0.5rem;">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Upload Surat Peminjaman (PDF/Gambar, Max 2MB)</label>
                <input type="file" name="loan_letter" class="form-control" required style="padding: 0.5rem;">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Jumlah Pinjam</label>
                <input type="number" name="quantity" class="form-control" min="1" max="{{ $inventory->available_qty }}" value="1" required style="padding: 0.5rem;">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Tanggal Pinjam</label>
                <input type="date" name="loan_date" class="form-control" value="{{ date('Y-m-d') }}" required style="padding: 0.5rem;">
            </div>

            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Rencana Kembali</label>
                <input type="date" name="return_date" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required style="padding: 0.5rem;">
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Kondisi Awal Barang</label>
                <select name="condition_on_loan" class="form-control" required style="padding: 0.5rem; width: 100%;">
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                    <option value="Hilang">Hilang</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.65rem; font-weight: 700; border-radius: 8px;">
                <i class="ph ph-floppy-disk"></i> Simpan Catatan
            </button>
        </form>
        @else
        <div style="background: #fff5f5; border: 1px solid #fed7d7; color: #c53030; padding: 1rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; text-align: center;">
            Stok barang habis. Tidak dapat meminjamkan barang ini saat ini.
        </div>
        @endif
    </div>
    @endif
</div>


@endsection
