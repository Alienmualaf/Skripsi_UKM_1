@extends('layouts.app')

@section('title', 'Keuangan Kas')
@section('header', 'Keuangan Kas PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

{{-- Balance Hero --}}
<div class="card mb-6" style="padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; background: linear-gradient(135deg, var(--accent-color) 0%, #1e3a8a 100%); color: #fff;">
    <div>
        <h4 style="margin: 0; font-size: 0.8125rem; font-weight: 700; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.05em; color: #fff;">Total Saldo Kas Bersih PSUP</h4>
        <div style="font-size: 2.25rem; font-weight: 800; margin-top: 0.25rem; color: #fff;">
            Rp {{ number_format($netBalance, 0, ',', '.') }}
        </div>
    </div>
    @if(!false)
    <a href="{{ route('pengurus.finances.create') }}" class="btn" style="background: white; color: var(--accent-color); padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
        <i class="ph ph-plus"></i> Tambah Transaksi
    </a>
    @endif
</div>

<style>
    @media (max-width: 768px) {
        /* Filter full width */
        .fin-filter { flex-direction: column !important; }
        .fin-filter > * { width: 100% !important; }

        /* Simple responsive table */
        .table { font-size: 0.8rem !important; }
        .table td, .table th { padding: 0.5rem 0.4rem !important; white-space: normal !important; word-break: break-word !important; }
        .fin-hide { display: none !important; }
        .fin-actions { display: flex; gap: 0.3rem; justify-content: center; }
        .fin-actions .btn { padding: 0.35rem 0.45rem !important; font-size: 0.75rem !important; }
    }
</style>

<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-wallet" style="color: var(--accent-color);"></i> Buku Kas & Mutasi
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">{{ $finances->total() }} Transaksi</span>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('pengurus.finances.index') }}" class="fin-filter" style="display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 1.25rem;">
        <div style="width: 200px;">
            <label style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Jenis Aliran</label>
            <select name="type" class="form-control" style="width: 100%;" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="income"  {{ request('type') === 'income'  ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>
        @if(request()->anyFilled(['type']))
            <a href="{{ route('pengurus.finances.index') }}" class="btn" style="height: 2.5rem; padding: 0 1rem; display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 600; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary);">
                <i class="ph ph-x-circle"></i> Reset
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th class="fin-hide">Tanggal</th>
                    <th>Transaksi</th>
                    <th class="fin-hide">Digunakan Untuk</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($finances as $fin)
                <tr>
                    <td class="fin-hide" style="color: var(--text-secondary); white-space: nowrap;">{{ date('d/m/Y', strtotime($fin->transaction_date)) }}</td>
                    <td>
                        <div style="font-weight: 700; color: var(--text-primary);">{{ $fin->title }}</div>
                        {{-- Show date inline on mobile --}}
                        <div style="font-size: 0.72rem; color: var(--text-muted); display: none;" class="fin-date-mobile">{{ date('d M Y', strtotime($fin->transaction_date)) }}</div>
                    </td>
                    <td class="fin-hide">
                        @if($fin->used_for === 'Program Kerja')
                            <span class="badge" style="background: rgba(30,64,175,0.1); color: #1e40af; font-size: 0.72rem;">Proker: {{ $fin->program->name ?? '-' }}</span>
                        @else
                            <span class="badge" style="background: rgba(107,114,128,0.1); color: #4b5563; font-size: 0.72rem;">Umum</span>
                        @endif
                    </td>
                    <td>
                        @if($fin->type === 'income')
                            <span class="badge" style="background: rgba(16,185,129,0.1); color: var(--success-color); font-size: 0.72rem;">Masuk</span>
                        @else
                            <span class="badge" style="background: rgba(239,68,68,0.1); color: var(--danger-color); font-size: 0.72rem;">Keluar</span>
                        @endif
                    </td>
                    <td style="font-weight: 800; white-space: nowrap; color: {{ $fin->type === 'income' ? 'var(--success-color)' : 'var(--danger-color)' }};">
                        {{ $fin->type === 'income' ? '+' : '−' }}Rp {{ number_format($fin->amount, 0, ',', '.') }}
                    </td>
                    <td>
                        <div class="fin-actions">
                            <a href="{{ route('pengurus.finances.edit', $fin->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); text-decoration: none;" title="Edit">
                                <i class="ph ph-pencil-simple"></i><span class="fin-hide"> Edit</span>
                            </a>
                            @if(!false)
                            <form action="{{ route('pengurus.finances.destroy', $fin->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?');" style="margin: 0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" title="Hapus">
                                    <i class="ph ph-trash"></i><span class="fin-hide"> Hapus</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Belum ada transaksi tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $finances->links('shared.pagination') }}
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .fin-date-mobile { display: block !important; }
    }
</style>
@endsection
