@extends('layouts.app')

@section('title', 'Admin UKM Dashboard')
@section('header', 'Dashboard Admin UKM')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ukm.css') }}">
<style>
    .ukm-stat-card {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        border-radius: 16px;
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 0 !important;
    }
    .ukm-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    .ukm-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }
    .grid-4-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
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
            font-size: 0.75rem !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }
    }
</style>

<!-- Stat Grid -->
<div class="grid-4-stats">
    <!-- Total Anggota Aktif -->
    <a href="{{ route('ukm.members') }}" class="card ukm-stat-card" style="text-decoration: none; color: inherit;">
        <div class="ukm-icon-box" style="background: rgba(30, 64, 175, 0.08); color: #1e40af;">
            <i class="ph ph-users-three"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Anggota Aktif</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalMembers }}</div>
        </div>
    </a>

    <!-- Calon Anggota (Recruitment) -->
    <a href="{{ route('ukm.registrations') }}" class="card ukm-stat-card" style="text-decoration: none; color: inherit;">
        <div class="ukm-icon-box" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">
            <i class="ph ph-user-plus"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Pendaftaran Calon</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: #d97706; margin-top: 0.15rem;">{{ $pendingRegistrationsCount }}</div>
        </div>
    </a>

    <!-- Total Pelatih -->
    <a href="{{ route('ukm.trainers.index') }}" class="card ukm-stat-card" style="text-decoration: none; color: inherit;">
        <div class="ukm-icon-box" style="background: rgba(16, 185, 129, 0.08); color: #059669;">
            <i class="ph ph-chalkboard-teacher"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Total Pelatih</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalTrainers }}</div>
        </div>
    </a>

    <!-- Aset Inventaris -->
    <a href="{{ route('pengurus.inventories.index') }}" class="card ukm-stat-card" style="text-decoration: none; color: inherit;">
        <div class="ukm-icon-box" style="background: rgba(99, 102, 241, 0.08); color: #4f46e5;">
            <i class="ph ph-package"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">Aset Inventaris</h4>
            <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalInventories }}</div>
        </div>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="margin-top: 1.5rem;">
    <!-- Verifikasi Calon Anggota (Recruitment) -->
    <div class="card" style="padding: 1.75rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <i class="ph ph-user-circle-gear" style="color: var(--accent-color);"></i> Verifikasi Calon Anggota
            </h3>
            <a href="{{ route('ukm.registrations') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--accent-color); text-decoration: none;">Lihat Semua <i class="ph ph-caret-right"></i></a>
        </div>
        
        <div class="table-wrapper" style="margin: 0; border: none; box-shadow: none; padding: 0;">
            <table class="table" style="font-size: 0.85rem; width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Calon</th>
                        <th class="hidden-mobile">Tanggal Daftar</th>
                        <th class="hidden-mobile">Email / Telepon</th>
                        <th style="text-align: center; width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestRegistrations as $reg)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $reg->name }}</td>
                        <td class="hidden-mobile" style="color: var(--text-secondary);">{{ $reg->created_at ? $reg->created_at->format('d-m-Y') : '-' }}</td>
                        <td class="hidden-mobile" style="color: var(--text-secondary); font-size: 0.8rem;">
                            {{ $reg->email ?? '-' }}<br>
                            <small style="color: var(--text-muted);">{{ $reg->phone ?? '-' }}</small>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('ukm.registrations') }}" class="btn btn-primary" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem;">
                                <i class="ph ph-check"></i> Verifikasi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-secondary text-center py-8">
                            <i class="ph ph-shield-check" style="font-size: 2.5rem; color: var(--success-color); display: block; margin: 0 auto 0.5rem;"></i>
                            Semua pendaftaran telah selesai diverifikasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Humas & Media: Dokumentasi Terbaru -->
    <div class="card" style="padding: 1.75rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <i class="ph ph-image" style="color: var(--accent-color);"></i> Dokumentasi & Galeri Terbaru
            </h3>
            <a href="{{ route('ukm.galleries.index') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--accent-color); text-decoration: none;">Lihat Semua <i class="ph ph-caret-right"></i></a>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            @forelse($latestGalleries as $gallery)
                <div style="background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; height: 160px; position: relative;">
                    @if($gallery->file_path)
                        <div style="height: 100px; background-image: url('{{ asset('storage/' . $gallery->file_path) }}'); background-size: cover; background-position: center; width: 100%;"></div>
                    @else
                        <div style="height: 100px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; width: 100%;">
                            <i class="ph ph-image-square-broken" style="font-size: 2rem; color: #94a3b8;"></i>
                        </div>
                    @endif
                    <div style="padding: 0.5rem; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                        <div style="font-weight: 700; font-size: 0.8rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $gallery->title }}">{{ $gallery->title }}</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.25rem;">
                            <span class="badge" style="background: {{ $gallery->show_on_landing ? 'rgba(16, 185, 129, 0.1)' : '#f1f5f9' }}; color: {{ $gallery->show_on_landing ? 'var(--success-color)' : '#64748b' }}; font-size: 0.65rem; font-weight: 700;">
                                {{ $gallery->show_on_landing ? 'Tampil Landing' : 'Draf' }}
                            </span>
                            <span style="font-size: 0.65rem; color: var(--text-muted);">{{ $gallery->created_at ? $gallery->created_at->diffForHumans() : '-' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: span 2; text-align: center; color: var(--text-muted); padding: 3rem 0;">
                    <i class="ph ph-images" style="font-size: 2.5rem; color: var(--text-muted); display: block; margin: 0 auto 0.5rem;"></i>
                    Belum ada dokumentasi terunggah.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection