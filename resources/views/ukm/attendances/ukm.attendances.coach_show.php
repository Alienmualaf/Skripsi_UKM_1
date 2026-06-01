@extends('layouts.app')

@section('title', 'Input Absensi Pelatih')
@section('header', 'Absensi: ' . $event->title)

@section('content')
<div class="card animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="{{ route('coach-attendances.index') }}" style="color: var(--accent-color); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; margin-bottom: 0.5rem; text-decoration: none;">
                <i class="ph ph-arrow-left"></i> Kembali ke Daftar
            </a>
            <h3 style="font-weight: 700; font-size: 1.25rem;">Presensi Pelatih & BPH</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">{{ $event->title }} - {{ $event->start_date->format('d M Y') }}</p>
        </div>
    </div>

    <form action="{{ route('coach-attendances.store', $event->id) }}" method="POST">
        @csrf
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pelatih / BPH</th>
                        <th>Kategori</th>
                        <th style="text-align: center;">Hadir</th>
                        <th style="text-align: center;">Izin</th>
                        <th style="text-align: center;">Tidak Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coaches as $coach)
                    @php
                        $status = $attendances[$coach->id]->status ?? 'tidak hadir';
                    @endphp
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                @if($coach->photo)
                                    <img src="{{ asset('storage/' . $coach->photo) }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--bg-color); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">{{ substr($coach->name, 0, 1) }}</div>
                                @endif
                                <span style="font-weight: 600;">{{ $coach->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $coach->category == 'pelatih' ? 'badge-primary' : ($coach->category == 'bph' ? 'badge-success' : 'badge-warning') }}" style="text-transform: capitalize; font-size: 0.7rem;">
                                {{ $coach->category }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <input type="radio" name="attendances[{{ $coach->id }}]" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }} style="width: 1.25rem; height: 1.25rem; accent-color: var(--success-color);">
                        </td>
                        <td style="text-align: center;">
                            <input type="radio" name="attendances[{{ $coach->id }}]" value="izin" {{ $status == 'izin' ? 'checked' : '' }} style="width: 1.25rem; height: 1.25rem; accent-color: var(--warning-color);">
                        </td>
                        <td style="text-align: center;">
                            <input type="radio" name="attendances[{{ $coach->id }}]" value="tidak hadir" {{ $status == 'tidak hadir' ? 'checked' : '' }} style="width: 1.25rem; height: 1.25rem; accent-color: var(--danger-color);">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                            Belum ada pelatih yang dipilih untuk agenda ini.<br>
                            <a href="/ukm/events/{{ $event->id }}/participants" style="color: var(--accent-color); font-weight: 600; text-decoration: none;">Pilih pelatih di sini &rarr;</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(!$coaches->isEmpty())
        <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem; font-weight: 700;">Simpan Presensi</button>
        </div>
        @endif
    </form>
</div>
@endsection
