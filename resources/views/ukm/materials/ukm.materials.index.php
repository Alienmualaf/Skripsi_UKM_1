@php
    $ukmId = session('managed_ukm_id');
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
@endphp

@extends('layouts.app')

@section('title', 'Materi Pembelajaran')
@section('header', 'Materi Pembelajaran')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #10b981;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if($isOperator)
<div class="card mb-4 animate-fade-in">
    <h3 style="font-weight: 700; margin-bottom: 1.5rem;">Upload Materi Baru</h3>
    <form action="/ukm/materials" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label" style="font-weight: 600;">Judul Materi</label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Dokumen Panduan Belajar">
            </div>
            <div class="form-group">
                <label class="form-label" style="font-weight: 600;">Jenis</label>
                <select name="type" class="form-control" required>
                    <option value="dokumen">Dokumen</option>
                    <option value="audio">Audio</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" style="font-weight: 600;">Terkait Agenda <span style="color: var(--danger-color);">*</span></label>
                <select name="event_id" class="form-control" required>
                    <option value="">-- Pilih Agenda --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label class="form-label" style="font-weight: 600;">File (Maks 10MB)</label>
                <input type="file" name="file" class="form-control">
                <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Biarkan kosong jika menggunakan link.</p>
            </div>
            <div class="form-group">
                <label class="form-label" style="font-weight: 600;">Atau Link Eksternal (Opsional)</label>
                <input type="url" name="link" class="form-control" placeholder="https://youtube.com/... atau https://drive.google.com/...">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="form-label" style="font-weight: 600;">Keterangan / Deskripsi</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Tulis instruksi atau keterangan materi di sini..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 2rem; font-weight: 700;">
            <i class="ph ph-upload-simple"></i> Simpan Materi
        </button>
    </form>
</div>
@endif

<!-- Filter Card -->
<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="width: 180px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Jenis Materi</label>
            <select name="type" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="dokumen" {{ request('type') == 'dokumen' ? 'selected' : '' }}>Dokumen</option>
                <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>
        <div style="width: 220px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Filter Agenda</label>
            <select name="event_id" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Agenda</option>
                @foreach($events as $ev)
                    <option value="{{ $ev->id }}" {{ request('event_id') == $ev->id ? 'selected' : '' }}>{{ $ev->title }}</option>
                @endforeach
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Materi</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, rincian..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['type', 'event_id', 'search']))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card animate-fade-in" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-books" style="color: var(--accent-color);"></i> Daftar Materi
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $materials->total() }} Materi
        </span>
    </div>
    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Materi</th>
                    <th>Agenda</th>
                    <th>Sumber</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--bg-color); display: flex; align-items: center; justify-content: center; color: var(--accent-color);">
                                <i class="ph {{ $material->type == 'audio' ? 'ph-music-note' : ($material->type == 'video' ? 'ph-video-camera' : 'ph-file-text') }}"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $material->title }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ ucfirst($material->type) }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size: 0.875rem; font-weight: 500;">
                            <i class="ph ph-calendar"></i> {{ $material->event->title ?? 'Umum' }}
                        </span>
                    </td>
                    <td>
                        @if($material->file_path)
                            <span class="badge badge-primary">File</span>
                        @endif
                        @if($material->link)
                            <span class="badge badge-success">Link</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            @if($material->file_path)
                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn btn-sm" style="background: var(--bg-color); color: var(--accent-color);"><i class="ph ph-download-simple"></i></a>
                            @endif
                            @if($material->link)
                                <a href="{{ $material->link }}" target="_blank" class="btn btn-sm" style="background: var(--bg-color); color: var(--success-color);"><i class="ph ph-link"></i></a>
                            @endif
                            @if($isOperator)
                            <form action="/ukm/materials/{{ $material->id }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background: var(--bg-color); color: var(--danger-color);"><i class="ph ph-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 3rem; color: var(--text-secondary);">Belum ada materi pembelajaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.25rem;">
        {{ $materials->links('shared.pagination') }}
    </div>
</div>
@endsection
