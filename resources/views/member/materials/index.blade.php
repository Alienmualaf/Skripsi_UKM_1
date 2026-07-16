@extends('layouts.app')

@section('title', 'Materi Latihan')
@section('header', 'Materi Latihan PSUP')

@section('content')
<style>
.fm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 1.5rem 1rem;
}
.fm-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1rem 0.75rem;
    border: 1px solid transparent;
    border-radius: 12px;
    position: relative;
    transition: all 0.2s ease;
    background: transparent;
    text-decoration: none;
    cursor: pointer;
}
.fm-item:hover {
    background-color: rgba(255, 205, 14, 0.04);
    border-color: rgba(255, 205, 14, 0.3);
}
.fm-icon-wrapper {
    position: relative;
    width: 72px;
    height: 72px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
}
.fm-name {
    font-weight: 700;
    font-size: 0.8rem;
    color: var(--text-primary);
    width: 100%;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    word-break: break-word;
}
.fm-meta {
    font-size: 0.7rem;
    color: var(--text-secondary);
    margin-top: 0.25rem;
}
.fm-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.2s ease;
    z-index: 10;
}
.fm-item:hover .fm-actions {
    opacity: 1;
}
.fm-action-btn {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-primary);
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
    padding: 0;
}
.fm-action-btn:hover {
    background: var(--accent-color);
    color: #00072D;
    border-color: var(--accent-color);
}
.fm-action-btn.danger:hover {
    background: var(--danger-color);
    color: #fff;
    border-color: var(--danger-color);
}
@media (max-width: 768px) {
    .fm-actions {
        opacity: 0.8;
    }
}
</style>
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Unduh Materi & Partitur</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Unduh berkas partitur lagu resmi dan rekaman audio latihan vokal (MP3) berdasarkan pembagian suara Anda.</p>
    </div>
</div>

