@extends('layouts.app')

@section('title', 'Profil & Landing Page PSUP')
@section('header', 'Pengaturan Website & Profil PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<!-- Tab Navigation -->
<div class="card mb-4" style="padding: 0.5rem 1rem;">
    <div style="display: flex; gap: 0.5rem; overflow-x: auto; white-space: nowrap; padding-bottom: 0.25rem;">
        <button onclick="switchTab('tab-profile')" id="btn-tab-profile" class="tab-btn active" style="padding: 0.75rem 1.25rem; font-weight: 700; font-size: 0.85rem; border: none; background: none; color: var(--text-secondary); border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s;">
            <i class="ph ph-identification-card" style="margin-right: 0.25rem;"></i> Profil & Kontak
        </button>
        <button onclick="switchTab('tab-history')" id="btn-tab-history" class="tab-btn" style="padding: 0.75rem 1.25rem; font-weight: 700; font-size: 0.85rem; border: none; background: none; color: var(--text-secondary); border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s;">
            <i class="ph ph-hourglass-high" style="margin-right: 0.25rem;"></i> Sejarah (Timeline)
        </button>
        <button onclick="switchTab('tab-recruitment')" id="btn-tab-recruitment" class="tab-btn" style="padding: 0.75rem 1.25rem; font-weight: 700; font-size: 0.85rem; border: none; background: none; color: var(--text-secondary); border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s;">
            <i class="ph ph-user-plus" style="margin-right: 0.25rem;"></i> Open Recruitment
        </button>
        <button onclick="switchTab('tab-downloads')" id="btn-tab-downloads" class="tab-btn" style="padding: 0.75rem 1.25rem; font-weight: 700; font-size: 0.85rem; border: none; background: none; color: var(--text-secondary); border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s;">
            <i class="ph ph-file-pdf" style="margin-right: 0.25rem;"></i> Unduhan & Berkas
        </button>
    </div>
</div>

<style>
    .tab-btn.active {
        color: var(--primary-color) !important;
        border-bottom-color: var(--primary-color) !important;
    }
    .tab-content-panel {
        display: none;
    }
    .tab-content-panel.active {
        display: block;
    }
    .form-section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px dashed var(--border-color);
        padding-bottom: 0.75rem;
    }
</style>

