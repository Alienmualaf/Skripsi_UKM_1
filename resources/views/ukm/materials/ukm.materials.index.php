@php
    $ukmId = $ukm->id;
    $userRole = auth()->user()->roleInUKM($ukmId);
    $isOperator = auth()->user()->isSuperAdmin() || $userRole === 'admin';
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

    .explorer-actions {
        display: flex;
        gap: 0.75rem;
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
        background: rgba(var(--accent-color-rgb), 0.01);
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

    .folder-actions {
        display: flex;
        gap: 0.2rem;
        position: relative;
        z-index: 10;
    }

    .folder-action-btn {
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }

    .folder-action-btn:hover {
        background: var(--bg-color);
        color: var(--text-primary);
    }
    
    .folder-action-btn.delete:hover {
        color: var(--danger-color);
        background: #fef2f2;
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
        grid-template-columns: 2.5fr 1fr 1fr 1.25fr 150px;
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

    .file-btn.delete:hover {
        color: var(--danger-color);
        background: #fee2e2;
        border-color: #fecaca;
    }

    /* Modal layout custom scroll/max-width */
    .modal {
        transition: opacity 0.25s ease;
    }

    @media (max-width: 992px) {
        .file-row {
            grid-template-columns: 2fr 1fr 1fr 100px;
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
                <a href="/ukm/materials" class="breadcrumb-link">
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
                            <a href="/ukm/materials?folder_id={{ $bc['id'] }}" class="breadcrumb-link">
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

        @if($isOperator)
        <div class="explorer-actions">
            <button onclick="document.getElementById('modal-create-folder').style.display='flex'" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.4rem; border-radius: 10px; font-weight: 700; padding: 0.65rem 1.25rem;">
                <i class="ph ph-folder-plus" style="font-size: 1.1rem;"></i> Buat Folder
            </button>
            <button onclick="document.getElementById('modal-upload-file').style.display='flex'" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.4rem; border-radius: 10px; font-weight: 700; padding: 0.65rem 1.25rem;">
                <i class="ph ph-upload-simple" style="font-size: 1.1rem;"></i> Unggah File
            </button>
        </div>
        @endif
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
                <a href="/ukm/materials{{ $currentFolder ? '?folder_id=' . $currentFolder->id : '' }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;">
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
                    <a href="/ukm/materials?folder_id={{ $folder->id }}" class="folder-info">
                        <i class="ph-fill ph-folder folder-icon"></i>
                        <div class="folder-details">
                            <span class="folder-name" title="{{ $folder->name }}">{{ $folder->name }}</span>
                            <span class="folder-desc">{{ $folder->description ?: 'Tidak ada deskripsi' }}</span>
                        </div>
                    </a>

                    <!-- Operator Actions for Folder -->
                    @if($isOperator)
                    <div class="folder-actions">
                        <button onclick="openEditFolderModal('{{ $folder->id }}', '{{ addslashes($folder->name) }}', '{{ addslashes($folder->description) }}')" class="folder-action-btn" title="Edit Folder">
                            <i class="ph ph-pencil-simple"></i>
                        </button>
                        <form action="/ukm/folders/{{ $folder->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus folder ini beserta seluruh isinya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="folder-action-btn delete" title="Hapus Folder">
                                <i class="ph ph-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endif
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

                    <!-- Delete (If Operator) -->
                    @if($isOperator)
                        <form action="/ukm/materials/{{ $material->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file materi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="file-btn delete" title="Hapus File">
                                <i class="ph ph-trash"></i>
                            </button>
                        </form>
                    @endif
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

<!-- ==========================================
     MODALS SECTION
     ========================================== -->

@if($isOperator)
<!-- Modal: Create Folder -->
<div id="modal-create-folder" class="modal" onclick="if(event.target === this) this.style.display='none'" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="card animate-fade-in" style="width: 100%; max-width: 500px; margin: auto; padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-lg); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-weight: 800; font-size: 1.25rem; margin: 0; color: var(--text-primary); font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-folder-plus" style="color: #f59e0b;"></i> Buat Folder Baru
            </h3>
            <button type="button" onclick="document.getElementById('modal-create-folder').style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Tutup">
                <i class="ph ph-x"></i>
            </button>
        </div>
        
        <form action="/ukm/folders" method="POST">
            @csrf
            <!-- Parent Folder ID if inside a directory -->
            @if($currentFolder)
                <input type="hidden" name="parent_id" value="{{ $currentFolder->id }}">
            @endif
            
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Nama Folder <span style="color: var(--danger-color);">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Lagu Wajib, Konser 2026, Agenda Pengurus..." style="border-radius: 10px; height: 2.6rem;">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Keterangan / Deskripsi (Opsional)</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Tulis deskripsi folder di sini..." style="border-radius: 10px;"></textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; align-items: center; border-top: 1px solid var(--border-color); padding-top: 1.25rem; margin-top: 1rem;">
                <button type="button" onclick="document.getElementById('modal-create-folder').style.display='none'" class="btn btn-secondary" style="padding: 0.65rem 1.5rem; border-radius: 10px; font-weight: 700;">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 2rem; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="ph ph-check-bold"></i> Buat Folder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Folder -->
<div id="modal-edit-folder" class="modal" onclick="if(event.target === this) this.style.display='none'" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="card animate-fade-in" style="width: 100%; max-width: 500px; margin: auto; padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-lg); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-weight: 800; font-size: 1.25rem; margin: 0; color: var(--text-primary); font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-folder" style="color: #f59e0b;"></i> Edit Folder
            </h3>
            <button type="button" onclick="document.getElementById('modal-edit-folder').style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Tutup">
                <i class="ph ph-x"></i>
            </button>
        </div>
        
        <form action="" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Nama Folder <span style="color: var(--danger-color);">*</span></label>
                <input type="text" name="name" class="form-control" required style="border-radius: 10px; height: 2.6rem;">
            </div>
            
            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Keterangan / Deskripsi (Opsional)</label>
                <textarea name="description" class="form-control" rows="3" style="border-radius: 10px;"></textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; align-items: center; border-top: 1px solid var(--border-color); padding-top: 1.25rem; margin-top: 1rem;">
                <button type="button" onclick="document.getElementById('modal-edit-folder').style.display='none'" class="btn btn-secondary" style="padding: 0.65rem 1.5rem; border-radius: 10px; font-weight: 700;">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 2rem; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="ph ph-check-bold"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Upload File -->
<div id="modal-upload-file" class="modal" onclick="if(event.target === this) this.style.display='none'" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); padding: 1rem;">
    <div class="card animate-fade-in" style="width: 100%; max-width: 600px; margin: auto; padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-lg); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-weight: 800; font-size: 1.25rem; margin: 0; color: var(--text-primary); font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-file-plus" style="color: var(--accent-color);"></i> Unggah Materi Baru
            </h3>
            <button type="button" onclick="document.getElementById('modal-upload-file').style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Tutup">
                <i class="ph ph-x"></i>
            </button>
        </div>
        
        <form action="/ukm/materials" method="POST" enctype="multipart/form-data" onsubmit="return validateUploadForm(this)">
            @csrf
            <!-- Current Folder ID if inside a directory -->
            @if($currentFolder)
                <input type="hidden" name="folder_id" value="{{ $currentFolder->id }}">
            @endif
            
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Judul Materi <span style="color: var(--danger-color);">*</span></label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Partitur Lagu Indonesia Raya, Rekaman Sopran..." style="border-radius: 10px; height: 2.6rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Sumber Materi <span style="color: var(--danger-color);">*</span></label>
                    <select id="upload-source-select" class="form-control" style="border-radius: 10px; height: 2.6rem;" onchange="toggleUploadSource(this.value)">
                        <option value="file">Unggah File (PDF, MP3, MP4, dll.)</option>
                        <option value="link">Tautan Eksternal (Link URL)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Hubungkan ke Agenda (Opsional)</label>
                    <select name="event_id" class="form-control" style="border-radius: 10px; height: 2.6rem;">
                        <option value="">-- Tidak Terkait Agenda --</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}">{{ $ev->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Upload File Input -->
            <div class="form-group mb-3" id="group-upload-file">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Pilih File (Max 50MB)</label>
                <input type="file" name="file" class="form-control" style="border-radius: 10px; padding: 0.4rem 0.75rem; height: auto;">
                <p style="font-size: 0.725rem; color: var(--text-secondary); margin-top: 0.35rem; line-height: 1.35;">
                    Mendukung dokumen (PDF, DOC, DOCX), audio (MP3, WAV, M4A) dan video (MP4, MOV, MKV).
                </p>
            </div>

            <!-- Link Input -->
            <div id="group-upload-link" style="display: none; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Tautan Link URL</label>
                    <input type="url" name="link" class="form-control" placeholder="https://drive.google.com/..." style="border-radius: 10px; height: 2.6rem;">
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Jenis Tautan</label>
                    <select name="type_link" class="form-control" style="border-radius: 10px; height: 2.6rem;">
                        <option value="dokumen">Dokumen</option>
                        <option value="audio">Audio</option>
                        <option value="video">Video</option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Keterangan / Deskripsi (Opsional)</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Tulis rincian atau keterangan file di sini..." style="border-radius: 10px;"></textarea>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; align-items: center; border-top: 1px solid var(--border-color); padding-top: 1.25rem; margin-top: 1rem;">
                <button type="button" onclick="document.getElementById('modal-upload-file').style.display='none'" class="btn btn-secondary" style="padding: 0.65rem 1.5rem; border-radius: 10px; font-weight: 700;">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 2rem; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="ph ph-upload-simple"></i> Simpan File
                </button>
            </div>
        </form>
    </div>
</div>
@endif

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
    // Toggle Upload Source (File / Link)
    function toggleUploadSource(val) {
        const fileGroup = document.getElementById('group-upload-file');
        const linkGroup = document.getElementById('group-upload-link');
        
        if (val === 'file') {
            fileGroup.style.display = 'block';
            linkGroup.style.display = 'none';
        } else {
            fileGroup.style.display = 'none';
            linkGroup.style.display = 'grid';
        }
    }

    // Validate Form Input
    function validateUploadForm(form) {
        const source = document.getElementById('upload-source-select').value;
        if (source === 'file') {
            const file = form.querySelector('input[name="file"]').value;
            if (!file) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pilih File',
                    text: 'Silakan pilih file yang akan diunggah.'
                });
                return false;
            }
        } else {
            const link = form.querySelector('input[name="link"]').value;
            if (!link) {
                Swal.fire({
                    icon: 'error',
                    title: 'Isi Tautan',
                    text: 'Silakan masukkan tautan link URL materi.'
                });
                return false;
            }
        }
        return true;
    }

    // Open Edit Folder Modal
    function openEditFolderModal(id, name, description) {
        const modal = document.getElementById('modal-edit-folder');
        const form = modal.querySelector('form');
        form.action = '/ukm/folders/' + id;
        modal.querySelector('input[name="name"]').value = name;
        modal.querySelector('textarea[name="description"]').value = description;
        modal.style.display = 'flex';
    }

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