<!-- Folder Breadcrumbs -->
<div class="card mb-6" style="padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.9rem;">
        <a href="{{ route('member.materials') }}" style="color: var(--accent-color); text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph-fill ph-house" style="font-size: 1.15rem;"></i> Home
        </a>
        @foreach($breadcrumbs as $bc)
            <span style="color: var(--text-muted);">/</span>
            <a href="{{ route('member.materials', ['folder_id' => $bc->id]) }}" style="color: var(--accent-color); text-decoration: none;">
                {{ $bc->name }}
            </a>
        @endforeach
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="card mb-4" style="padding: 1rem 1.5rem;">
    <form action="" method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        @if(request()->has('folder_id'))
            <input type="hidden" name="folder_id" value="{{ request('folder_id') }}">
        @endif
        <div style="flex: 1; min-width: 250px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari folder atau nama berkas..." class="form-control" style="padding: 0.5rem 0.75rem; font-size: 0.875rem;">
        </div>
        <div style="width: 180px;">
            <select name="type" class="form-control" style="padding: 0.5rem 0.75rem; font-size: 0.875rem;" onchange="this.form.submit()">
                <option value="">Semua Tipe Berkas</option>
                <option value="Partitur" {{ request('type') === 'Partitur' ? 'selected' : '' }}>Partitur (PDF)</option>
                <option value="Audio" {{ request('type') === 'Audio' ? 'selected' : '' }}>Audio</option>
                <option value="Video" {{ request('type') === 'Video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: bold; border-radius: 8px;">Filter</button>
        @if(request()->anyFilled(['search', 'type']))
            <a href="{{ route('member.materials', request()->has('folder_id') ? ['folder_id' => request('folder_id')] : []) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: bold; text-decoration: none; color: var(--text-primary); border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">Reset</a>
        @endif
    </form>
</div>

<!-- Folders and Materials Display -->
<div class="card" style="padding: 1.5rem;">
    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 0.95rem; color: var(--text-primary); display: flex; align-items: center; gap: 0.35rem;">
        <i class="ph ph-folder-open" style="color: var(--accent-color);"></i> Berkas & Folder
    </h4>
    
    <div class="fm-grid">
        <!-- Folders -->
        @foreach($folders as $folder)
            <div class="fm-item">
                <!-- Clickable Area -->
                <a href="{{ route('member.materials', ['folder_id' => $folder->id]) }}" style="display: flex; flex-direction: column; align-items: center; width: 100%; height: 100%; text-decoration: none; z-index: 1;">
                    <div class="fm-icon-wrapper">
                        <i class="ph-fill ph-folder" style="font-size: 3.75rem; color: #FFCD0E;"></i>
                    </div>
                    <div class="fm-name" style="color: var(--text-primary);">{{ $folder->name }}</div>
                    <div class="fm-meta" style="color: var(--text-secondary);">Folder</div>
                </a>
            </div>
        @endforeach
        
        <!-- Files -->
        @foreach($materials as $mat)
            @php
                $ext = pathinfo($mat->file_path, PATHINFO_EXTENSION);
                $isAudio = Str::endsWith($mat->file_path, '.mp3') || Str::endsWith($mat->file_path, '.wav');
                $isPdf = Str::endsWith($mat->file_path, '.pdf');
                $isVideo = Str::endsWith($mat->file_path, '.mp4') || Str::endsWith($mat->file_path, '.mov');
                
                // Icon config
                $iconClass = 'ph-fill ph-file';
                $iconColor = '#64748b';
                
                if ($isAudio) {
                    $iconClass = 'ph-fill ph-music-notes';
                    $iconColor = 'var(--accent-color)';
                } elseif ($isPdf) {
                    $iconClass = 'ph-fill ph-file-pdf';
                    $iconColor = '#ef4444';
                } elseif ($isVideo) {
                    $iconClass = 'ph-fill ph-video-camera';
                    $iconColor = '#3b82f6';
                }
            @endphp
            <div class="fm-item">
                <!-- Clickable Area for Preview -->
                <div onclick="showPreview('{{ addslashes($mat->title) }}', '{{ $ext }}', '{{ asset('storage/' . $mat->file_path) }}')" style="display: flex; flex-direction: column; align-items: center; width: 100%; height: 100%; z-index: 1;">
                    <div class="fm-icon-wrapper">
                        <i class="{{ $iconClass }}" style="font-size: 3.75rem; color: {{ $iconColor }};"></i>
                    </div>
                    <div class="fm-name">{{ $mat->title }}</div>
                    <div class="fm-meta">{{ $mat->size ? number_format($mat->size / 1024, 1) . ' KB' : '-' }}</div>
                </div>
                
                <!-- Actions -->
                <div class="fm-actions" style="z-index: 2;">
                    <a href="{{ route('member.materials.download', $mat->id) }}" class="fm-action-btn" title="Unduh">
                        <i class="ph ph-download-simple" style="font-size: 0.85rem;"></i>
                    </a>
                </div>
            </div>
        @endforeach
        
        @if(count($folders) === 0 && count($materials) === 0)
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-muted);">
                <i class="ph ph-folder-open" style="font-size: 3rem; display: block; margin-bottom: 0.5rem; opacity: 0.5; margin-left: auto; margin-right: auto;"></i>
                <p style="font-size: 0.9rem; margin: 0;">Folder ini kosong.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Pratinjau Berkas -->
<div id="previewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 800px; padding: 2rem; position: relative;">
        <button type="button" onclick="closePreview()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary);">&times;</button>
        <h4 id="preview-title" style="margin: 0 0 1.5rem 0; font-weight: 800; font-size: 1.25rem; color: var(--text-primary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 90%;">Pratinjau Berkas</h4>
        
        <div style="background: #0f172a; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; min-height: 200px; padding: 1rem;">
            <!-- Preview Elements -->
            <audio id="preview-audio" controls style="width: 100%; display: none;"></audio>
            <video id="preview-video" controls style="width: 100%; max-height: 450px; display: none;"></video>
            <iframe id="preview-pdf" style="width: 100%; height: 500px; border: none; display: none;"></iframe>
            <img id="preview-image" style="max-width: 100%; max-height: 500px; display: none;" alt="Pratinjau">
            <div id="preview-fallback" style="color: #fff; font-size: 0.9rem; text-align: center; display: none; padding: 2rem;">
                <i class="ph ph-file" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                Format berkas ini tidak mendukung pratinjau langsung. Silakan unduh berkas untuk melihat.
            </div>
        </div>
        
        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.5rem;">
            <a id="preview-download-btn" href="#" class="btn btn-primary" style="font-weight: bold; padding: 0.5rem 1.5rem; text-decoration: none; border-radius: 8px;">Unduh Berkas</a>
            <button type="button" onclick="closePreview()" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); font-weight: bold; padding: 0.5rem 1.5rem; border-radius: 8px;">Tutup</button>
        </div>
    </div>
</div>

<script>
function showPreview(title, type, url) {
    // Hide all preview elements first
    document.getElementById('preview-audio').style.display = 'none';
    document.getElementById('preview-audio').src = '';
    document.getElementById('preview-video').style.display = 'none';
    document.getElementById('preview-video').src = '';
    document.getElementById('preview-pdf').style.display = 'none';
    document.getElementById('preview-pdf').src = '';
    document.getElementById('preview-image').style.display = 'none';
    document.getElementById('preview-image').src = '';
    document.getElementById('preview-fallback').style.display = 'none';
    
    // Set title and download URL
    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-download-btn').href = url;
    
    const extension = type.toLowerCase();
    
    if (['mp3', 'wav', 'ogg'].includes(extension)) {
        const audio = document.getElementById('preview-audio');
        audio.src = url;
        audio.style.display = 'block';
    } else if (['mp4', 'mov', 'webm'].includes(extension)) {
        const video = document.getElementById('preview-video');
        video.src = url;
        video.style.display = 'block';
    } else if (['pdf'].includes(extension)) {
        const iframe = document.getElementById('preview-pdf');
        iframe.src = url;
        iframe.style.display = 'block';
    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
        const img = document.getElementById('preview-image');
        img.src = url;
        img.style.display = 'block';
    } else {
        document.getElementById('preview-fallback').style.display = 'block';
    }
    
    document.getElementById('previewModal').style.display = 'flex';
}

function closePreview() {
    document.getElementById('preview-audio').pause();
    document.getElementById('preview-audio').src = '';
    document.getElementById('preview-video').pause();
    document.getElementById('preview-video').src = '';
    document.getElementById('preview-pdf').src = '';
    document.getElementById('preview-image').src = '';
    document.getElementById('previewModal').style.display = 'none';
}
</script>
@endsection
