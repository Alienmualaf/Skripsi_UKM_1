@extends('layouts.app')

@section('title', 'Monitor Inventaris')
@section('header', 'Monitoring Inventaris')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Override Data: Inventaris & Peminjaman</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Pengawasan seluruh aset inventaris fisik UKM (Keyboard, Standing Mic, Almamater, dll.) dan rekaman peminjaman barang oleh anggota.</p>
</div>

<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- 1. DATA INVENTARIS -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-package" style="color: var(--accent-color);"></i> Database Aset Inventaris UKM
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Aset</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Total</th>
                        <th>Kondisi</th>
                        <th>Lokasi Simpan</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $i)
                    <tr>
                        <td style="font-family: monospace; font-weight: 700;">{{ $i->code }}</td>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $i->name }}</td>
                        <td style="font-weight: bold; color: var(--accent-color);">{{ $i->total_qty }} unit</td>
                        <td>
                            @if($i->condition === 'Baik' || $i->condition === 'good')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16,185,129,0.15); font-weight: bold;">Baik</span>
                            @elseif($i->condition === 'Rusak' || $i->condition === 'poor')
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid rgba(239,68,68,0.15); font-weight: bold;">Rusak</span>
                            @else
                                <span class="badge" style="background: #fff8e6; color: #f59e0b; border: 1px solid rgba(245,158,11,0.15); font-weight: bold;">{{ $i->condition }}</span>
                            @endif
                        </td>
                        <td>{{ $i->storage_location ?? '-' }}</td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                                <a href="{{ route('pengurus.inventories.edit', $i->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'inventory', 'id' => $i->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa aset ini? Seluruh data peminjaman terkait aset ini akan terhapus.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Tidak ada data aset inventaris.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $inventories->appends(['inventories_page' => $inventories->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

    <!-- 2. DATA PEMINJAMAN -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-hand-tap" style="color: var(--accent-color);"></i> Database Peminjaman Aset
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pihak Peminjam</th>
                        <th>Barang Dipinjam</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Surat</th>
                        <th>Status</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $l)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $l->borrower_name ?? ($l->member ? $l->member->name : '-') }}</td>
                        <td style="font-weight: 600;">{{ $l->inventory ? $l->inventory->name : '-' }}</td>
                        <td>{{ $l->quantity }} unit</td>
                        <td>{{ $l->loan_date ? \Carbon\Carbon::parse($l->loan_date)->format('d M Y') : '-' }}</td>
                        <td>
                            @if($l->loan_letter)
                                <a href="{{ asset('storage/' . $l->loan_letter) }}" target="_blank" class="btn" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; background: var(--accent-light); color: var(--accent-color); border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2,;">
                                    <i class="ph ph-file-text"></i> Surat
                                </a>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($l->status === 'Returned')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16,185,129,0.15); font-weight: bold;">Dikembalikan</span>
                            @else
                                <span class="badge" style="background: #fff8e6; color: #f59e0b; border: 1px solid rgba(245,158,11,0.15); font-weight: bold;">Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'inventoryLoan', 'id' => $l->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa data peminjaman ini?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">Tidak ada data peminjaman aset.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $loans->appends(['loans_page' => $loans->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

</div>
@endsection
