@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Kelola Anggota UKM')
@section('header', 'Kelola Anggota')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #10b981;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="card mb-4 animate-fade-in" style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #ef4444;">
        <i class="ph-fill ph-x-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="margin: 0; font-weight: 800;">Daftar Pendaftar & Anggota</h3>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>Role di UKM</th>
                    <th>Status</th>
                    <th>Klasifikasi Anggota</th>
                    @if($isOperator)
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr>
                    <td>{{ optional($member->user)->name ?? 'User dihapus' }}</td>
                    <td>{{ optional($member->user)->email ?? '-' }}</td>
                    <td>
                        @if($isOperator && $member->status == 'approved')
                            <form action="/ukm/members/{{ $member->id }}/role" method="POST" style="margin: 0; display: inline-block;">
                                @csrf @method('PUT')
                                <select name="role_in_ukm" class="form-control" style="padding: 0.25rem 0.5rem; font-size: 0.875rem; width: auto;" onchange="this.form.submit()">
                                    <option value="admin" {{ $member->role_in_ukm == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="member" {{ $member->role_in_ukm == 'member' ? 'selected' : '' }}>Anggota</option>
                                </select>
                            </form>
                        @else
                            @if($member->role_in_ukm == 'admin')
                                <span class="badge badge-admin">Admin</span>
                            @else
                                <span class="badge badge-member">Anggota</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $member->status == 'approved' ? 'badge-approved' : 'badge-pending' }}">
                            {{ ucfirst($member->status) }}
                        </span>
                    </td>
                    <td>
                        @if($isOperator && $member->status === 'approved')
                            <form action="/ukm/members/{{ $member->id }}/classification" method="POST" style="display: flex; gap: 0.5rem;">
                                @csrf @method('PUT')
                                <select name="ukm_classification_id" class="form-control" style="padding: 0.25rem 0.5rem; font-size: 0.875rem; width: auto;" onchange="this.form.submit()">
                                    <option value="">- Belum Ditentukan -</option>
                                    @foreach($classifications as $c)
                                        <option value="{{ $c->id }}" {{ $member->ukm_classification_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @else
                            {{ $member->classification->name ?? '-' }}
                        @endif
                    </td>
                    @if($isOperator)
                    <td>
                        <div class="flex gap-2">
                            @if($member->status == 'pending')
                            <form action="/ukm/members/{{ $member->id }}/approve" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;"><i class="ph ph-check"></i> Terima</button>
                            </form>
                            @endif
                            
                            @if($member->role_in_ukm != 'admin')
                            <form action="/ukm/members/{{ $member->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus/menolak anggota ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;"><i class="ph ph-x"></i> Tolak / Keluarkan</button>
                            </form>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
