@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('header', 'Activity Log')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Aktivitas Pengguna</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Catatan detail aktivitas operasional yang dilakukan oleh seluruh aktor sistem.</p>
    </div>
</div>

<div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
    <form action="{{ route('admin.logs.activity') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="position: relative; min-width: 320px; flex: 1;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Aktivitas</label>
            <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari deskripsi, operator, modul..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
        </div>
        <div style="display: flex; gap: 0.5rem; height: 2.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0 1.25rem; font-weight: 700; border-radius: 8px;">Filter</button>
            @if($search)
                <a href="{{ route('admin.logs.activity') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; justify-content: center; height: 2.5rem;">Reset</a>
            @endif
        </div>
    </form>
</div>

<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-clock-counter-clockwise" style="color: var(--accent-color);"></i> Log Operasional Sistem
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $logs->total() }} record
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 180px;">Waktu</th>
                    <th>Operator</th>
                    <th>Role</th>
                    <th>Modul</th>
                    <th>Aktivitas</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="color: var(--text-secondary); font-size: 0.8125rem;">{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $log->username }}</td>
                    <td><span class="badge" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); font-size: 0.7rem;">{{ $log->role }}</span></td>
                    <td><span class="badge badge-info" style="background: var(--accent-light); color: var(--accent-color); font-weight: bold;">{{ $log->module }}</span></td>
                    <td style="color: var(--text-primary); font-weight: 500;">{{ $log->activity }}</td>
                    <td style="font-family: monospace; font-size: 0.8125rem; color: var(--text-secondary);">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 2rem;">Tidak ada log aktivitas ditemukan.</td>
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
