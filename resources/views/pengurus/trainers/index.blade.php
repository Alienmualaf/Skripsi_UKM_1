@extends('layouts.app')

@section('title', 'Daftar Pelatih')
@section('header', 'Manajemen Pelatih PSUP')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Daftar Pelatih PSUP</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola data instruktur vokal, dirigen, pianis, dan pelatih resmi Paduan Suara Universitas Pancasila.</p>
    </div>
    @if(!auth()->user()->isAdminUkm())
    <a href="{{ route('pengurus.trainers.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
        <i class="ph ph-plus"></i> Tambah Pelatih
    </a>
    @endif
</div>

<div class="card" style="padding: 1.5rem;">
    <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-briefcase" style="color: var(--accent-color);"></i> Tim Pelatih &amp; Pembina
    </h4>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">Foto</th>
                    <th>Nama Pelatih</th>
                    <th>Spesialisasi / Keahlian</th>
                    <th>Nomor Telepon</th>
                    <th style="width: 160px; text-align: center;">Aksi</th>
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
                            <a href="{{ route('pengurus.trainers.edit', $trainer->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form action="{{ route('pengurus.trainers.destroy', $trainer->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelatih ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;"><i class="ph ph-trash"></i> Hapus</button>
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