<!-- 🌟 TAB 1: PROFIL & KONTAK -->
<div id="tab-profile" class="tab-content-panel active">
    <div class="card" style="padding: 2rem;">
        <h3 class="form-section-title">
            <i class="ph ph-identification-card" style="color: var(--primary-color);"></i> Profil & Media Sosial Organisasi
        </h3>

        <form action="{{ route('ukm.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Resmi Organisasi</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $profile->name ?? '') }}" style="padding: 0.65rem;">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Singkatan Organisasi</label>
                    <input type="text" name="alias" class="form-control" value="{{ old('alias', $profile->alias ?? '') }}" style="padding: 0.65rem;">
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tagline / Slogan</label>
                <input type="text" name="tagline" class="form-control" placeholder="Satu Suara, Sejuta Harmoni" value="{{ old('tagline', $profile->tagline ?? '') }}" style="padding: 0.65rem;">
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi Singkat Organisasi</label>
                <textarea name="description" class="form-control" rows="4" style="padding: 0.65rem;">{{ old('description', $profile->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Visi Organisasi</label>
                    <textarea name="vision" class="form-control" rows="4" style="padding: 0.65rem;">{{ old('vision', $profile->vision ?? '') }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Misi Organisasi</label>
                    <textarea name="mission" class="form-control" rows="4" style="padding: 0.65rem;" placeholder="Gunakan baris baru atau angka untuk memisahkan setiap poin misi.">{{ old('mission', $profile->mission ?? '') }}</textarea>
                </div>
            </div>

            <h3 class="form-section-title" style="margin-top: 2rem;">
                <i class="ph ph-phone" style="color: var(--primary-color);"></i> Informasi Kontak & Alamat
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nomor Kontak / WhatsApp</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone ?? '') }}" style="padding: 0.65rem;">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Email Resmi</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $profile->email ?? '') }}" style="padding: 0.65rem;">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Website</label>
                    <input type="text" name="website" class="form-control" value="{{ old('website', $profile->website ?? '') }}" style="padding: 0.65rem;">
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Alamat Sekretariat</label>
                <textarea name="address" class="form-control" rows="2" style="padding: 0.65rem;">{{ old('address', $profile->address ?? '') }}</textarea>
            </div>

            <h3 class="form-section-title" style="margin-top: 2rem;">
                <i class="ph ph-share-network" style="color: var(--primary-color);"></i> Media Sosial & Video Profil
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Link Instagram</label>
                    <input type="text" name="instagram" class="form-control" placeholder="https://instagram.com/..." value="{{ old('instagram', $profile->instagram ?? '') }}" style="padding: 0.65rem;">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Link YouTube Channel</label>
                    <input type="text" name="youtube" class="form-control" placeholder="https://youtube.com/..." value="{{ old('youtube', $profile->youtube ?? '') }}" style="padding: 0.65rem;">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Link TikTok</label>
                    <input type="text" name="tiktok" class="form-control" placeholder="https://tiktok.com/@..." value="{{ old('tiktok', $profile->tiktok ?? '') }}" style="padding: 0.65rem;">
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Video Profil YouTube (Embed Link / Share Link)</label>
                <input type="text" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." value="{{ old('video_url', $profile->video_url ?? '') }}" style="padding: 0.65rem;">
            </div>

            <h3 class="form-section-title" style="margin-top: 2rem;">
                <i class="ph ph-image" style="color: var(--primary-color);"></i> Logo, Banner & Struktur Organisasi
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Logo Organisasi</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="width: 80px; height: 80px; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if(isset($profile->logo) && $profile->logo)
                                <img src="{{ asset('storage/' . $profile->logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="ph ph-image-square" style="font-size: 2rem; color: var(--text-secondary);"></i>
                            @endif
                        </div>
                        <input type="file" name="logo" class="form-control" accept="image/*" style="padding: 0.5rem; flex-grow: 1;">
                    </div>
                    <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem; display: block;">Rasio 1:1, Max 2MB.</span>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Banner / Background</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="width: 120px; height: 80px; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if(isset($profile->banner) && $profile->banner)
                                <img src="{{ asset('storage/' . $profile->banner) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="ph ph-image-square" style="font-size: 2rem; color: var(--text-secondary);"></i>
                            @endif
                        </div>
                        <input type="file" name="banner" class="form-control" accept="image/*" style="padding: 0.5rem; flex-grow: 1;">
                    </div>
                    <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem; display: block;">Rasio Landskap, Max 3MB.</span>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Bagan Struktur</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="width: 100px; height: 80px; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;" @if(isset($profile->structure_image) && $profile->structure_image) onclick="openImageModal('{{ asset('storage/' . $profile->structure_image) }}')" @endif>
                            @if(isset($profile->structure_image) && $profile->structure_image)
                                <img src="{{ asset('storage/' . $profile->structure_image) }}" style="width: 100%; height: 100%; object-fit: contain;">
                            @else
                                <i class="ph ph-tree-structure" style="font-size: 2rem; color: var(--text-secondary);"></i>
                            @endif
                        </div>
                        <input type="file" name="structure_image" class="form-control" accept="image/*" style="padding: 0.5rem; flex-grow: 1;">
                    </div>
                    <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem; display: block;">File gambar, Max 4MB.</span>
                </div>
            </div>

            <div style="margin-top: 2.5rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700;">
                    <i class="ph ph-floppy-disk" style="margin-right: 0.25rem;"></i> Simpan Profil Organisasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 🌟 TAB 2: SEJARAH (TIMELINE) -->
<div id="tab-history" class="tab-content-panel">
    <div class="card" style="padding: 2rem; margin-bottom: 1.5rem;">
        <h3 class="form-section-title" style="margin-bottom: 1rem;">
            <i class="ph ph-plus-circle" style="color: var(--primary-color);"></i> Tambah Sejarah Baru (Timeline)
        </h3>
        
        <form action="{{ route('ukm.profile.history.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tahun</label>
                    <input type="text" name="year" class="form-control" placeholder="Contoh: 1998" required style="padding: 0.65rem;">
                </div>

                <div class="form-group mb-4 md:col-span-3">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Judul Peristiwa Sejarah</label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Pendirian PSUP UP" required style="padding: 0.65rem;">
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi Peristiwa</label>
                <textarea name="description" class="form-control" rows="3" required style="padding: 0.65rem;"></textarea>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Foto Pendukung (Opsional)</label>
                <input type="file" name="photo" class="form-control" accept="image/*" style="padding: 0.5rem;">
                <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem; display: block;">Format JPG, PNG. Max 2MB.</span>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.5rem; font-weight: 700; margin-top: 0.5rem;">
                <i class="ph ph-plus" style="margin-right: 0.25rem;"></i> Tambah Sejarah
            </button>
        </form>
    </div>

    <!-- Sejarah List -->
    <div class="card" style="padding: 2rem;">
        <h3 class="form-section-title">
            <i class="ph ph-hourglass-high" style="color: var(--primary-color);"></i> Daftar Timeline Sejarah Organisasi
        </h3>

        <div class="table-wrapper">
            <table class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 10%;">Tahun</th>
                        <th style="width: 25%;">Judul Peristiwa</th>
                        <th style="width: 40%;">Deskripsi</th>
                        <th style="width: 15%;">Foto</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $history)
                        <tr>
                            <td style="font-weight: 800; color: var(--primary-color);">{{ $history->year }}</td>
                            <td style="font-weight: 700; color: var(--text-primary);">{{ $history->title }}</td>
                            <td style="font-size: 0.8rem; line-height: 1.4; color: var(--text-secondary);">{{ $history->description }}</td>
                            <td>
                                @if($history->photo)
                                    <img src="{{ asset('storage/' . $history->photo) }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <span style="font-size: 0.7rem; color: var(--text-muted);">Tidak ada foto</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <!-- Edit Button (triggers inline form/modal or toggle edit) -->
                                    <button onclick="toggleEditHistory({{ $history->id }})" class="btn" style="padding: 0.35rem; background: rgba(59,130,246,0.1); color: #3b82f6; border: none; border-radius: 6px; cursor: pointer;">
                                        <i class="ph ph-pencil-simple" style="font-size: 1rem;"></i>
                                    </button>
                                    <form action="{{ route('ukm.profile.history.destroy', $history->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sejarah tahun {{ $history->year }} ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" style="padding: 0.35rem; background: rgba(239,68,68,0.1); color: #ef4444; border: none; border-radius: 6px; cursor: pointer;">
                                            <i class="ph ph-trash" style="font-size: 1rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit History Form Row (Initially Hidden) -->
                        <tr id="edit-row-{{ $history->id }}" style="display: none; background: var(--bg-color);">
                            <td colspan="5" style="padding: 1.5rem;">
                                <form action="{{ route('ukm.profile.history.update', $history->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 0.9rem; color: var(--primary-color);">Ubah Sejarah - Tahun {{ $history->year }}</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                        <div class="form-group mb-4">
                                            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tahun</label>
                                            <input type="text" name="year" class="form-control" value="{{ $history->year }}" required style="padding: 0.65rem;">
                                        </div>

                                        <div class="form-group mb-4 md:col-span-3">
                                            <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Judul Peristiwa</label>
                                            <input type="text" name="title" class="form-control" value="{{ $history->title }}" required style="padding: 0.65rem;">
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi Peristiwa</label>
                                        <textarea name="description" class="form-control" rows="3" required style="padding: 0.65rem;">{{ $history->description }}</textarea>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Ganti Foto (Opsional)</label>
                                        <input type="file" name="photo" class="form-control" accept="image/*" style="padding: 0.5rem;">
                                    </div>

                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <button type="button" onclick="toggleEditHistory({{ $history->id }})" class="btn btn-secondary" style="padding: 0.5rem 1.25rem;">Batal</button>
                                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-muted">Belum ada timeline sejarah yang dimasukkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- 🌟 TAB 4: OPEN RECRUITMENT -->
