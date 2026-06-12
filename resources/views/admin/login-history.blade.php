@extends('layouts.app')

@section('title', 'Login History')
@section('header', 'Login History')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Riwayat Login</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Catatan riwayat sesi login, logout, dan deteksi kegagalan autentikasi pengguna.</p>
    </div>
</div>

<div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
    <form action="{{ route('admin.logs.login') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="position: relative; min-width: 320px; flex: 1;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Sesi</label>
            <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari username, IP address, status..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
        </div>
        <div style="display: flex; gap: 0.5rem; height: 2.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0 1.25rem; font-weight: 700; border-radius: 8px;">Filter</button>
            @if($search)
                <a href="{{ route('admin.logs.login') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; justify-content: center; height: 2.5rem;">Reset</a>
            @endif
        </div>
    </form>
</div>

<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-sign-in" style="color: var(--accent-color);"></i> Log Sesi Autentikasi
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $logs->total() }} record
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Waktu Login</th>
                    <th>Waktu Logout</th>
                    <th>IP Address</th>
                    <th>Perangkat</th>
                    <th>Browser</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $log->username }}</td>
                    <td style="color: var(--text-secondary); font-size: 0.8125rem;">{{ $log->login_at ? $log->login_at->format('d-m-Y H:i:s') : '-' }}</td>
                    <td style="color: var(--text-secondary); font-size: 0.8125rem;">{{ $log->logout_at ? $log->logout_at->format('d-m-Y H:i:s') : '-' }}</td>
                    <td style="font-family: monospace; font-size: 0.8125rem; color: var(--text-secondary);">{{ $log->ip_address }}</td>
                    <td>
                        <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                            <i class="ph ph-{{ strtolower($log->device) == 'mobile' ? 'phone' : (strtolower($log->device) == 'tablet' ? 'tablet' : 'monitor') }}"></i>
                            {{ $log->device }}
                        </span>
                    </td>
                    <td style="color: var(--text-secondary);">{{ $log->browser }}</td>
                    <td>
                        @if($log->status === 'Success')
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16,185,129,0.15); font-weight: bold;">Berhasil</span>
                        @elseif($log->status === 'Logout')
                            <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: bold;">Selesai</span>
                        @else
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid rgba(239,68,68,0.15); font-weight: bold;">Gagal</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-secondary); padding: 2rem;">Tidak ada log riwayat login ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $logs->links('shared.pagination') }}
    </div>
</div>
@endsection
