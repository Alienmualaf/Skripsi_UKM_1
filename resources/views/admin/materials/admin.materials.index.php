@extends('layouts.app')

@section('title', 'Materi Belajar UKM')
@section('header', 'Monitoring Materi Belajar UKM')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Monitoring Materi Belajar UKM</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Super Admin memantau seluruh materi pembelajaran atau modul latihan yang diunggah oleh UKM.</p>
</div>

<!-- Filter Card -->
<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Filter berdasarkan UKM</label>
            <select name="ukm_id" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua UKM</option>
                @foreach($ukms as $u)
                    <option value="{{ $u->id }}" {{ request('ukm_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Materi</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul materi..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->filled('ukm_id') || request()->filled('search'))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table Card -->
<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-book-open" style="color: var(--accent-color);"></i> Daftar Materi Pembelajaran
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $materials->total() }} Materi
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Judul Materi</th>
                    <th>UKM</th>
                    <th>Tipe File</th>
                    <th>Tanggal Unggah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $m)
                <tr>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $m->title }}</td>
                    <td style="font-weight: 600; color: var(--accent-color);">{{ $m->ukm->name }}</td>
                    <td><span class="badge badge-info">{{ ucfirst($m->type) }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            @if($m->file_path)
                                <a href="{{ asset('storage/' . $m->file_path) }}" target="_blank" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i class="ph ph-eye"></i> Lihat File
                                </a>
                            @endif
                            @if($m->link)
                                <a href="{{ $m->link }}" target="_blank" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; background: var(--bg-color); border: 1px solid var(--border-color); color: var(--success-color); text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i class="ph ph-link"></i> Buka Link
                                </a>
                            @endif
                            <form action="/admin/materials/{{ $m->id }}" method="POST" onsubmit="return confirm('Hapus materi ini?')" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;">
                                    <i class="ph ph-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($materials->isEmpty())
                <tr><td colspan="5" class="text-secondary text-center py-8">Tidak ada data materi pembelajaran.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.25rem;">
        {{ $materials->links('shared.pagination') }}
    </div>
</div>
@endsection
