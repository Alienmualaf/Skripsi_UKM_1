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

<style>
    .reg-action-container {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        width: 100%;
        max-width: 130px;
        margin: 0 auto;
    }
    .reg-btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        width: 100%;
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
        .th-action-compact {
            width: 110px !important;
        }
        .table td {
            padding: 0.5rem 0.35rem !important;
            font-size: 0.75rem !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }
        .table th {
            padding: 0.5rem 0.35rem !important;
            font-size: 0.725rem !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }
        .reg-action-container {
            max-width: 100% !important;
        }
        .reg-action-container select {
            padding: 0 0.25rem !important;
            height: 28px !important;
            font-size: 0.7rem !important;
            border-radius: 6px !important;
        }
        .reg-btn-group {
            flex-direction: row !important;
            gap: 0.25rem !important;
        }
        .reg-btn-group button {
            flex: 1 !important;
            height: 28px !important;
            padding: 0 !important;
            font-size: 0.85rem !important;
            border-radius: 6px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
    }
</style>

<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Verifikasi Pendaftaran Calon Anggota</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Periksa formulir pendaftaran mahasiswa baru yang ingin bergabung dengan Paduan Suara Universitas Pancasila.</p>
</div>

<div class="card" style="padding: 1.25rem;">
    <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-user-plus" style="color: var(--accent-color);"></i> Calon Anggota Baru
    </h4>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table" style="vertical-align: middle;">
            <thead>
                <tr>
                    <th style="width: 60px;">Foto</th>
                    <th>Nama Calon</th>
                    <th class="hidden-mobile">Email</th>
                    <th>NPM / Fakultas</th>
                    <th class="hidden-mobile" style="max-width: 250px;">Pengalaman Paduan Suara</th>
                    <th class="hidden-mobile">Status</th>
                    <th class="th-action-compact" style="width: 200px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $reg)
                <tr>
                    <td>
                        @if($reg->photo)
                            <a href="{{ asset('storage/' . $reg->photo) }}" target="_blank">
                                <img src="{{ asset('storage/' . $reg->photo) }}" alt="Foto {{ $reg->name }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        @else
                            <div style="width: 45px; height: 45px; border-radius: 8px; background: var(--bg-color); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.25rem;">
                                <i class="ph ph-user"></i>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $reg->name }}</td>
                    <td class="hidden-mobile" style="color: var(--text-secondary);">
                        {{ $reg->email }}<br>
                        <small style="color: var(--text-muted);">{{ $reg->phone }}</small>
                    </td>
                    <td style="color: var(--text-primary);">
                        <span style="font-weight: 600;">{{ $reg->npm }}</span><br>
                        <small style="color: var(--text-secondary);">{{ $reg->faculty }} ({{ $reg->class_year }})</small>
                        
                        <!-- Mobile-only status badge -->
                        <div class="show-mobile-block" style="margin-top: 0.15rem;">
                            @if($reg->status === 'Calon Anggota')
                                <span class="badge badge-warning" style="background: #fff8e6; color: #f59e0b; font-weight: bold; border: 1px solid rgba(245,158,11,0.15); padding: 0.1rem 0.25rem; border-radius: 4px; font-size: 0.65rem;">Pending</span>
                            @elseif($reg->status === 'Anggota Aktif')
                                <span class="badge badge-success" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); font-weight: bold; border: 1px solid rgba(16,185,129,0.15); padding: 0.1rem 0.25rem; border-radius: 4px; font-size: 0.65rem;">Diterima</span>
                            @else
                                <span class="badge badge-danger" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color); font-weight: bold; border: 1px solid rgba(239,68,68,0.15); padding: 0.1rem 0.25rem; border-radius: 4px; font-size: 0.65rem;">Ditolak</span>
                            @endif
                        </div>
                    </td>
                    <td class="hidden-mobile" style="color: var(--text-secondary); max-width: 250px;">
                        <div style="font-size: 0.8rem; line-height: 1.45; max-height: 50px; overflow-y: auto; word-break: break-word;">
                            {{ $reg->choir_experience ?? 'Tidak ada pengalaman musik/padus.' }}
                        </div>
                    </td>
                    <td class="hidden-mobile">
                        @if($reg->status === 'Calon Anggota')
                            <span class="badge badge-warning" style="background: #fff8e6; color: #f59e0b; font-weight: bold; border: 1px solid rgba(245,158,11,0.15); padding: 0.25rem 0.5rem; border-radius: 6px;">Pending</span>
                        @elseif($reg->status === 'Anggota Aktif')
                            <span class="badge badge-success" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); font-weight: bold; border: 1px solid rgba(16,185,129,0.15); padding: 0.25rem 0.5rem; border-radius: 6px;">Diterima</span>
                        @else
                            <span class="badge badge-danger" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color); font-weight: bold; border: 1px solid rgba(239,68,68,0.15); padding: 0.25rem 0.5rem; border-radius: 6px;">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($reg->status === 'Calon Anggota')
                        <!-- Hidden forms for HTML5 submission -->
                        <form id="accept-form-{{ $reg->id }}" action="{{ route('ukm.registrations.verify', $reg->id) }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="action" value="Terima">
                        </form>
                        <form id="reject-form-{{ $reg->id }}" action="{{ route('ukm.registrations.verify', $reg->id) }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="action" value="Tolak">
                        </form>

                        <div class="reg-action-container">
                            <select name="voice_classification_id" form="accept-form-{{ $reg->id }}" required style="padding: 0.4rem; border: 1px solid var(--border-color); border-radius: var(--radius-sm); font-size: 0.75rem; background: var(--surface-color); color: var(--text-primary); font-weight: 600; width: 100%; cursor: pointer;">
                                <option value="" disabled selected>Pilih Suara</option>
                                @foreach($voiceClassifications as $vc)
                                    <option value="{{ $vc->id }}">{{ $vc->name }}</option>
                                @endforeach
                            </select>
                            <div class="reg-btn-group">
                                <button form="accept-form-{{ $reg->id }}" type="submit" class="btn btn-primary" style="padding: 0.4rem 0.5rem; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                                    <i class="ph ph-check"></i> <span class="hidden-mobile">Setujui</span>
                                </button>
                                <button form="reject-form-{{ $reg->id }}" type="submit" class="btn btn-danger" style="padding: 0.4rem 0.5rem; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.25rem;">
                                    <i class="ph ph-x"></i> <span class="hidden-mobile">Tolak</span>
                                </button>
                            </div>
                        </div>
                        @else
                        <div style="text-align: center; color: var(--text-muted); font-size: 0.75rem; font-weight: bold;">Selesai</div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary py-4">Belum ada pendaftaran masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
