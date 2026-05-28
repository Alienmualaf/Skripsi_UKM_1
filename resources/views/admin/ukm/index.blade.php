@extends('layouts.app')

@section('title', 'Kelola UKM')
@section('header', 'Kelola UKM')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Daftar Unit Kegiatan Mahasiswa</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Super Admin mengawasi data UKM secara global, melihat detail, dan melakukan koreksi data yang diperlukan.</p>
    </div>
    <a href="{{ route('ukm.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
        <i class="ph ph-plus-circle"></i> Tambah UKM Baru
    </a>
</div>

<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-house-line" style="color: var(--accent-color);"></i> Keseluruhan UKM Terdaftar
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            <i class="ph ph-database" style="margin-right: 0.25rem;"></i> Total {{ $ukms->total() }} UKM
        </span>
    </div>

    <form action="{{ url('/admin/ukm') }}" method="GET" style="margin-bottom: 1.25rem; display: flex; gap: 0.5rem; max-width: 400px; width: 100%;">
        <div style="position: relative; flex: 1;">
            <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari UKM..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
        </div>
        <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        @if(request('search'))
            <a href="{{ url('/admin/ukm') }}" class="btn btn-secondary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Reset</a>
        @endif
    </form>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama UKM</th>
                    <th>Deskripsi Singkat</th>
                    <th>Jumlah Anggota</th>
                    <th>Admin Saat Ini</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ukms as $ukm)
                <tr>
                    <td style="font-weight: 600; color: var(--text-primary);">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 32px; height: 32px; border-radius: 6px; background: var(--bg-color); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                @if($ukm->logo)
                                    <img src="{{ filter_var($ukm->logo, FILTER_VALIDATE_URL) ? $ukm->logo : asset('storage/' . $ukm->logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--accent-color);">{{ substr($ukm->name, 0, 1) }}</span>
                                @endif
                            </div>
                            {{ $ukm->name }}
                        </div>
                    </td>
                    <td>{{ Str::limit($ukm->description, 50) }}</td>
                    <td>{{ $ukm->members()->count() }} Anggota</td>
                    <td>
                        @php $admin = $ukm->admin(); @endphp
                        @if($admin)
                            <span class="badge badge-approved" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2);"><i class="ph-fill ph-crown"></i> {{ $admin->name }}</span>
                        @else
                            <span class="badge badge-pending" style="background: var(--accent-light); color: var(--accent-color); border: 1px solid rgba(30, 64, 175, 0.15);">Belum ada Admin</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('ukm.edit', $ukm->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form action="{{ route('ukm.destroy', $ukm->id) }}" method="POST" onsubmit="return confirm('Peringatan: Menghapus UKM akan menghapus semua event, anggota, dan keuangan terkait. Lanjutkan?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;"><i class="ph ph-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if(count($ukms) == 0)
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-secondary); padding: 2rem;">Belum ada data UKM.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.25rem;">
        {{ $ukms->links('shared.pagination') }}
    </div>
</div>
@endsection
