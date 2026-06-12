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
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Data instruktur vokal, dirigen, pianis, dan pelatih resmi Paduan Suara Universitas Pancasila. Penambahan pelatih dilakukan oleh Pengurus.</p>
    </div>
</div>

<div class="card" style="padding: 1.5rem;">
    <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-briefcase" style="color: var(--accent-color);"></i> Tim Pelatih & Pembina
    </h4>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">Foto</th>
                    <th>Nama Pelatih</th>
                    <th>Spesialisasi / Keahlian</th>
                    <th>Nomor Telepon</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainers as $trainer)
                <tr>
                    <td>
                        @if($trainer->photo)
                            <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--accent-light); display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--accent-color);">{{ substr($trainer->name, 0, 1) }}</div>
                        @endif
                    </td>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $trainer->name }}</td>
                    <td style="color: var(--text-primary); font-weight: 600;">{{ $trainer->specialty }}</td>
                    <td style="color: var(--text-secondary);">{{ $trainer->phone ?? '-' }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('ukm.trainers.edit', $trainer->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;"><i class="ph ph-pencil-simple"></i> Edit</a>
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
