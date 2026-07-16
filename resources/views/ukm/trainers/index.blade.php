@extends('layouts.app')

@section('title', 'Kelola Pelatih')
@section('header', 'Kelola Pelatih PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Daftar Pelatih PSUP</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Data instruktur vokal, dirigen, pianis, dan pelatih resmi Paduan Suara Universitas Pancasila.</p>
    </div>
    <div>
        <a href="{{ route('ukm.trainers.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-plus"></i> Tambah Pelatih
        </a>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table {
            display: table !important;
            table-layout: fixed !important;
            width: 100% !important;
        }
        thead, tbody, tr {
            min-width: auto !important;
            display: table-row-group !important;
        }
        thead {
            display: table-header-group !important;
        }
        tr {
            display: table-row !important;
        }
        .table td, .table th {
            padding: 0.5rem 0.35rem !important;
            font-size: 0.725rem !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }
        .th-action-compact {
            width: 75px !important;
        }
    }
</style>

<div class="card" style="padding: 1.5rem;">
    <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-briefcase" style="color: var(--accent-color);"></i> Tim Pelatih & Pembina
    </h4>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table" style="vertical-align: middle;">
            <thead>
                <tr>
                    <th style="width: 50px;">Foto</th>
                    <th>Nama Pelatih</th>
                    <th>Spesialisasi / Keahlian</th>
                    <th class="hidden-mobile">Nomor Telepon</th>
                    <th class="th-action-compact" style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainers as $trainer)
                <tr>
                    <td>
                        @if($trainer->photo)
                            <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--accent-light); display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--accent-color); font-size: 0.875rem;">{{ substr($trainer->name, 0, 1) }}</div>
                        @endif
                    </td>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $trainer->name }}</td>
                    <td style="color: var(--text-primary); font-weight: 600;">{{ $trainer->specialty }}</td>
                    <td class="hidden-mobile" style="color: var(--text-secondary);">{{ $trainer->phone ?? '-' }}</td>
                    <td>
                        <div style="display: flex; gap: 0.35rem; justify-content: center;">
                            <a href="{{ route('ukm.trainers.edit', $trainer->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.5rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;" title="Edit">
                                <i class="ph ph-pencil-simple"></i><span class="hidden-mobile"> Edit</span>
                            </a>
                            <form action="{{ route('ukm.trainers.destroy', $trainer->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelatih ini?');" style="display: inline; margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.5rem; font-size: 0.8rem; font-weight: 600;" title="Hapus">
                                    <i class="ph ph-trash"></i><span class="hidden-mobile"> Hapus</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-secondary py-4">Belum ada pelatih terdaftar. Klik 'Tambah Pelatih' untuk menambahkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
