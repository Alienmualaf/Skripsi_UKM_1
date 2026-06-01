@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Kelola Pelatih')
@section('header', 'Kelola Pelatih')

@section('content')
<!-- Filter Card -->
<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="width: 180px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Kategori</label>
            <select name="category" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="pelatih" {{ request('category') == 'pelatih' ? 'selected' : '' }}>Pelatih</option>
                <option value="bph" {{ request('category') == 'bph' ? 'selected' : '' }}>BPH</option>
                <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Personel</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, keahlian..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['category', 'search']))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card animate-fade-in" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
                <i class="ph ph-user-focus" style="color: var(--accent-color);"></i> Daftar Pelatih, BPH & Lainnya
            </h4>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
                Total: {{ $coaches->total() }} Personel
            </span>
            @if($isOperator)
            <button onclick="document.getElementById('modal-add-coach').style.display='flex'" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.35rem; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.875rem;">
                <i class="ph ph-plus-circle"></i> Tambah Pelatih
            </button>
            @endif
        </div>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Keahlian</th>
                    @if($isOperator)
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($coaches as $coach)
                <tr>
                    <td>
                        @if($coach->photo)
                            <img src="{{ asset('storage/' . $coach->photo) }}" alt="Photo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent-color);">
                        @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--bg-color); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--text-secondary);">
                                {{ substr($coach->name, 0, 1) }}
                            </div>
                        @endif
                    </td>
                    <td style="font-weight: 600;">{{ $coach->name }}</td>
                    <td>
                        <span class="badge {{ $coach->category == 'pelatih' ? 'badge-primary' : ($coach->category == 'bph' ? 'badge-success' : 'badge-warning') }}" style="text-transform: capitalize;">
                            {{ $coach->category }}
                        </span>
                    </td>
                    <td>{{ $coach->skills ?: '-' }}</td>
                    @if($isOperator)
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editCoach({{ json_encode($coach) }})" class="btn btn-sm" style="background: var(--bg-color); color: var(--accent-color);"><i class="ph ph-pencil"></i></button>
                            <form action="/ukm/coaches/{{ $coach->id }}" method="POST" onsubmit="return confirm('Hapus pelatih ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background: var(--bg-color); color: var(--danger-color);"><i class="ph ph-trash"></i></button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $isOperator ? 5 : 4 }}" style="text-align: center; padding: 3rem; color: var(--text-secondary);">Belum ada data pelatih.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.25rem;">
        {{ $coaches->links('shared.pagination') }}
    </div>
</div>

@if($isOperator)
<!-- Modal Add -->
<div id="modal-add-coach" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; margin: 1rem;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem;">Tambah Pelatih Baru</h3>
        <form action="/ukm/coaches" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Kategori</label>
                <select name="category" class="form-control" required>
                    <option value="pelatih">Pelatih</option>
                    <option value="bph">BPH</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Keahlian / Jabatan</label>
                <input type="text" name="skills" class="form-control" placeholder="Contoh: Vokal / Pelatih Utama">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Foto Profil</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Deskripsi Singkat</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="this.closest('.modal').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-coach" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; margin: 1rem;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem;">Edit Data Pelatih</h3>
        <form id="edit-form" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Nama Lengkap</label>
                <input type="text" name="name" id="edit-name" class="form-control" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Kategori</label>
                <select name="category" id="edit-category" class="form-control" required>
                    <option value="pelatih">Pelatih</option>
                    <option value="bph">BPH</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Keahlian / Jabatan</label>
                <input type="text" name="skills" id="edit-skills" class="form-control">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Foto Baru (Opsional)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Deskripsi Singkat</label>
                <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="this.closest('.modal').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCoach(coach) {
    const modal = document.getElementById('modal-edit-coach');
    const form = document.getElementById('edit-form');
    form.action = '/ukm/coaches/' + coach.id;
    document.getElementById('edit-name').value = coach.name;
    document.getElementById('edit-category').value = coach.category;
    document.getElementById('edit-skills').value = coach.skills || '';
    document.getElementById('edit-description').value = coach.description || '';
    modal.style.display = 'flex';
}
</script>
@endif
@endsection
