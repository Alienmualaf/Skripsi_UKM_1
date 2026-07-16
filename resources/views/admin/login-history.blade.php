@extends('layouts.app')

@section('title', 'Login History')
@section('header', 'Login History')

@section('content')
<style>
@media (max-width: 768px) {
  .table-login-compact th:nth-child(3),
  .table-login-compact td:nth-child(3),
  .table-login-compact th:nth-child(4),
  .table-login-compact td:nth-child(4),
  .table-login-compact th:nth-child(5),
  .table-login-compact td:nth-child(5),
  .table-login-compact th:nth-child(6),
  .table-login-compact td:nth-child(6) {
    display: none !important;
  }
}
</style>
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Riwayat Login</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Catatan riwayat sesi login, logout, dan deteksi kegagalan autentikasi pengguna.</p>
    </div>
</div>

<div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
    <form action="{{ route('admin.logs.login') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="min-width: 320px; flex: 1;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Sesi</label>
            <div style="position: relative;">
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem; pointer-events: none;"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari username, IP address, status..." class="form-control" style="padding-left: 2.25rem !important; height: 2.5rem; font-size: 0.875rem;">
            </div>
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
        <table class="table table-login-compact">
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
                    <td style="font-weight: 700; color: var(--text-primary);">
                        <div style="margin-bottom: 0.15rem;">{{ $log->username }}</div>
                        
                        <!-- Mobile device/browser info fallback -->
                        <div class="show-mobile-inline" style="gap: 0.25rem; opacity: 0.85;">
                            <span class="badge" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); font-size: 0.6rem; padding: 0.1rem 0.35rem;">
                                <i class="ph ph-{{ strtolower($log->device) == 'mobile' ? 'phone' : (strtolower($log->device) == 'tablet' ? 'tablet' : 'monitor') }}" style="font-size: 0.7rem; vertical-align: middle;"></i> {{ $log->device }}
                            </span>
                        </div>
                    </td>
                    <td style="color: var(--text-secondary); font-size: 0.8125rem;">
                        @if($log->login_at)
                            <span class="hidden-mobile">{{ $log->login_at->format('d-m-Y H:i:s') }}</span>
                            <div class="show-mobile-block" style="font-size: 0.75rem; line-height: 1.2;">
                                <div>{{ $log->login_at->format('d-m-Y') }}</div>
                                <div style="opacity: 0.7;">{{ $log->login_at->format('H:i:s') }}</div>
                            </div>
                        @else
                            -
                        @endif
                    </td>
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
