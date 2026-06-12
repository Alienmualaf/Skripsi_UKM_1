@extends('layouts.app')

@section('title', 'Kelola Anggota')
@section('header', 'Kelola Anggota PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Daftar Anggota Paduan Suara</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola klasifikasi suara, status keanggotaan (Aktif/Alumni), dan detail profil penyanyi.</p>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card" style="padding: 1.25rem; margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('ukm.members') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="width: 200px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Klasifikasi Suara</label>
            <select name="voice" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Suara</option>
                @foreach($classifications as $c)
                    <option value="{{ $c->id }}" {{ request('voice') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="width: 200px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Status Anggota</label>
            <select name="status" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="alumni" {{ request('status') === 'alumni' ? 'selected' : '' }}>Alumni</option>
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Anggota</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NPM..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['voice', 'status', 'search']))
            <a href="{{ route('ukm.members') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-users" style="color: var(--accent-color);"></i> Daftar Penyanyi PSUP
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $members->total() }} Anggota
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>NPM</th>
                    <th>Nama Lengkap</th>
                    <th>Fakultas</th>
                    <th>No. HP</th>
                    <th>Klasifikasi Suara</th>
                    <th>Status</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $member->npm ?? '-' }}</td>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $member->user->name ?? 'User dihapus' }}</td>
                    <td style="color: var(--text-secondary);">{{ $member->faculty ?? '-' }}</td>
                    <td style="color: var(--text-secondary);">{{ $member->phone ?? '-' }}</td>
                    <td>
                        @if($member->user && $member->user->role && $member->user->role->name !== 'anggota')
                            <span class="badge" style="background: #f1f5f9; color: #64748b; font-style: italic;">N/A (Bukan Anggota)</span>
                        @elseif($member->voiceClassification)
                            <span class="badge badge-info" style="background: var(--accent-light); color: var(--accent-color);">{{ $member->voiceClassification->name }}</span>
                        @else
                            <span class="badge" style="background: #f1f5f9; color: #475569;">Belum diklasifikasi</span>
                        @endif
                    </td>
                    <td>
                        @if($member->status === 'Anggota Aktif')
                            <span style="color: var(--success-color); font-weight: bold; font-size: 0.8125rem; display: flex; align-items: center; gap: 0.25rem;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--success-color); display: inline-block;"></span> Aktif
                            </span>
                        @else
                            <span style="color: var(--text-muted); font-weight: bold; font-size: 0.8125rem; display: flex; align-items: center; gap: 0.25rem;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #94a3b8; display: inline-block;"></span> Alumni
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('ukm.members.edit', $member->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;"><i class="ph ph-pencil-simple"></i> Edit</a>
                            
                            <form action="{{ route('ukm.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Keluarkan anggota ini dari PSUP? Akun pengguna tetap ada namun status keanggotaan terhapus.');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;"><i class="ph ph-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary py-4">Tidak ada data anggota ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.25rem;">
        {{ $members->links('shared.pagination') }}
    </div>
</div>
@endsection
