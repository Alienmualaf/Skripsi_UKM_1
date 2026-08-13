@extends('layouts.app')

@section('title', 'Penugasan Tampil')
@section('header', 'Penugasan Tampil PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Penugasan Konser & Penampilan</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola sesi performa luar/dalam kampus, tugas menyanyi, dan delegasi tim penyanyi PSUP.</p>
    </div>
    @if(!false)
    <a href="{{ route('pengurus.programs.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
        <i class="ph ph-plus"></i> Tambah Penugasan
    </a>
    @endif
</div>

<div class="card" style="padding: 1.5rem;">
    <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-microphone-stage" style="color: var(--accent-color);"></i> Penugasan Aktif
    </h4>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Penugasan</th>
                    <th>Tanggal Penampilan</th>
                    <th>Lokasi</th>
                    <th>Jumlah Delegasi</th>
                    <th>Anggota Terdelegasi</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $job->title }}</td>
                    <td style="color: var(--text-secondary);">{{ date('d-m-Y', strtotime($job->date)) }}</td>
                    <td style="color: var(--text-secondary);">{{ $job->location }}</td>
                    <td style="font-weight: bold; color: var(--accent-color);">
                        {{ $job->members->count() }} Anggota
                    </td>
                    <td>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; max-width: 250px;">
                            @forelse($job->members as $member)
                                <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                    {{ $member->user->name ?? 'User' }}
                                </span>
                            @empty
                                <span style="font-size: 0.75rem; color: var(--text-muted);">Belum ada anggota ditunjuk.</span>
                            @endforelse
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('pengurus.jobs.edit', $job->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form action="{{ route('pengurus.jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;"><i class="ph ph-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Belum ada penugasan tampil terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
