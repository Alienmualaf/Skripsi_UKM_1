@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Agenda & Kegiatan')
@section('header', 'Agenda & Kegiatan')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #10b981;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if($isOperator && request()->has('create'))
<div class="card mb-4 animate-fade-in" style="max-width: 800px; border-top: 3px solid var(--accent-color);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="margin: 0; font-weight: 800;">Buat Kegiatan Baru</h3>
        <a href="/ukm/events" class="btn btn-secondary" style="padding: 0.4rem 1rem; font-size: 0.8125rem; border-radius: var(--radius-md); display: inline-flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-x"></i> Tutup Form
        </a>
    </div>
    
    <form action="/ukm/events" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Kegiatan</label>
            <input type="text" name="title" class="form-control" placeholder="Contoh: Latihan Rutin, Rapat BPH" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Deskripsi Kegiatan</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Tulis rincian singkat kegiatan..."></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">Lokasi</label>
            <input type="text" name="location" class="form-control" placeholder="Contoh: Gedung Serbaguna, JCC">
        </div>
        
        <div class="flex gap-2" style="margin-bottom: 1rem;">
            <div style="flex: 1;">
                <label class="form-label">Tanggal Mulai</label>
                <input type="datetime-local" name="start_date" class="form-control" required>
            </div>
            <div style="flex: 1;">
                <label class="form-label">Tanggal Selesai</label>
                <input type="datetime-local" name="end_date" class="form-control" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary" style="font-weight: 700;">Simpan Kegiatan</button>
    </form>
</div>
@endif

<!-- Filter Card -->
<div class="card mb-4" style="border-top: 3px solid var(--accent-color); padding: 1.25rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="width: 160px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Status Kegiatan</label>
            <select name="status" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Kegiatan</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kegiatan, lokasi..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['status', 'search']))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 style="margin: 0; font-weight: 800;">Pilih Agenda Kegiatan</h3>
            <p style="margin: 0; font-size: 0.8rem; color: var(--text-secondary);">Total: {{ $events->total() }} agenda ditemukan.</p>
        </div>
        @if($isOperator && !request()->has('create'))
            <a href="/ukm/events?create=true" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-weight: 700; border-radius: var(--radius-md); display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.8125rem;">
                <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i> Tambah Kegiatan Baru
            </a>
        @endif
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Event</th>
                    <th>Jadwal</th>
                    <th>Lokasi</th>
                    <th>Status Kegiatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr>
                    <td style="font-weight: 600;">{{ $event->title }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }} - <br>
                        {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}
                    </td>
                    <td>{{ $event->location ?? '-' }}</td>
                    <td>
                        @if($event->status === 'upcoming')
                            <span class="badge badge-warning">Upcoming</span>
                        @elseif($event->status === 'ongoing')
                            <span class="badge badge-info">Ongoing</span>
                        @else
                            <span class="badge badge-approved">Completed</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-2" style="flex-wrap: wrap; align-items: center;">
                            @if($isOperator)
                                <a href="/ukm/events/{{ $event->id }}/participants" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-weight: 700; border-radius: var(--radius-md); display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.8125rem;">
                                    <i class="ph ph-users-three" style="font-size: 1.1rem;"></i> Kelola Anggota
                                </a>
                                <form action="/ukm/events/{{ $event->id }}" method="POST" onsubmit="return confirm('Hapus event ini?');" style="margin: 0; display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.5rem 0.75rem; border-radius: var(--radius-md); font-size: 0.8125rem; display: inline-flex; align-items: center; justify-content: center;"><i class="ph ph-trash" style="font-size: 1.1rem;"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if(count($events) == 0)
                <tr>
                    <td colspan="5" class="text-secondary text-center" style="padding: 2rem;">Belum ada data event.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.25rem;">
        {{ $events->links('shared.pagination') }}
    </div>
</div>

@endsection
