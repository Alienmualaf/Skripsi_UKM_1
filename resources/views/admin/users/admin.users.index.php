@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('header', 'Kelola Pengguna PSUP')

@section('content')
<style>
    /* Responsive Table Override for Mobile View */
    @media (max-width: 768px) {
        /* Reduce card padding on mobile for extra space */
        .card {
            padding: 1rem !important;
        }

        /* Force table elements to display as block */
        .responsive-table, 
        .responsive-table thead, 
        .responsive-table tbody, 
        .responsive-table th, 
        .responsive-table td, 
        .responsive-table tr { 
            display: block !important; 
            width: 100% !important;
            min-width: 0 !important;
            box-sizing: border-box;
        }
        
        /* Hide traditional table headers */
        .responsive-table thead { 
            display: none !important;
        }
        
        /* Format rows as cards */
        .responsive-table tr { 
            margin-bottom: 1.25rem;
            border: 1px solid var(--border-color) !important;
            border-radius: var(--radius-md) !important;
            padding: 0.75rem 1rem !important;
            background: var(--surface-color);
            box-shadow: var(--shadow-sm);
        }
        
        .responsive-table tr:hover td {
            background-color: transparent !important;
            color: inherit !important;
        }
        
        /* Style individual data cells */
        .responsive-table td { 
            text-align: right !important;
            padding: 0.6rem 0 !important;
            border-bottom: 1px solid var(--border-color) !important;
            position: relative;
            font-size: 0.85rem !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 1rem;
            min-height: 2.5rem;
            white-space: normal !important;
        }
        
        /* Remove bottom border from last cell in row card */
        .responsive-table td:last-child {
            border-bottom: none !important;
            padding-bottom: 0.25rem !important;
            margin-top: 0.5rem;
        }
        
        /* Display data-label as inline label on mobile */
        .responsive-table td::before { 
            content: attr(data-label);
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-align: left;
            flex-shrink: 0;
            max-width: 40%;
        }

        /* Value styling inside td for proper wrap */
        .responsive-table td > span,
        .responsive-table td > div {
            text-align: right !important;
            word-break: break-word;
            max-width: 60%;
            white-space: normal !important;
            display: inline-block;
        }

        /* Center action buttons container */
        .responsive-table td .btn-container {
            width: 100%;
            max-width: 100% !important;
            justify-content: flex-end !important;
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        /* Hide the old inline badges inside Nama cell since they are dedicated rows now */
        .show-mobile-inline {
            display: none !important;
        }
    }
</style>

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Manajemen Pengguna</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola seluruh akun pengguna, atur status aktivasi/suspend, dan lakukan reset sandi.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
        <i class="ph ph-user-plus" style="font-size: 1.2rem;"></i> Tambah Pengguna Baru
    </a>
</div>

<!-- Filters & Search -->
<div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
    <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; justify-content: space-between;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; flex: 1;">
            <!-- Search Input -->
            <div style="min-width: 280px; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Pengguna</label>
                <div style="position: relative;">
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem; pointer-events: none;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, username..." class="form-control" style="padding-left: 2.25rem !important; height: 2.5rem; font-size: 0.875rem;">
                </div>
            </div>
            
            <!-- Role Filter -->
            <div style="width: 180px;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Peran (Role)</label>
                <select name="role" class="form-control" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;" onchange="this.form.submit()">
                    <option value="">Semua Peran</option>
                    @foreach(\App\Models\Role::all() as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->display_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div style="width: 150px;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Status Akun</label>
                <select name="status" class="form-control" style="height: 2.5rem; font-size: 0.875rem; border-radius: 8px;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 0.5rem; height: 2.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0 1.25rem; font-weight: 700; border-radius: 8px;">Filter</button>
            @if(request()->anyFilled(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; justify-content: center; height: 2.5rem;">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-users" style="color: var(--accent-color);"></i> Daftar Pengguna Platform
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $users->total() }} Pengguna
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none; overflow: visible;">
        <table class="table responsive-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email / Username</th>
                    <th>Peran</th>
                    <th>Status</th>
                    <th>Login Terakhir</th>
                    <th>Dibuat Pada</th>
                    <th style="width: 260px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td data-label="Nama" style="font-weight: 700; color: var(--text-primary);">
                        <div style="display: flex; align-items: center; gap: 0.25rem; flex-wrap: wrap;">
                            <span>{{ $user->name }}</span>
                            @if(auth()->id() == $user->id)
                                <span style="font-size: 0.7rem; background: var(--accent-light); color: var(--accent-color); padding: 0.15rem 0.4rem; border-radius: 4px; margin-left: 0.25rem; display: inline-block;">Saya</span>
                            @endif
                        </div>
                        
                        <!-- Mobile-only badges (Fallback/deprecated, hidden by CSS when table is in card layout) -->
                        <div class="show-mobile-inline" style="gap: 0.35rem; margin-top: 0.35rem; flex-wrap: wrap;">
                            @if($user->isSuperAdmin())
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2); font-weight: 700;">{{ $user->role->display_name }}</span>
                            @elseif($user->isAdminUkm())
                                <span class="badge" style="background: #fff8e6; color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.25); font-weight: 700;">{{ $user->role->display_name }}</span>
                            @elseif($user->isPengurus())
                                <span class="badge" style="background: var(--accent-light); color: var(--accent-color); border: 1px solid rgba(30, 64, 175, 0.15); font-weight: 700;">{{ $user->role->display_name }}</span>
                            @else
                                <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 700;">{{ $user->role ? $user->role->display_name : 'Anggota' }}</span>
                            @endif

                            @if($user->status === 'active')
                                <span class="badge" style="background: var(--success-light); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2); font-weight: 700;">Aktif</span>
                            @elseif($user->status === 'suspended')
                                <span class="badge" style="background: var(--danger-light); color: var(--danger-color); border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 700;">Suspended</span>
                            @else
                                <span class="badge" style="background: #f1f5f9; color: var(--text-secondary); border: 1px solid var(--border-color); font-weight: 700;">Nonaktif</span>
                            @endif
                        </div>
                    </td>
                    <td data-label="Email / Username">
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">{{ explode('@', $user->email)[0] }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $user->email }}</div>
                        </div>
                    </td>
                    <td data-label="Peran">
                        @if($user->isSuperAdmin())
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2); font-weight: 700;">{{ $user->role->display_name }}</span>
                        @elseif($user->isAdminUkm())
                            <span class="badge" style="background: #fff8e6; color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.25); font-weight: 700;">{{ $user->role->display_name }}</span>
                        @elseif($user->isPengurus())
                            <span class="badge" style="background: var(--accent-light); color: var(--accent-color); border: 1px solid rgba(30, 64, 175, 0.15); font-weight: 700;">{{ $user->role->display_name }}</span>
                        @else
                            <span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 700;">{{ $user->role ? $user->role->display_name : 'Anggota' }}</span>
                        @endif
                    </td>
                    <td data-label="Status">
                        @if($user->status === 'active')
                            <span class="badge" style="background: var(--success-light); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2); font-weight: 700;">Aktif</span>
                        @elseif($user->status === 'suspended')
                            <span class="badge" style="background: var(--danger-light); color: var(--danger-color); border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 700;">Suspended</span>
                        @else
                            <span class="badge" style="background: #f1f5f9; color: var(--text-secondary); border: 1px solid var(--border-color); font-weight: 700;">Nonaktif</span>
                        @endif
                    </td>
                    <td data-label="Login Terakhir">
                        @php
                            $lastLogin = $user->loginHistories()->where('status', 'Success')->latest('login_at')->first();
                        @endphp
                        @if($lastLogin)
                            <span title="{{ $lastLogin->login_at->format('d M Y H:i:s') }}">{{ $lastLogin->login_at->diffForHumans() }}</span>
                        @else
                            <span style="color: var(--text-muted);">Belum pernah login</span>
                        @endif
                    </td>
                    <td data-label="Dibuat Pada">
                        <span>{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</span>
                    </td>
                    <td data-label="Aksi">
                        <div class="btn-container">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.25rem;" title="Edit Akun"><i class="ph ph-pencil-simple"></i> Edit</a>
                            
                            <!-- Force Reset Password -->
                            <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Reset sandi akun ini? Sandi baru otomatis menjadi \'password\'.');">
                                @csrf
                                <button type="submit" class="btn" style="background: #fffff0; border: 1px solid #faf089; padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: #b7791f; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;" title="Reset Sandi ke 'password'"><i class="ph ph-key"></i> Reset</button>
                            </form>

                            <!-- Delete Akun -->
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permanen pengguna ini? Seluruh profil anggota terkait akan dilepas.');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;" title="Hapus Akun"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary py-4">Tidak ada data pengguna ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.25rem;">
        {{ $users->links('shared.pagination') }}
    </div>
</div>
@endsection
