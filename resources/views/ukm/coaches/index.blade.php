@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Kelola Pelatih')
@section('header', 'Kelola Pelatih & BPH')

@section('content')
<div class="card animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3 style="font-weight: 700; font-size: 1.25rem;">Daftar Pelatih, BPH & Lainnya</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">Manajemen data pelatih yang terlibat dalam kegiatan.</p>
        </div>
        @if($isOperator)
        <button onclick="document.getElementById('modal-add-coach').style.display='flex'" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="ph ph-plus-circle"></i> Tambah Pelatih
        </button>
        @endif
    </div>

    <div class="table-responsive">
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
