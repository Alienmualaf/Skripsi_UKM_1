@extends('layouts.app')

@section('title', 'Inventaris Barang')
@section('header', 'Inventaris Barang PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Aset & Inventaris PSUP</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola instrumen (keyboard, piano), alat pendukung (stand partitur), seragam konser, dan pantau peminjaman anggota.</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('pengurus.inventories.all_loans') }}" class="btn" style="background: var(--accent-light); color: var(--accent-color); padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
            <i class="ph ph-hand-holding-box"></i> Daftar Peminjaman
        </a>
        <a href="{{ route('pengurus.inventories.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
            <i class="ph ph-plus"></i> Tambah Barang
        </a>
    </div>
</div>

<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-package" style="color: var(--accent-color);"></i> Daftar Inventaris Aset
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $inventories->total() }} Item
        </span>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('pengurus.inventories.index') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 1.25rem;">
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Barang</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['search']))
            <a href="{{ route('pengurus.inventories.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Digunakan Untuk</th>
                    <th>Stok Total</th>
                    <th>Stok Tersedia</th>
                    <th>Kondisi</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $inv)
                <tr>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $inv->name }}</td>
                    <td style="color: var(--text-secondary);">{{ $inv->category ?? 'Umum' }}</td>
                    <td>
                        @if($inv->used_for === 'Program Kerja')
                            <span class="badge" style="background: rgba(30, 64, 175, 0.1); color: #1e40af; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px;">Proker: {{ $inv->program->name ?? '-' }}</span>
                        @else
                            <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #4b5563; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px;">Umum</span>
                        @endif
                    </td>
                    <td style="color: var(--text-primary); font-weight: 600;">{{ $inv->total_qty }} pcs</td>
                    <td style="color: var(--text-primary); font-weight: 600;">
                        <span class="badge" style="background: {{ $inv->available_qty > 0 ? 'var(--accent-light)' : '#fef2f2' }}; color: {{ $inv->available_qty > 0 ? 'var(--accent-color)' : 'var(--danger-color)' }};">
                            {{ $inv->available_qty }} pcs tersedia
                        </span>
                    </td>
                    <td>
                        @if($inv->condition === 'Baik' || $inv->condition === 'good')
                            <span class="badge badge-approved" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">Baik</span>
                        @elseif($inv->condition === 'Cukup' || $inv->condition === 'fair')
                            <span class="badge badge-warning" style="background: #fff8e6; color: #f59e0b;">Cukup</span>
                        @elseif($inv->condition === 'Rusak' || $inv->condition === 'poor')
                            <span class="badge badge-danger" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color);">Rusak</span>
                        @else
                            <span class="badge badge-danger" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color);">{{ $inv->condition }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('pengurus.inventories.loans', $inv->id) }}" class="btn" style="background: var(--accent-light); color: var(--accent-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; text-decoration: none; border-radius: 6px;"><i class="ph ph-hand-holding-box"></i> Pinjam</a>
                            <a href="{{ route('pengurus.inventories.edit', $inv->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form action="{{ route('pengurus.inventories.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;"><i class="ph ph-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary py-4">Belum ada barang terinventaris.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.25rem;">
        {{ $inventories->links('shared.pagination') }}
    </div>
</div>
@endsection
