@extends('layouts.app')

@section('title', 'Input Absensi Sesi')
@section('header', 'Absensi: ' . $session->title)

@section('content')
<div class="card mb-4 animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h4 style="margin: 0; font-weight: 700;">{{ $session->title }}</h4>
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.25rem;">
                <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem;">{{ $session->date->format('d M Y') }} • {{ $event->title }}</p>
                <span class="badge {{ $session->is_open ? 'badge-success' : 'badge-danger' }}" style="font-size: 0.7rem; padding: 2px 8px;">
                    <i class="ph-fill {{ $session->is_open ? 'ph-circle' : 'ph-lock' }}" style="font-size: 0.6rem;"></i> 
                    Absensi {{ $session->is_open ? 'Dibuka' : 'Ditutup' }}
                </span>
            </div>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            @if($isUKMAdmin)
                <form action="{{ route('ukm.sessions.attendance.toggle', $session->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn {{ $session->is_open ? 'btn-danger' : 'btn-success' }}" style="font-weight: 700;">
                        <i class="ph {{ $session->is_open ? 'ph-lock' : 'ph-lock-open' }}"></i> 
                        {{ $session->is_open ? 'Tutup Absensi' : 'Buka Absensi' }}
                    </button>
                </form>
            @endif
            <a href="{{ $isMember ? '/room/'.$event->ukm_id.'/classroom/'.$event->id.'?tab=attendances' : '/ukm/classroom/'.$event->id.'?tab=attendances' }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color);">
                <i class="ph ph-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

@if(!$isUKMAdmin && !$session->is_open)
    <div class="card mb-4" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; display: flex; align-items: center; gap: 1rem; padding: 1.5rem;">
        <i class="ph-fill ph-warning-circle" style="font-size: 2rem;"></i>
        <div>
            <h4 style="margin: 0; font-weight: 800;">Absensi Telah Ditutup</h4>
            <p style="margin: 0; font-size: 0.875rem; opacity: 0.8;">Admin telah menutup sesi absensi ini. Anda tidak dapat melakukan pengisian atau perubahan.</p>
        </div>
    </div>
@endif

    <form action="{{ $isMember ? route('member.sessions.attendance', $session->id) : route('ukm.sessions.attendance', $session->id) }}" method="POST">
        @csrf
        
        <!-- Section Peserta -->
        <div id="section-members" class="attendance-section">
            <div class="card animate-fade-in">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="font-weight: 700; margin: 0;"><i class="ph ph-users"></i> Daftar Peserta</h3>
                    @if($isMember)
                        <span class="badge badge-primary">Sesi Mandiri</span>
                    @endif
                </div>

                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Klasifikasi</th>
                                <th style="text-align: center;">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            @php
                                $attendance = $memberAttendances->get($member->user_id);
                                $currentStatus = $attendance ? $attendance->status : null;
                                $isLocked = $isMember && $attendance;
                                $isOwnRow = $member->user_id == auth()->id();
                                $isDisabled = ($isMember && !$isOwnRow) || ($isMember && !$session->is_open) || $isLocked;
                            @endphp
                            
                            @if(!$isMember || $isOwnRow)
                            <tr style="{{ $isOwnRow ? 'background: var(--accent-light);' : '' }}">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div style="font-weight: 700;">{{ $member->user->name }}</div>
                                        @if($isOwnRow) <span class="badge badge-success" style="font-size: 0.6rem;">ANDA</span> @endif
                                    </div>
                                </td>
                                <td>{{ optional($member->classification)->name ?: '-' }}</td>
                                <td>
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                        @if($isLocked && $isOwnRow)
                                            <div style="color: var(--success-color); font-weight: 800; font-size: 0.875rem; display: flex; align-items: center; gap: 0.4rem;">
                                                <i class="ph-fill ph-lock-key"></i> {{ ucfirst($currentStatus) }} (Sudah Dikunci)
                                            </div>
                                            <input type="hidden" name="attendances[{{ $member->user_id }}]" value="{{ $currentStatus }}">
                                        @elseif($isMember && !$session->is_open && $isOwnRow && !$currentStatus)
                                            <div style="color: var(--danger-color); font-weight: 700; font-size: 0.8125rem;">Ditutup</div>
                                        @else
                                            <div style="display: flex; gap: 1rem; justify-content: center;">
                                                @foreach(['hadir' => 'var(--success-color)', 'izin' => 'var(--warning-color)', 'tidak hadir' => 'var(--danger-color)'] as $val => $color)
                                                <label style="cursor: pointer; display: flex; align-items: center; gap: 0.4rem; font-weight: 600; font-size: 0.875rem; {{ $isDisabled ? 'opacity: 0.5; cursor: not-allowed;' : '' }}">
                                                    <input type="radio" name="attendances[{{ $member->user_id }}]" value="{{ $val }}" 
                                                        {{ $currentStatus === $val ? 'checked' : '' }} 
                                                        {{ $isDisabled ? 'disabled' : '' }}
                                                        style="width: 1.1rem; height: 1.1rem; accent-color: {{ $color }};"> {{ ucfirst($val) }}
                                                </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="sticky-save" style="position: sticky; bottom: 2rem; display: flex; flex-direction: column; align-items: center; gap: 1rem; z-index: 10;">
            @if($isMember)
                @php
                    $myAttendance = $memberAttendances->get(auth()->id());
                @endphp
                @if($myAttendance)
                    <div class="lock-indicator">
                        <i class="ph-fill ph-lock-key"></i> Absensi sudah dikunci
                    </div>
                @elseif(!$session->is_open)
                    <div class="lock-indicator" style="border-color: var(--danger-color); color: var(--danger-color);">
                        <i class="ph-fill ph-lock"></i> Absensi telah ditutup
                    </div>
                @else
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="ph-fill ph-floppy-disk"></i> Simpan Absensi Saya
                    </button>
                @endif
            @else
                <button type="submit" class="btn btn-primary btn-save">
                    <i class="ph-fill ph-floppy-disk"></i> Simpan Semua Perubahan
                </button>
            @endif
        </div>
    </form>

<style>
    .lock-indicator {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        padding: 0.75rem 2rem;
        border-radius: 50px;
        border: 1px solid var(--success-color);
        color: var(--success-color);
        font-weight: 800;
        font-size: 0.9rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .btn-save {
        padding: 1rem 3.5rem;
        border-radius: 50px;
        font-weight: 800;
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
</style>
@endsection
