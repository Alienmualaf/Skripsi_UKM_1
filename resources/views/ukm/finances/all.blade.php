@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Seluruh Riwayat Keuangan')
@section('header', 'Semua Riwayat Keuangan UKM')

@section('content')
<div class="mb-6">
    <a href="/ukm/finance" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); font-weight: 700; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 0.375rem;">
        <i class="ph ph-arrow-left"></i> Kembali ke Pengelolaan
    </a>
</div>

<div class="card">
    <h3 class="mb-4">Daftar Lengkap Riwayat Keuangan Organisasi ({{ count($finances) }} Transaksi)</h3>
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
</div>

@endsection
