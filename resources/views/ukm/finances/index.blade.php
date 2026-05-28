@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Kelola Keuangan')
@section('header', 'Kelola Keuangan')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #10b981;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

{{-- FORM INPUT FULL WIDTH: 2 KOLOM --}}
@if($isOperator)
<div class="card mb-4 animate-fade-in" style="border-top: 3px solid var(--accent-color);">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
        <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--accent-light); display: flex; align-items: center; justify-content: center;">
            <i class="ph-fill ph-wallet" style="font-size: 1.25rem; color: var(--accent-color);"></i>
        </div>
        <div>
            <h3 style="margin: 0; font-weight: 800; font-size: 1.1rem;">Catat Transaksi Baru</h3>
            <p style="margin: 0; font-size: 0.8rem; color: var(--text-secondary);">Catat pemasukan dan pengeluaran keuangan organisasi.</p>
        </div>
    </div>

    <form action="/ukm/finance" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem 2rem;">
            {{-- KOLOM KIRI --}}
            <div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Jenis Transaksi <span style="color: var(--danger-color);">*</span></label>
                        <select name="type" class="form-control" required>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nominal (Rp) <span style="color: var(--danger-color);">*</span></label>
                        <input type="number" name="amount" class="form-control" required min="0" placeholder="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Judul / Keterangan Singkat <span style="color: var(--danger-color);">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Iuran Bulanan, Pembelian Alat" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Transaksi <span style="color: var(--danger-color);">*</span></label>
                    <input type="date" name="transaction_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div>
                <div class="form-group">
                    <label class="form-label">Terkait Agenda</label>
                    <select name="event_id" class="form-control">
                        <option value="">Operasional BPH (Umum)</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.35rem;">Pilih "Operasional BPH" untuk transaksi rutin organisasi.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Lengkap (Opsional)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Rincian transaksi, catatan tambahan..." style="resize: vertical;"></textarea>
                </div>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-color); padding-top: 1.25rem; margin-top: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
            <p style="margin: 0; font-size: 0.8rem; color: var(--text-secondary);"><i class="ph ph-info" style="margin-right: 0.25rem;"></i> Field bertanda <span style="color: var(--danger-color); font-weight: 700;">*</span> wajib diisi.</p>
            <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Simpan Transaksi
            </button>
        </div>
    </form>
</div>
@endif

{{-- TABEL DATA (MAKS 10) --}}
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="margin: 0; font-weight: 800;">Riwayat Keuangan</h3>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            <i class="ph ph-database" style="margin-right: 0.25rem;"></i> Total {{ $totalFinances }} transaksi
        </span>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Transaksi</th>
                    <th>Agenda</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                    @if($isOperator)
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($finances as $finance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($finance->transaction_date)->format('d/m/Y') }}</td>
                    <td style="font-weight: 600;">{{ $finance->title }}</td>
                    <td>
                        @if($finance->event)
                            <span class="text-secondary" style="font-size: 0.8125rem; font-weight: 600;">{{ $finance->event->title }}</span>
                        @else
                            <span class="text-secondary" style="font-size: 0.8125rem; font-style: italic;">Operasional BPH</span>
                        @endif
                    </td>
                    <td>
                        @if($finance->type == 'income')
                            <span class="badge badge-approved">Pemasukan</span>
                        @else
                            <span class="badge badge-pending" style="background-color: rgba(239, 68, 68, 0.2); color: #ef4444;">Pengeluaran</span>
                        @endif
                    </td>
                    <td style="font-weight: bold; color: {{ $finance->type == 'income' ? 'var(--success-color)' : 'var(--danger-color)' }}">
                        {{ $finance->type == 'income' ? '+' : '-' }} Rp {{ number_format($finance->amount, 0, ',', '.') }}
                    </td>
                    @if($isOperator)
                    <td>
                        <form action="/ukm/finance/{{ $finance->id }}" method="POST" onsubmit="return confirm('Hapus catatan ini?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.85rem; font-size: 0.8125rem;"><i class="ph ph-trash"></i> Hapus</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
                
                @if(count($finances) == 0)
                <tr>
                    <td colspan="{{ $isOperator ? 6 : 5 }}" class="text-secondary text-center" style="padding: 2.5rem;">
                        <i class="ph ph-wallet" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.4;"></i>
                        Belum ada catatan keuangan.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- TOMBOL LIHAT SELENGKAPNYA --}}
    @if($totalFinances > 10)
    <div style="text-align: center; margin-top: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
        <a href="/ukm/finance/all" class="btn" style="background: var(--accent-light); color: var(--accent-color); font-weight: 700; padding: 0.65rem 1.75rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: var(--radius-md); transition: all 0.2s ease; border: 1px solid rgba(30, 64, 175, 0.15);">
            <i class="ph ph-arrow-right"></i> Lihat Selengkapnya
        </a>
    </div>
    @endif
</div>

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
