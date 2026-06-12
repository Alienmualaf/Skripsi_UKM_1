@extends('layouts.app')

@section('title', 'System Log')
@section('header', 'System Laravel Log')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Log Sistem (laravel.log)</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Menampilkan 100 baris terakhir berkas log internal Laravel untuk debugging & audit mendalam.</p>
    </div>
</div>

<div class="card" style="padding: 1.5rem; background: #1e1e1e; border: 1px solid #333; box-shadow: var(--shadow-lg);">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 0.75rem; margin-bottom: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span style="width: 12px; height: 12px; border-radius: 50%; background: #ff5f56; display: inline-block;"></span>
            <span style="width: 12px; height: 12px; border-radius: 50%; background: #ffbd2e; display: inline-block;"></span>
            <span style="width: 12px; height: 12px; border-radius: 50%; background: #27c93f; display: inline-block;"></span>
            <span style="color: #8e8e93; font-family: monospace; font-size: 0.75rem; margin-left: 0.5rem; font-weight: bold;">laravel.log - Terminal Output</span>
        </div>
        <button onclick="window.location.reload()" style="background: none; border: none; color: #0a84ff; font-weight: bold; font-size: 0.75rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;"><i class="ph ph-arrows-counter-clockwise"></i> Refresh</button>
    </div>

    <pre style="margin: 0; padding: 1rem; background: #000; border-radius: 8px; color: #4af626; font-family: 'Courier New', Courier, monospace; font-size: 0.8125rem; line-height: 1.5; overflow: auto; max-height: 600px; white-space: pre-wrap; word-break: break-all; border: 1px solid #222;">{{ $logContent }}</pre>
</div>
@endsection
