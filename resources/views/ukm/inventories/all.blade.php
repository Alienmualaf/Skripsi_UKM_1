@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Seluruh Inventaris Barang')
@section('header', 'Semua Inventaris Barang UKM')

@section('content')
<div class="mb-6">
    <a href="/ukm/inventory" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); font-weight: 700; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 0.375rem;">
        <i class="ph ph-arrow-left"></i> Kembali ke Pengelolaan
    </a>
</div>

<div class="card">
    <h3 class="mb-4">Daftar Lengkap Inventaris Organisasi ({{ count($inventories) }} Barang)</h3>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Agenda</th>
                    <th>Kondisi</th>
                    <th>Lokasi</th>
                    @if($isOperator)
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $item)
                <tr>
                    <td style="font-weight: 600;">{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        @if($item->event)
                            <span class="text-secondary" style="font-size: 0.8125rem; font-weight: 600;">{{ $item->event->title }}</span>
                        @else
                            <span class="text-secondary" style="font-size: 0.8125rem; font-style: italic;">Operasional BPH</span>
                        @endif
                    </td>
                    <td>
                        @if($item->condition == 'good')
                            <span class="badge badge-approved">Baik</span>
                        @elseif($item->condition == 'damaged')
                            <span class="badge badge-pending">Rusak</span>
                        @else
                            <span class="badge" style="background-color: rgba(239, 68, 68, 0.2); color: #ef4444;">Hilang</span>
                        @endif
                    </td>
                    <td>{{ $item->location ?? '-' }}</td>
                    @if($isOperator)
                    <td>
                        <form action="/ukm/inventory/{{ $item->id }}" method="POST" onsubmit="return confirm('Hapus barang ini dari daftar inventaris?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;"><i class="ph ph-trash"></i> Hapus</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
                
                @if(count($inventories) == 0)
                <tr>
                    <td colspan="{{ $isOperator ? 6 : 5 }}" class="text-secondary text-center" style="padding: 2rem;">Belum ada barang inventaris.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
