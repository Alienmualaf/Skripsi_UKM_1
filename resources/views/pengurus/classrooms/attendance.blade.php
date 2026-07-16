@extends('layouts.app')

@php
    $isJob = isset($job);
    $backUrl = $isJob ? route('pengurus.jobs.classroom.show', [$job->id]) : route('pengurus.programs.performance.classroom.show', [$programId, $performanceId]);
    $saveUrl = $isJob ? route('pengurus.jobs.classroom.attendance.save', [$job->id, $attendance->id]) : route('pengurus.programs.performance.classroom.attendance.save', [$programId, $performanceId, $attendance->id]);
@endphp

@section('title', 'Absensi - ' . $attendance->title)
@section('header', 'Isi Absensi')
@section('content')
@if(session('success'))
<div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:0.85rem 1.25rem;border-radius:8px;font-weight:600;margin-bottom:1rem;"><i class="ph ph-check-circle"></i> {{ session('success') }}</div>
@endif
<div style="margin-bottom:1.5rem;">
    <a href="{{ $backUrl }}" style="display:inline-flex;align-items:center;gap:0.35rem;color:var(--accent-color);text-decoration:none;font-weight:600;font-size:0.875rem;margin-bottom:0.25rem;"><i class="ph ph-arrow-left"></i> Kembali ke Pusat Latihan</a>
    <h3 style="font-size:1.2rem;font-weight:800;color:var(--text-primary);margin:0;">{{ $attendance->title }}</h3>
    <p style="margin:0.25rem 0 0;color:var(--text-secondary);font-size:0.875rem;">
        Jenis: <strong>{{ $attendance->type }}</strong> &nbsp;|&nbsp; Tanggal: <strong>{{ date('d M Y', strtotime($attendance->date)) }}</strong>
    </p>
</div>
<div class="card" style="padding:1.5rem;">
    <form action="{{ $saveUrl }}" method="POST">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Anggota</th>
                    <th>Klasifikasi Suara</th>
                    <th style="width:200px;">Status Kehadiran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendance->details as $i => $detail)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td style="font-weight:600;">{{ $detail->member->name }}</td>
                    <td style="color:var(--text-secondary);">{{ $detail->member->voiceClassification->name ?? '-' }}</td>
                    <td>
                        <select name="statuses[{{ $detail->member_id }}]" class="form-control" style="padding:0.35rem 0.5rem;font-size:0.8125rem;height:auto;border-radius:6px;">
                            @foreach(['Hadir','Izin','Sakit','Alpha'] as $s)
                            <option value="{{ $s }}" {{ $detail->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="notes[{{ $detail->member_id }}]" class="form-control" value="{{ $detail->notes }}" placeholder="Opsional..." style="padding:0.35rem 0.5rem;font-size:0.8125rem;height:auto;"></td>
                </tr>
                @endforeach
                @if($attendance->details->isEmpty())
                <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">Tidak ada peserta. Tambahkan peserta di tab Peserta terlebih dahulu.</td></tr>
                @endif
            </tbody>
        </table>
        @if($attendance->details->isNotEmpty())
        <div style="margin-top:1.25rem;display:flex;gap:0.75rem;">
            <button type="submit" class="btn btn-primary" style="padding:0.65rem 1.5rem;font-weight:700;border-radius:8px;"><i class="ph ph-floppy-disk"></i> Simpan Absensi</button>
            <a href="{{ $backUrl }}" class="btn" style="background:var(--bg-color);border:1px solid var(--border-color);padding:0.65rem 1.25rem;font-weight:700;border-radius:8px;text-decoration:none;color:var(--text-primary);">Batal</a>
        </div>
        @endif
    </form>
</div>
@endsection
