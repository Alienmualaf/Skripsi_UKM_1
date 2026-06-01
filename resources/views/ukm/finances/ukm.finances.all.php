@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Riwayat Keuangan')
@section('header', 'Riwayat Keuangan')

@section('content')
<div class="mb-6">
    <a href="/ukm/finance" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); font-weight: 700; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 0.375rem;">
        <i class="ph ph-arrow-left"></i> Kembali ke Pengelolaan
    </a>
</div>

<!-- Filter Card -->
<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="width: 180px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Jenis Transaksi</label>
            <select name="type" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Transaksi</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, keterangan..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['type', 'search']))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-receipt" style="color: var(--accent-color);"></i> Daftar Lengkap Riwayat Keuangan Organisasi
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $finances->total() }} Transaksi
        </span>
    </div>
    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
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
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;"><i class="ph ph-trash"></i> Hapus</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
                
                @if(count($finances) == 0)
                <tr>
                    <td colspan="{{ $isOperator ? 6 : 5 }}" class="text-secondary text-center" style="padding: 2rem;">Belum ada catatan keuangan.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.25rem;">
        {{ $finances->links('shared.pagination') }}
    </div>
</div>

@endsection
