@extends('layouts.app')

@section('title', 'Riwayat Absensi')
@section('header', $event->title)

@section('content')
<div class="card animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="font-weight: 700; font-size: 1.5rem;">Riwayat Presensi Agenda</h3>
            <p style="color: var(--text-secondary);">Berikut adalah data kehadiran seluruh peserta untuk agenda <strong>{{ $event->title }}</strong>.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Anggota</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td style="font-weight: 600;">{{ $att->user->name }}</td>
                    <td style="text-align: center;">
                        @if($att->status == 'hadir')
                            <span class="badge badge-success" style="padding: 0.5rem 1rem;">Hadir</span>
                        @elseif($att->status == 'izin')
                            <span class="badge badge-warning" style="padding: 0.5rem 1rem;">Izin</span>
                        @else
                            <span class="badge badge-danger" style="padding: 0.5rem 1rem;">Tidak Hadir</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center; padding: 4rem; color: var(--text-secondary);">Presensi belum diinput oleh admin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
