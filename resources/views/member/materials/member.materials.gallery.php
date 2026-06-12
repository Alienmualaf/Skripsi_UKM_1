@php
    $ukmId = $ukm->id;
@endphp

@extends('layouts.app')

@section('title', 'Galeri Materi - ' . $ukm->name)
@section('header', 'Galeri Materi: ' . $ukm->name)

@section('content')
<style>
    /* Premium File Explorer Design System */
    .explorer-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .breadcrumb-container {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        color: var(--text-secondary);
        flex-wrap: wrap;
    }
    
    .breadcrumb-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    
    .breadcrumb-link {
        color: var(--text-secondary);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.15s;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .breadcrumb-link:hover {
        color: var(--accent-color);
    }
    
    .breadcrumb-current {
        color: var(--text-primary);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .breadcrumb-separator {
        color: var(--border-color);
        font-size: 0.75rem;
        display: flex;
        align-items: center;
    }

    .filter-search-bar {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .section-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 1.5rem 0 1rem 0;
        font-family: 'Outfit', sans-serif;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Folder Grid & Cards */
    .folder-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .folder-card {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
        position: relative;
    }

    .folder-card:hover {
        transform: translateY(-2px);
        border-color: var(--accent-color);
        box-shadow: var(--shadow-md);
        background: rgba(59, 130, 246, 0.01);
    }

    .folder-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 0;
        flex: 1;
        text-decoration: none;
    }

    .folder-icon {
        font-size: 2.25rem;
        color: #f59e0b; /* Warm amber folder color */
        flex-shrink: 0;
    }

    .folder-details {
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .folder-name {
        font-weight: 700;
        font-size: 0.925rem;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: 'Outfit', sans-serif;
    }

    .folder-desc {
        font-size: 0.7rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* File Table / Explorer */
    .file-explorer-container {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .file-row {
        display: grid;
        grid-template-columns: 2.5fr 1fr 1fr 1.25fr 110px;
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        align-items: center;
        transition: background 0.15s ease;
    }

    .file-row:last-child {
        border-bottom: none;
    }

    .file-row.header {
        background: var(--bg-color);
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        cursor: default;
        font-family: 'Outfit', sans-serif;
    }

    .file-row:not(.header):hover {
        background: rgba(59, 130, 246, 0.02);
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        min-width: 0;
    }

    .file-icon-wrapper {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.35rem;
    }

    /* Type Specific Styling */
    .icon-pdf { background: #fef2f2; color: #ef4444; }
    .icon-doc { background: #eff6ff; color: #3b82f6; }
    .icon-audio { background: #ecfdf5; color: #10b981; }
    .icon-video { background: #faf5ff; color: #8b5cf6; }
    .icon-link { background: #f0fdfa; color: #0d9488; }
    .icon-file { background: #f9fafb; color: #6b7280; }

    .file-details {
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .file-title {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: 'Outfit', sans-serif;
    }

    .file-name-sub {
        font-size: 0.725rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-meta {
        font-size: 0.85rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .file-meta.uploader-name {
        font-weight: 500;
        color: var(--text-primary);
    }

    .file-actions {
        display: flex;
        gap: 0.4rem;
        justify-content: flex-end;
    }

    .file-btn {
        background: var(--bg-color);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        cursor: pointer;
        padding: 8px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .file-btn:hover {
        background: var(--border-color);
        color: var(--accent-color);
    }

    @media (max-width: 992px) {
        .file-row {
            grid-template-columns: 2fr 1fr 1fr 90px;
        }
        .file-row .uploader-col {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .file-row.header {
            display: none;
        }
        .file-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
            padding: 1rem;
        }
        .file-meta {
            font-size: 0.775rem;
            padding-left: 2.9rem;
        }
        .file-meta::before {
            content: attr(data-label) ": ";
            font-weight: 700;
            color: var(--text-secondary);
        }
        .file-actions {
            justify-content: flex-start;
            padding-left: 2.9rem;
            margin-top: 0.5rem;
        }
    }
</style>

<div class="animate-fade-in">
    <!-- Explorer Header & Breadcrumbs -->
    <div class="explorer-header">
        <div class="breadcrumb-container">
            <!-- Root Item -->
            <div class="breadcrumb-item">
                <a href="/room/{{ $ukm->id }}/materials-gallery" class="breadcrumb-link">
                    <i class="ph ph-books" style="font-size: 1.15rem;"></i> Galeri Materi
                </a>
            </div>
            
            @if(count($breadcrumbs) > 0)
                <div class="breadcrumb-separator"><i class="ph ph-caret-right"></i></div>
                @foreach($breadcrumbs as $index => $bc)
                    <div class="breadcrumb-item">
                        @if($index === count($breadcrumbs) - 1)
                            <span class="breadcrumb-current">
                                <i class="ph ph-folder-open"></i> {{ $bc['name'] }}
                            </span>
                        @else
                            <a href="/room/{{ $ukm->id }}/materials-gallery?folder_id={{ $bc['id'] }}" class="breadcrumb-link">
                                <i class="ph ph-folder"></i> {{ $bc['name'] }}
                            </a>
                        @endif
                    </div>
                    @if($index < count($breadcrumbs) - 1)
                        <div class="breadcrumb-separator"><i class="ph ph-caret-right"></i></div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>

    <!-- Search & Filter Area -->
    <div class="filter-search-bar">
        <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <!-- Preserve current folder if navigating/filtering -->
            @if($currentFolder)
                <input type="hidden" name="folder_id" value="{{ $currentFolder->id }}">
            @endif

            <div style="width: 200px;">
                <label class="form-label" style="font-weight: 700; font-size: 0.775rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block; text-transform: uppercase;">Jenis File</label>
                <select name="type" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 10px; border: 1px solid var(--border-color); height: 2.5rem; font-size: 0.85rem;" onchange="this.form.submit()">
                    <option value="">Semua Jenis</option>
                    <option value="dokumen" {{ $type === 'dokumen' ? 'selected' : '' }}>Dokumen (PDF, Word)</option>
                    <option value="audio" {{ $type === 'audio' ? 'selected' : '' }}>Audio (MP3, WAV)</option>
                    <option value="video" {{ $type === 'video' ? 'selected' : '' }}>Video (MP4, MKV)</option>
                </select>
            </div>

            <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
                <div style="position: relative; flex: 1;">
                    <label class="form-label" style="font-weight: 700; font-size: 0.775rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block; text-transform: uppercase;">Cari Materi</label>
                    <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.35rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1.05rem;"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama folder atau judul file..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem; border-radius: 10px;">
                </div>
                <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1.25rem; font-weight: 700; border-radius: 10px; display: flex; align-items: center; gap: 0.25rem;">
                    <i class="ph ph-magnifying-glass"></i> Cari
                </button>
            </div>

            @if($search || $type)
                <a href="/room/{{ $ukm->id }}/materials-gallery{{ $currentFolder ? '?folder_id=' . $currentFolder->id : '' }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;">
                    <i class="ph ph-arrows-counter-clockwise"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Folders List -->
    @if(count($folders) > 0)
        <div class="section-title">
            <i class="ph ph-folder" style="color: #f59e0b; font-size: 1.15rem;"></i> Folder ({{ count($folders) }})
        </div>
        <div class="folder-grid animate-fade-in">
            @foreach($folders as $folder)
                <div class="folder-card">
                    <!-- Link to Folder -->
                    <a href="/room/{{ $ukm->id }}/materials-gallery?folder_id={{ $folder->id }}" class="folder-info">
                        <i class="ph-fill ph-folder folder-icon"></i>
                        <div class="folder-details">
                            <span class="folder-name" title="{{ $folder->name }}">{{ $folder->name }}</span>
                            <span class="folder-desc">{{ $folder->description ?: 'Tidak ada deskripsi' }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Files List -->
    <div class="section-title">
        <i class="ph ph-file" style="color: var(--accent-color); font-size: 1.15rem;"></i> File / Materi ({{ count($materials) }})
    </div>
    
    <div class="file-explorer-container animate-fade-in">
        <!-- Explorer Header Row -->
        <div class="file-row header">
            <div>Nama File</div>
            <div>Jenis</div>
            <div>Ukuran</div>
            <div class="uploader-col">Pengunggah / Tanggal</div>
            <div style="text-align: right;">Aksi</div>
        </div>

        <!-- Files Loop -->
        @forelse($materials as $material)
            @php
                $ext = $material->file_type;
                $iconClass = 'icon-file';
                $iconName = 'ph-file';
                
                if ($ext === 'pdf') {
                    $iconClass = 'icon-pdf';
                    $iconName = 'ph-file-pdf';
                } elseif (in_array($ext, ['doc', 'docx'])) {
                    $iconClass = 'icon-doc';
                    $iconName = 'ph-file-doc';
                } elseif (in_array($ext, ['mp3', 'wav', 'm4a'])) {
                    $iconClass = 'icon-audio';
                    $iconName = 'ph-file-audio';
                } elseif (in_array($ext, ['mp4', 'mov', 'mkv'])) {
                    $iconClass = 'icon-video';
                    $iconName = 'ph-file-video';
                } elseif ($ext === 'link') {
                    $iconClass = 'icon-link';
                    $iconName = 'ph-link';
                }
            @endphp
            <div class="file-row">
                <!-- File Name & Info -->
                <div class="file-info">
                    <div class="file-icon-wrapper {{ $iconClass }}">
                        <i class="ph-fill {{ $iconName }}"></i>
                    </div>
                    <div class="file-details">
                        <span class="file-title" title="{{ $material->title }}">{{ $material->title }}</span>
                        <span class="file-name-sub" title="{{ $material->file_name ?: $material->link }}">
                            {{ $material->file_name ?: $material->link }}
                        </span>
                    </div>
                </div>

                <!-- File Type -->
                <div class="file-meta" data-label="Jenis">
                    {{ strtoupper($material->file_type) }}
                </div>

                <!-- File Size -->
                <div class="file-meta" data-label="Ukuran">
                    {{ $material->formatted_size }}
                </div>

                <!-- Uploader & Date -->
                <div class="file-meta uploader-col" data-label="Pengunggah">
                    <div class="file-meta uploader-name">{{ $material->uploader->name ?? ($material->creator->name ?? 'Anonim') }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-secondary);">{{ $material->created_at->format('d M Y H:i') }}</div>
                </div>

                <!-- Actions -->
                <div class="file-actions">
                    <!-- Download -->
                    @if($material->file_path)
                        <a href="/materials/{{ $material->id }}/download" class="file-btn" title="Unduh File">
                            <i class="ph ph-download-simple"></i>
                        </a>
                    @endif

                    <!-- Preview -->
                    <button onclick="previewFile('{{ $material->id }}', '{{ addslashes($material->title) }}', '{{ $material->file_type }}', '{{ addslashes($material->file_path ?? "") }}', '{{ $material->link }}')" class="file-btn" title="Preview File">
                        <i class="ph ph-eye"></i>
                    </button>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 5rem 2rem; background: var(--surface-color);">
                <i class="ph ph-folder-open" style="font-size: 4rem; opacity: 0.15; color: var(--text-secondary); margin-bottom: 1rem; display: block; margin-left: auto; margin-right: auto;"></i>
                <h4 style="font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem;">Tidak ada file</h4>
                <p style="font-size: 0.825rem; color: var(--text-secondary); max-width: 400px; margin: 0 auto;">Folder ini kosong atau tidak ada file yang cocok dengan pencarian / filter Anda.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal: Preview File -->
<div id="modal-preview-file" class="modal" onclick="if(event.target === this) closePreviewFileModal()" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); align-items: center; justify-content: center; z-index: 1100; backdrop-filter: blur(5px); padding: 1rem;">
    <div class="card animate-fade-in" style="width: 100%; max-width: 850px; margin: auto; padding: 1.5rem; border-radius: 20px; box-shadow: var(--shadow-lg); position: relative; background: var(--surface-color);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
            <h3 id="preview-title" style="font-weight: 800; font-size: 1.15rem; margin: 0; color: var(--text-primary); font-family: 'Outfit', sans-serif; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 85%;">
                Preview File
            </h3>
            <button type="button" onclick="closePreviewFileModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Tutup">
                <i class="ph ph-x"></i>
            </button>
        </div>
        
        <!-- Preview Content Container -->
        <div id="preview-content" style="min-height: 250px; display: flex; flex-direction: column; justify-content: center;">
            <!-- Rendered dynamically in JS -->
        </div>
    </div>
</div>

<script>
    // Dynamic Preview File Modal Handler
    function previewFile(id, title, type, filePath, externalLink) {
        const modal = document.getElementById('modal-preview-file');
        const titleEl = document.getElementById('preview-title');
        const contentEl = document.getElementById('preview-content');
        
        titleEl.textContent = title;
        contentEl.innerHTML = ''; // Clear previous contents
        
        if (externalLink && type === 'link') {
            contentEl.innerHTML = `
                <div style="text-align: center; padding: 3rem 1.5rem;">
                    <i class="ph ph-link" style="font-size: 4.5rem; color: var(--accent-color); opacity: 0.8; margin-bottom: 1.5rem; display: block; margin-left: auto; margin-right: auto;"></i>
                    <h4 style="font-weight: 700; margin-bottom: 0.5rem; color: var(--text-primary); font-size: 1.2rem;">Tautan Eksternal</h4>
                    <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 0.9rem; max-width: 400px; margin-left: auto; margin-right: auto;">Materi ini berupa link luar ke Google Drive, YouTube, atau website lain.</p>
                    <a href="${externalLink}" target="_blank" class="btn btn-primary" style="padding: 0.75rem 2rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 10px; font-weight: 700; text-decoration: none; height: auto;">
                        <i class="ph ph-arrow-square-out"></i> Buka Tautan di Tab Baru
                    </a>
                </div>
            `;
        } else {
            const previewUrl = '/materials/' + id + '/preview';
            
            if (type === 'pdf' || filePath.toLowerCase().endsWith('.pdf')) {
                contentEl.innerHTML = `
                    <iframe src="${previewUrl}" width="100%" height="550px" style="border: none; border-radius: 8px;"></iframe>
                `;
            } else if (type === 'audio' || ['mp3', 'wav', 'm4a'].includes(type) || filePath.toLowerCase().match(/\.(mp3|wav|m4a)$/)) {
                contentEl.innerHTML = `
                    <div style="text-align: center; padding: 4rem 1.5rem;">
                        <i class="ph-fill ph-music-notes" style="font-size: 4.5rem; color: #10b981; opacity: 0.8; margin-bottom: 1.5rem; display: block; margin-left: auto; margin-right: auto;"></i>
                        <h5 style="font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem; font-size: 1.1rem;">Memutar Audio</h5>
                        <audio src="${previewUrl}" controls autoplay style="width: 100%; max-width: 500px; margin: 0 auto; outline: none;"></audio>
                    </div>
                `;
            } else if (type === 'video' || ['mp4', 'mov', 'mkv'].includes(type) || filePath.toLowerCase().match(/\.(mp4|mov|mkv)$/)) {
                contentEl.innerHTML = `
                    <div style="background: #000; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; width: 100%;">
                        <video src="${previewUrl}" controls autoplay style="width: 100%; max-height: 480px; outline: none;"></video>
                    </div>
                `;
            } else {
                // Fallback for doc/docx/others
                contentEl.innerHTML = `
                    <div style="text-align: center; padding: 3rem 1.5rem;">
                        <i class="ph ph-file-text" style="font-size: 4.5rem; color: var(--text-secondary); opacity: 0.8; margin-bottom: 1.5rem; display: block; margin-left: auto; margin-right: auto;"></i>
                        <h4 style="font-weight: 700; margin-bottom: 0.5rem; color: var(--text-primary); font-size: 1.2rem;">Preview Tidak Tersedia</h4>
                        <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 0.9rem; max-width: 400px; margin-left: auto; margin-right: auto;">File jenis ini (.${type}) tidak dapat dipreview langsung di browser.</p>
                        <a href="/materials/${id}/download" class="btn btn-primary" style="padding: 0.75rem 2rem; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 10px; font-weight: 700; text-decoration: none; height: auto;">
                            <i class="ph ph-download-simple"></i> Unduh File Materi
                        </a>
                    </div>
                `;
            }
        }
        
        modal.style.display = 'flex';
    }

    // Close Preview File Modal
    function closePreviewFileModal() {
        const modal = document.getElementById('modal-preview-file');
        const contentEl = document.getElementById('preview-content');
        contentEl.innerHTML = ''; // Stop audio/video playing instantly
        modal.style.display = 'none';
    }
</script>
@endsection
