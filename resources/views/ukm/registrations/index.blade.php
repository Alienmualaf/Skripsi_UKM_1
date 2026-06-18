@extends('layouts.app')

@section('title', 'Pendaftaran Calon Anggota')
@section('header', 'Pendaftaran Calon Anggota')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Verifikasi Pendaftaran Calon Anggota</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Periksa formulir pendaftaran mahasiswa baru yang ingin bergabung dengan Paduan Suara Universitas Pancasila.</p>
</div>

<div class="card" style="padding: 1.5rem;">
    <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-user-plus" style="color: var(--accent-color);"></i> Calon Anggota Baru
    </h4>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Calon</th>
                    <th>Email</th>
                    <th>NPM</th>
                    <th>Fakultas</th>
                    <th>Vokal / Pengalaman</th>
                    <th>Alasan Bergabung</th>
                    <th>Status</th>
                    <th style="width: 200px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $reg)
                <tr>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $reg->name }}</td>
                    <td style="color: var(--text-secondary);">{{ $reg->email }}</td>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $reg->npm }}</td>
                    <td style="color: var(--text-secondary);">{{ $reg->faculty }}</td>
                    <td style="color: var(--text-primary);">
                        <div style="font-weight: bold; font-size: 0.8rem; color: var(--accent-color);">{{ $reg->voice_range }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $reg->experience }}">{{ $reg->experience ?? '-' }}</div>
                    </td>
                    <td style="color: var(--text-secondary); font-size: 0.75rem; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $reg->reason }}">{{ $reg->reason }}</td>
                    <td>
                        @if($reg->status === 'Pending')
                            <span class="badge badge-warning" style="background: #fff8e6; color: #f59e0b; font-weight: bold; border: 1px solid rgba(245,158,11,0.15); padding: 0.25rem 0.5rem; border-radius: 6px;">Pending</span>
                        @elseif($reg->status === 'Terima')
                            <span class="badge badge-success" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); font-weight: bold; border: 1px solid rgba(16,185,129,0.15); padding: 0.25rem 0.5rem; border-radius: 6px;">Diterima</span>
                        @else
                            <span class="badge badge-danger" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color); font-weight: bold; border: 1px solid rgba(239,68,68,0.15); padding: 0.25rem 0.5rem; border-radius: 6px;">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($reg->status === 'Pending')
                        <div style="display: flex; flex-direction: column; gap: 0.35rem; justify-content: center; align-items: stretch; width: 100%;">
                            <form action="{{ route('ukm.registrations.verify', $reg->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 0.25rem;">
                                @csrf
                                <input type="hidden" name="action" value="Terima">
                                <select name="voice_classification_id" required style="padding: 0.4rem; border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-size: 0.75rem; background: var(--surface-color); color: var(--text-primary); font-weight: 600; width: 100%; cursor: pointer;">
                                    <option value="" disabled selected>Pilih Suara</option>
                                    @foreach($voiceClassifications as $vc)
                                        <option value="{{ $vc->id }}">{{ $vc->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 700;"><i class="ph ph-check"></i> Setujui</button>
                            </form>
                            <form action="{{ route('ukm.registrations.verify', $reg->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="Tolak">
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 700; width: 100%;"><i class="ph ph-x"></i> Tolak</button>
                            </form>
                        </div>
                        @else
                        <div style="text-align: center; color: var(--text-muted); font-size: 0.75rem; font-weight: bold;">Selesai Diproses</div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-secondary py-4">Belum ada formulir pendaftaran masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
