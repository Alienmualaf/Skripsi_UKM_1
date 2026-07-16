@php
    $isOperator = auth()->user()->isSuperAdmin() || auth()->user()->isAdminUkm() || auth()->user()->isPengurus();
@endphp

@extends('layouts.app')

@section('title', 'Manajemen Prestasi & Medali')
@section('header', 'Manajemen Prestasi & Medali')

@section('content')
<div class="grid-sidebar-layout">
    <div class="card animate-fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="font-weight: 700; font-size: 1.25rem;">Daftar Prestasi & Medali</h3>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Total: {{ $achievements->total() }} prestasi tercatat.</p>
            </div>
            @if($isOperator)
            <button onclick="document.getElementById('modal-add-achievement').style.display='flex'" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                <i class="ph ph-plus-circle"></i> Tambah Prestasi
            </button>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            @foreach($achievements as $ach)
            <div class="achievement-card">
                @if($ach->photo)
                <div class="achievement-card-img">
                    <img src="{{ asset('storage/' . $ach->photo) }}" alt="{{ $ach->title }}">
                </div>
                @else
                <div class="achievement-card-img" style="display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                    <i class="ph ph-trophy" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
                @endif
                <div class="achievement-card-content">
                    <div class="achievement-card-header">
                        <div>
                            <h4 style="font-weight: 700; font-size: 1.1rem; margin: 0 0 0.25rem 0;">{{ $ach->title }}</h4>
                            <span style="font-size: 0.75rem; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 600;">
                                <i class="ph ph-calendar"></i> Tanggal: {{ date('d M Y', strtotime($ach->date)) }}
                            </span>
                        </div>
                        @if($isOperator)
                        <div class="achievement-card-actions">
                            <form action="/ukm/achievements/{{ $ach->id }}/toggle-landing" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn" style="padding: 0.35rem 0.5rem; font-size: 0.75rem; background: {{ $ach->show_on_landing ? 'var(--accent-color)' : 'var(--bg-color)' }}; border: 1px solid {{ $ach->show_on_landing ? 'var(--accent-color)' : 'var(--border-color)' }}; color: {{ $ach->show_on_landing ? '#fff' : 'var(--text-secondary)' }}; display: inline-flex; align-items: center; gap: 0.25rem;" title="{{ $ach->show_on_landing ? 'Sembunyikan dari Landing Page' : 'Tampilkan di Landing Page' }}">
                                    <i class="{{ $ach->show_on_landing ? 'ph-fill ph-star' : 'ph ph-star' }}"></i>
                                    {{ $ach->show_on_landing ? 'Landing Page: ON' : 'Landing Page: OFF' }}
                                </button>
                            </form>
                            <button onclick="editAch({{ json_encode($ach) }})" class="btn btn-sm" style="background: var(--bg-color); color: var(--accent-color); padding: 0.35rem 0.5rem; display: inline-flex; align-items: center; justify-content: center;" title="Edit"><i class="ph ph-pencil"></i></button>
                            <form action="/ukm/achievements/{{ $ach->id }}" method="POST" onsubmit="return confirm('Hapus prestasi ini?')" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.35rem 0.5rem; display: inline-flex; align-items: center; justify-content: center;" title="Hapus"><i class="ph ph-trash"></i></button>
                            </form>
                        </div>
                        @endif
                    </div>
                    <p style="color: var(--text-primary); font-size: 0.875rem; margin-top: 0.5rem; line-height: 1.5; white-space: pre-line;">
                        {{ $ach->description ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        @if($achievements->isEmpty())
            <div style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                <i class="ph ph-trophy" style="font-size: 3rem; opacity: 0.2;"></i>
                <p style="margin-top: 1rem;">Belum ada prestasi yang ditambahkan.</p>
            </div>
        @endif

        <div style="margin-top: 1.25rem;">
            {{ $achievements->links('shared.pagination') }}
        </div>
    </div>

    <div class="card animate-fade-in" style="position: sticky; top: 1.5rem;">
        <h4 style="font-weight: 700; margin-bottom: 1.25rem;">Pencarian</h4>
        <form action="" method="GET" style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Kata Kunci</label>
                <div style="position: relative; display: flex; gap: 0.5rem; flex-direction: column;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau deskripsi..." class="form-control" style="font-size: 0.875rem;">
                    <button type="submit" class="btn btn-primary" style="font-weight: 700; border-radius: 8px; width: 100%;">Cari</button>
                </div>
            </div>
            @if(request()->anyFilled(['search']))
                <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; text-align: center;"><i class="ph ph-x-circle"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

@if($isOperator)
<!-- Modal Add -->
<div id="modal-add-achievement" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 600px; margin: 1rem; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem;">Tambah Prestasi Baru</h3>
        <form action="/ukm/achievements" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Judul Prestasi / Nama Kompetisi</label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Juara 1 Paduan Suara Mahasiswa Nasional">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Tanggal Perolehan</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Foto / Sertifikat / Medali</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Deskripsi Singkat</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Tuliskan detail perolehan medali, penyelenggara, lokasi, dll..."></textarea>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 600; font-size: 0.875rem;">
                    <input type="checkbox" name="show_on_landing" value="1" style="width: auto; margin: 0; cursor: pointer;">
                    Tampilkan di Landing Page
                </label>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="this.closest('.modal').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Prestasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-achievement" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 600px; margin: 1rem; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem;">Edit Prestasi</h3>
        <form id="edit-achievement-form" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Judul Prestasi / Nama Kompetisi</label>
                <input type="text" name="title" id="edit-ach-title" class="form-control" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Tanggal Perolehan</label>
                <input type="date" name="date" id="edit-ach-date" class="form-control" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Ganti Foto (Kosongkan jika tidak ingin mengubah)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                <div id="edit-ach-photo-preview" style="margin-top: 0.5rem; display: none;">
                    <span style="font-size: 0.75rem; color: var(--text-secondary);">Foto saat ini:</span>
                    <img id="edit-ach-photo-img" src="" alt="Preview" style="display: block; width: 120px; height: 80px; object-fit: cover; border-radius: 4px; margin-top: 0.25rem;">
                </div>
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Deskripsi Singkat</label>
                <textarea name="description" id="edit-ach-description" class="form-control" rows="4"></textarea>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 600; font-size: 0.875rem;">
                    <input type="checkbox" name="show_on_landing" id="edit-ach-landing" value="1" style="width: auto; margin: 0; cursor: pointer;">
                    Tampilkan di Landing Page
                </label>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="this.closest('.modal').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editAch(ach) {
    const modal = document.getElementById('modal-edit-achievement');
    const form = document.getElementById('edit-achievement-form');
    form.action = '/ukm/achievements/' + ach.id;
    document.getElementById('edit-ach-title').value = ach.title;
    document.getElementById('edit-ach-date').value = ach.date;
    document.getElementById('edit-ach-description').value = ach.description || '';
    
    // Checkbox show_on_landing
    const checkbox = document.getElementById('edit-ach-landing');
    checkbox.checked = !!ach.show_on_landing;
    
    // Photo preview
    const previewDiv = document.getElementById('edit-ach-photo-preview');
    const previewImg = document.getElementById('edit-ach-photo-img');
    if (ach.photo) {
        previewImg.src = '/storage/' + ach.photo;
        previewDiv.style.display = 'block';
    } else {
        previewDiv.style.display = 'none';
    }

    modal.style.display = 'flex';
}
</script>
@endif
@endsection
