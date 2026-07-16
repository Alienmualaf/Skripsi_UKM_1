@extends('layouts.app')

@section('title', 'Monitor Keuangan')
@section('header', 'Monitoring Keuangan Kas')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Override Data: Keuangan Kas</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Pengawasan seluruh pembukuan keuangan UKM PSUP, pencatatan iuran kas masuk, dana keluar, sponsor, dan pendanaan rektorat.</p>
</div>

<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- 1. DATA KAS KEUANGAN -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-coins" style="color: var(--accent-color);"></i> Database Transaksi Keuangan Kas
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi / Transaksi</th>
                        <th>Tipe</th>
                        <th>Jumlah (Rupiah)</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($finances as $f)
                    <tr>
                        <td style="font-size: 0.8125rem; color: var(--text-secondary);">{{ $f->transaction_date ? date('d-m-Y', strtotime($f->transaction_date)) : '-' }}</td>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $f->description }}</td>
                        <td>
                            @if($f->type === 'income')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16,185,129,0.15); font-weight: bold;">Masuk (Income)</span>
                            @else
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid rgba(239,68,68,0.15); font-weight: bold;">Keluar (Expense)</span>
                            @endif
                        </td>
                        <td style="font-weight: bold; color: {{ $f->type === 'income' ? 'var(--success-color)' : 'var(--danger-color)' }}">
                            {{ $f->type === 'income' ? '+' : '-' }}Rp{{ number_format($f->amount, 0, ',', '.') }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                                <a href="{{ route('pengurus.finances.edit', $f->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'finance', 'id' => $f->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa transaksi ini? Saldo kas akan menyesuaikan kembali.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Tidak ada data transaksi keuangan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $finances->appends(['finances_page' => $finances->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

</div>
@endsection
