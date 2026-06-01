@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')
@section('header', 'Pengumuman Berbasis Agenda')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 350px; gap: 1.5rem; align-items: start;">
    <div class="card animate-fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h3 style="font-weight: 700; font-size: 1.25rem;">Daftar Pengumuman</h3>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Total: {{ $announcements->total() }} pengumuman diterbitkan.</p>
            </div>
            @if($isOperator)
            <button onclick="document.getElementById('modal-add-announcement').style.display='flex'" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-plus-circle"></i> Buat Pengumuman
            </button>
            @endif
        </div>

        @foreach($announcements as $ann)
        <div style="padding: 1.25rem; border: 1px solid var(--border-color); border-radius: 12px; margin-bottom: 1rem; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                <div>
                    <span class="badge badge-primary" style="margin-bottom: 0.5rem; font-size: 0.65rem;">{{ $ann->event->title }}</span>
                    <h4 style="font-weight: 700; font-size: 1.1rem; margin-top: 0.25rem;">{{ $ann->title }}</h4>
                    <div style="display: flex; align-items: center; gap: 1rem; color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                        <span style="display: flex; align-items: center; gap: 0.25rem;"><i class="ph ph-user"></i> {{ $ann->creator->name }}</span>
                        <span style="display: flex; align-items: center; gap: 0.25rem;"><i class="ph ph-clock"></i> {{ $ann->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                @if($isOperator)
                <div style="display: flex; gap: 0.5rem;">
                    <button onclick="editAnn({{ json_encode($ann) }})" class="btn btn-sm" style="background: var(--bg-color); color: var(--accent-color);"><i class="ph ph-pencil"></i></button>
                    <form action="/ukm/announcements/{{ $ann->id }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background: var(--bg-color); color: var(--danger-color);"><i class="ph ph-trash"></i></button>
                    </form>
                </div>
                @endif
            </div>
            <div style="color: var(--text-primary); font-size: 0.95rem; line-height: 1.6; white-space: pre-line;">
                {{ $ann->content }}
            </div>
        </div>
        @endforeach

        @if($announcements->isEmpty())
            <div style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                <i class="ph ph-megaphone-slash" style="font-size: 3rem; opacity: 0.2;"></i>
                <p style="margin-top: 1rem;">Belum ada pengumuman.</p>
            </div>
        @endif

        <div style="margin-top: 1.25rem;">
            {{ $announcements->links('shared.pagination') }}
        </div>
    </div>

    <div class="card animate-fade-in" style="position: sticky; top: 1.5rem;">
        <h4 style="font-weight: 700; margin-bottom: 1.25rem;">Pencarian & Filter</h4>
        <form action="" method="GET" style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Filter Agenda</label>
                <select name="event_id" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Agenda</option>
                    @foreach($events as $ev)
                        <option value="{{ $ev->id }}" {{ request('event_id') == $ev->id ? 'selected' : '' }}>{{ $ev->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Kata Kunci</label>
                <div style="position: relative; display: flex; gap: 0.5rem; flex-direction: column;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau isi..." class="form-control" style="font-size: 0.875rem;">
                    <button type="submit" class="btn btn-primary" style="font-weight: 700; border-radius: 8px; width: 100%;">Cari</button>
                </div>
            </div>
            @if(request()->anyFilled(['event_id', 'search']))
                <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; text-align: center;"><i class="ph ph-x-circle"></i> Reset</a>
            @endif
        </form>
    </div>
</div>

@if($isOperator)
<!-- Modal Add -->
<div id="modal-add-announcement" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 600px; margin: 1rem;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem;">Buat Pengumuman Baru</h3>
        <form action="/ukm/announcements" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Pilih Agenda</label>
                <select name="event_id" class="form-control" required>
                    @foreach($events as $ev)
                        <option value="{{ $ev->id }}">{{ $ev->title }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Judul Pengumuman</label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Perubahan Jadwal Latihan">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Isi Pengumuman</label>
                <textarea name="content" class="form-control" rows="8" required placeholder="Tulis pengumuman di sini..."></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="this.closest('.modal').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Terbitkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-announcement" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 600px; margin: 1rem;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem;">Edit Pengumuman</h3>
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Judul</label>
                <input type="text" name="title" id="edit-title" class="form-control" required>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Isi</label>
                <textarea name="content" id="edit-content" class="form-control" rows="8" required></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="this.closest('.modal').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editAnn(ann) {
    const modal = document.getElementById('modal-edit-announcement');
    const form = document.getElementById('edit-form');
    form.action = '/ukm/announcements/' + ann.id;
    document.getElementById('edit-title').value = ann.title;
    document.getElementById('edit-content').value = ann.content;
    modal.style.display = 'flex';
}
</script>
@endif
@endsection
