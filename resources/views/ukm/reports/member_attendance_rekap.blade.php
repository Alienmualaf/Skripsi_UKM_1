@extends('layouts.app')

@section('title', 'Rekap Absensi Anggota')
@section('header', 'Rekap Absensi Anggota')

@section('content')
<!-- Filter Card -->
<div class="card mb-4 animate-fade-in" style="border: 1px solid var(--border-color); border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <label style="font-weight: 700; font-size: 0.875rem; color: var(--text-secondary); white-space: nowrap;">
                    <i class="ph ph-funnel" style="color: var(--accent-color);"></i> Pilih Agenda:
                </label>
                <select class="form-control" style="border-radius: 10px; border: 1.5px solid var(--border-color); height: 42px; padding: 0 1rem; width: 250px; font-weight: 600;" onchange="window.location.href='/ukm/events/' + this.value + '/rekap/members'">
                    @foreach($events as $e)
                    <option value="{{ $e->id }}" {{ $e->id == $event->id ? 'selected' : '' }}>{{ $e->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Recap Content -->
<div class="card animate-fade-in" style="border: 1px solid var(--border-color); border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.015);">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-weight: 800; margin: 0; color: var(--text-color);">Rekap Kehadiran Anggota</h3>
        <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">
            Agenda: <strong>{{ $event->title }}</strong> • Total Pertemuan: <span style="color: var(--accent-color); font-weight: 700;">{{ $totalSessions }} Sesi</span>
        </p>
    </div>

    <div class="table-wrapper">
        <table class="table" style="vertical-align: middle;">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">No</th>
                    <th>Nama Anggota</th>
                    <th>Klasifikasi</th>
                    <th style="text-align: center; width: 100px;">Hadir</th>
                    <th style="text-align: center; width: 100px;">Izin</th>
                    <th style="text-align: center; width: 100px;">Tidak Hadir</th>
                    <th style="text-align: center; width: 150px;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $index => $item)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: var(--text-secondary);">{{ $index + 1 }}</td>
                    <td style="font-weight: 700; color: var(--text-color);">{{ $item['nama'] }}</td>
                    <td>
                        <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: 700; font-size: 0.725rem;">
                            {{ $item['klasifikasi'] }}
                        </span>
                    </td>
                    <td style="text-align: center; color: var(--success-color); font-weight: 800; font-size: 1.05rem;">
                        {{ $item['hadir'] }}
                    </td>
                    <td style="text-align: center; color: var(--warning-color); font-weight: 800; font-size: 1.05rem;">
                        {{ $item['izin'] }}
                    </td>
                    <td style="text-align: center; color: var(--danger-color); font-weight: 800; font-size: 1.05rem;">
                        {{ $item['tidak_hadir'] }}
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.25rem;">
                            @php
                                $percent = $item['persentase'];
                                $barColor = 'var(--danger-color)';
                                if ($percent >= 75) {
                                    $barColor = 'var(--success-color)';
                                } elseif ($percent >= 50) {
                                    $barColor = 'var(--warning-color)';
                                }
                            @endphp
                            <div style="font-weight: 800; font-size: 0.95rem; color: {{ $barColor }};">
                                {{ $percent }}%
                            </div>
                            <div style="width: 90px; height: 6px; background: rgba(0,0,0,0.05); border-radius: 3px; overflow: hidden;">
                                <div style="width: {{ $percent }}%; height: 100%; background: {{ $barColor }}; border-radius: 3px;"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                        <i class="ph ph-user-minus" style="font-size: 3rem; opacity: 0.2;"></i>
                        <p style="margin-top: 1rem; font-weight: 600;">Tidak ada data anggota terdaftar untuk agenda ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