<div id="tab-recruitment" class="tab-content-panel">
    <div class="card" style="padding: 2rem;">
        <h3 class="form-section-title">
            <i class="ph ph-user-plus" style="color: var(--primary-color);"></i> Konfigurasi Open Recruitment Anggota Baru
        </h3>

        <form action="{{ route('ukm.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Hidden Profile Fields (so they aren't blanked) -->
            <input type="hidden" name="name" value="{{ $profile->name ?? '' }}">
            <input type="hidden" name="alias" value="{{ $profile->alias ?? '' }}">

            <div class="form-group mb-6" style="background: var(--bg-color); padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h4 style="margin: 0; font-weight: 800; font-size: 0.95rem; color: var(--text-primary);">Status Pendaftaran Open Recruitment</h4>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.8rem; color: var(--text-secondary);">Aktifkan ini untuk memunculkan tombol pendaftaran dan section Oprec di homepage.</p>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 26px;">
                    <input type="checkbox" name="recruitment_active" value="1" {{ old('recruitment_active', $profile->recruitment_active ?? false) ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" id="recruitment-toggle">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .3s; border-radius: 34px;"></span>
                </label>
            </div>

            <style>
                .switch input:checked + .slider {
                    background-color: var(--success-color);
                }
                .slider:before {
                    position: absolute;
                    content: "";
                    height: 18px;
                    width: 18px;
                    left: 4px;
                    bottom: 4px;
                    background-color: white;
                    transition: .3s;
                    border-radius: 50%;
                }
                .switch input:checked + .slider:before {
                    transform: translateX(24px);
                }
            </style>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tanggal Pendaftaran Dibuka</label>
                    <input type="date" name="recruitment_start_date" class="form-control" value="{{ old('recruitment_start_date', $profile->recruitment_start_date ? $profile->recruitment_start_date->format('Y-m-d') : '') }}" style="padding: 0.65rem;">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tanggal Pendaftaran Ditutup</label>
                    <input type="date" name="recruitment_end_date" class="form-control" value="{{ old('recruitment_end_date', $profile->recruitment_end_date ? $profile->recruitment_end_date->format('Y-m-d') : '') }}" style="padding: 0.65rem;">
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Persyaratan Calon Anggota</label>
                <textarea name="recruitment_requirements" class="form-control" rows="4" style="padding: 0.65rem;" placeholder="Contoh: 1. Mahasiswa aktif Universitas Pancasila, 2. Memiliki minat di bidang olah suara...">{{ old('recruitment_requirements', $profile->recruitment_requirements ?? '') }}</textarea>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Tahapan Seleksi Rekrutmen</label>
                <textarea name="recruitment_stages" class="form-control" rows="4" style="padding: 0.65rem;" placeholder="Contoh: Tahap 1: Pengisian formulir online, Tahap 2: Audisi suara mandiri, Tahap 3: Wawancara...">{{ old('recruitment_stages', $profile->recruitment_stages ?? '') }}</textarea>
            </div>

            <div style="margin-top: 2.5rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700;">
                    <i class="ph ph-floppy-disk" style="margin-right: 0.25rem;"></i> Simpan Konfigurasi Oprec
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 🌟 TAB 5: DOWNLOADS & DOCUMENTS -->
<div id="tab-downloads" class="tab-content-panel">
    <div class="card" style="padding: 2rem;">
        <h3 class="form-section-title">
            <i class="ph ph-file-pdf" style="color: var(--primary-color);"></i> Unduhan Dokumen Resmi PSUP
        </h3>
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 2rem;">Upload dokumen PDF resmi PSUP agar dapat diunduh langsung oleh sponsor, mahasiswa, atau publik di homepage.</p>

        <form action="{{ route('ukm.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Hidden Profile Fields (so they aren't blanked) -->
            <input type="hidden" name="name" value="{{ $profile->name ?? '' }}">
            <input type="hidden" name="alias" value="{{ $profile->alias ?? '' }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Company Profile PDF -->
                <div class="card" style="padding: 1.5rem; background: var(--bg-color); border: 1px solid var(--border-color); text-align: center;">
                    <div style="font-size: 2.5rem; color: #ef4444; margin-bottom: 0.75rem;">
                        <i class="ph ph-file-pdf"></i>
                    </div>
                    <h4 style="margin: 0; font-weight: 800; font-size: 0.9rem; color: var(--text-primary);">Company Profile PDF</h4>
                    <div style="margin: 1rem 0;">
                        @if(isset($profile->company_profile_pdf) && $profile->company_profile_pdf)
                            <a href="{{ asset('storage/' . $profile->company_profile_pdf) }}" target="_blank" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.35rem 0.75rem; display: inline-block;">
                                <i class="ph ph-eye"></i> Lihat PDF
                            </a>
                        @else
                            <span style="font-size: 0.75rem; color: var(--text-muted);">Belum diupload</span>
                        @endif
                    </div>
                    <input type="file" name="company_profile_pdf" class="form-control" accept=".pdf" style="padding: 0.4rem; font-size: 0.75rem;">
                </div>

                <!-- Sponsorship Proposal PDF -->
                <div class="card" style="padding: 1.5rem; background: var(--bg-color); border: 1px solid var(--border-color); text-align: center;">
                    <div style="font-size: 2.5rem; color: #ef4444; margin-bottom: 0.75rem;">
                        <i class="ph ph-file-pdf"></i>
                    </div>
                    <h4 style="margin: 0; font-weight: 800; font-size: 0.9rem; color: var(--text-primary);">Proposal Sponsorship</h4>
                    <div style="margin: 1rem 0;">
                        @if(isset($profile->sponsorship_proposal_pdf) && $profile->sponsorship_proposal_pdf)
                            <a href="{{ asset('storage/' . $profile->sponsorship_proposal_pdf) }}" target="_blank" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.35rem 0.75rem; display: inline-block;">
                                <i class="ph ph-eye"></i> Lihat PDF
                            </a>
                        @else
                            <span style="font-size: 0.75rem; color: var(--text-muted);">Belum diupload</span>
                        @endif
                    </div>
                    <input type="file" name="sponsorship_proposal_pdf" class="form-control" accept=".pdf" style="padding: 0.4rem; font-size: 0.75rem;">
                </div>

                <!-- Media Kit PDF -->
                <div class="card" style="padding: 1.5rem; background: var(--bg-color); border: 1px solid var(--border-color); text-align: center;">
                    <div style="font-size: 2.5rem; color: #ef4444; margin-bottom: 0.75rem;">
                        <i class="ph ph-file-pdf"></i>
                    </div>
                    <h4 style="margin: 0; font-weight: 800; font-size: 0.9rem; color: var(--text-primary);">Media Kit PDF</h4>
                    <div style="margin: 1rem 0;">
                        @if(isset($profile->media_kit_pdf) && $profile->media_kit_pdf)
                            <a href="{{ asset('storage/' . $profile->media_kit_pdf) }}" target="_blank" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.35rem 0.75rem; display: inline-block;">
                                <i class="ph ph-eye"></i> Lihat PDF
                            </a>
                        @else
                            <span style="font-size: 0.75rem; color: var(--text-muted);">Belum diupload</span>
                        @endif
                    </div>
                    <input type="file" name="media_kit_pdf" class="form-control" accept=".pdf" style="padding: 0.4rem; font-size: 0.75rem;">
                </div>
            </div>

            <div style="margin-top: 2.5rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700;">
                    <i class="ph ph-floppy-disk" style="margin-right: 0.25rem;"></i> Simpan Berkas PDF
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Zoom Image -->
<div id="imageZoomModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
    <span onclick="closeImageModal()" style="position: absolute; top: 20px; right: 30px; font-size: 3rem; color: white; cursor: pointer; font-weight: bold;">&times;</span>
    <img id="zoomedImg" style="max-width: 90%; max-height: 90%; border-radius: 8px; box-shadow: 0 0 25px rgba(0,0,0,0.5);">
</div>

<script>
    function switchTab(tabId) {
        // Hide all panels
        document.querySelectorAll('.tab-content-panel').forEach(panel => {
            panel.classList.remove('active');
        });
        // Remove active class from buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected panel
        document.getElementById(tabId).classList.add('active');
        // Activate button
        document.getElementById('btn-' + tabId).classList.add('active');
    }

    function toggleEditHistory(id) {
        const row = document.getElementById('edit-row-' + id);
        if (row.style.display === 'none') {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    }

    function previewStructureImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // If preview image element doesn't exist, we can just reload the page or show it
                const previewImg = document.getElementById('structure-preview-img');
                if (previewImg) {
                    previewImg.src = e.target.result;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function submitDeleteStructureImage() {
        if (confirm('Apakah Anda yakin ingin menghapus bagan struktur organisasi?')) {
            document.getElementById('delete-structure-form').submit();
        }
    }

    function openImageModal(imgSrc) {
        const modal = document.getElementById('imageZoomModal');
        const img = document.getElementById('zoomedImg');
        img.src = imgSrc;
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }

    function closeImageModal() {
        const modal = document.getElementById('imageZoomModal');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
</script>
@endsection
