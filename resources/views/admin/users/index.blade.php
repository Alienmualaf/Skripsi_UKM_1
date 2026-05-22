@extends('layouts.app')

@section('title', 'Kelola Users')
@section('header', 'Data Pengguna')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Daftar Pengguna Platform</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Super Admin mengelola seluruh akun pengguna dan menentukan hak akses/peran dalam platform.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
        <i class="ph ph-user-plus"></i> Tambah User Baru
    </a>
</div>

<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-users" style="color: var(--accent-color);"></i> Keseluruhan Pengguna
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            <i class="ph ph-database" style="margin-right: 0.25rem;"></i> Total {{ count($users) }} Pengguna
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                @php
                    $adminMembership = $user->memberships()->where('role_in_ukm', 'admin')->first();
                @endphp
                <tr>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $user->name }}</td>
                    <td style="color: var(--text-secondary);">{{ $user->email }}</td>
                    <td>
                        @if($user->role == 'super_admin')
                            <span class="badge badge-approved" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2);">Super Admin</span>
                        @elseif($adminMembership)
                            <span class="badge badge-warning" style="background: #fff8e6; color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.25);">
                                Admin UKM: {{ $adminMembership->ukm->name ?? '-' }}
                            </span>
                        @else
                            <span class="badge badge-info" style="background: var(--accent-light); color: var(--accent-color); border: 1px solid rgba(30, 64, 175, 0.15);">
                                User Biasa
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;"><i class="ph ph-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
