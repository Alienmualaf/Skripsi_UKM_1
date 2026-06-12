@extends('layouts.app')

@section('title', 'Materi Latihan')
@section('header', 'Materi Latihan PSUP')

@section('content')
@php
    $isIframe = request()->has('iframe') || request()->query('iframe') || request('iframe') || isset($_GET['iframe']) || strpos(request()->fullUrl(), 'iframe') !== false;
@endphp

@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('success') && $isIframe && (strpos(session('success'), 'Classroom') !== false || strpos(session('success'), 'terhubung') !== false))
    <script>
        @php
            $classroomUrl = $classroom->performance_id
                ? route('pengurus.programs.performance.classroom.show', [$classroom->performance->program_id, $classroom->performance_id])
                : route('pengurus.jobs.classroom.show', [$classroom->job_id]);
        @endphp
        window.parent.location.href = "{{ $classroomUrl }}";
    </script>
@endif

@if($classroom && !$isIframe)
<div class="card mb-4 animate-fade-in" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); padding: 1rem 1.5rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; align-items: center; gap: 0.5rem;">
        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(59, 130, 246, 0.2); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
            <i class="ph-fill ph-chalkboard"></i>
        </div>
        <span style="font-size: 0.9rem; color: var(--text-primary); font-weight: 600;">
            Menghubungkan Materi ke Classroom: <span style="color: var(--accent-color);">{{ $classroom->name }}</span>
        </span>
    </div>
    @php
        $backToClassroomUrl = $classroom->performance_id
            ? route('pengurus.programs.performance.classroom.show', [$classroom->performance->program_id, $classroom->performance_id])
            : route('pengurus.jobs.classroom.show', [$classroom->job_id]);
    @endphp
    <a href="{{ $backToClassroomUrl }}" class="btn btn-primary" style="padding: 0.4rem 0.85rem; font-size: 0.8rem; font-weight: 700; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
        <i class="ph ph-arrow-left"></i> Selesai & Kembali ke Classroom
    </a>
</div>
@endif

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Galeri Materi & Partitur</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola folder partitur lagu, rekaman audio per jenis suara (Sopran, Alto, Tenor, Bass) untuk dipelajari anggota.</p>
    </div>
</div>

<!-- Folder Breadcrumbs -->
<div class="card mb-6" style="padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.9rem;">
        <a href="{{ route('pengurus.materials.index', array_merge($classroom ? ['classroom_id' => $classroomId] : [], request()->has('iframe') ? ['iframe' => 1] : [])) }}" style="color: var(--accent-color); text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
            <i class="ph-fill ph-house" style="font-size: 1.15rem;"></i> Root
        </a>
        @foreach($breadcrumbs as $bc)
            <span style="color: var(--text-muted);">/</span>
            <a href="{{ route('pengurus.materials.index', array_merge(['folder_id' => $bc->id], $classroom ? ['classroom_id' => $classroomId] : [], request()->has('iframe') ? ['iframe' => 1] : [])) }}" style="color: var(--accent-color); text-decoration: none;">
                {{ $bc->name }}
            </a>
        @endforeach
    </div>

    <!-- Actions to create Folder or Upload File in current directory -->
    <div style="display: flex; gap: 0.75rem;">
        <!-- Add Folder Trigger -->
        <button onclick="document.getElementById('addFolderModal').style.display='flex'" class="btn" style="background: var(--accent-light); color: var(--accent-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-folder-plus"></i> Folder Baru
        </button>

        <!-- Upload File Trigger -->
        <button onclick="document.getElementById('uploadFileModal').style.display='flex'" class="btn btn-primary" style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-upload-simple"></i> Unggah Berkas
        </button>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="card mb-4" style="padding: 1rem 1.5rem;">
    <form action="" method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        @if(request()->has('folder_id'))
            <input type="hidden" name="folder_id" value="{{ request('folder_id') }}">
        @endif
        @if($classroom)
            <input type="hidden" name="classroom_id" value="{{ $classroomId }}">
        @endif
        @if(request()->has('iframe'))
            <input type="hidden" name="iframe" value="1">
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
            <a href="{{ route('pengurus.materials.index', array_merge(request()->has('folder_id') ? ['folder_id' => request('folder_id')] : [], $classroom ? ['classroom_id' => $classroomId] : [], request()->has('iframe') ? ['iframe' => 1] : [])) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: bold; text-decoration: none; color: var(--text-primary); border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">Reset</a>
        @endif
    </form>
</div>

<!-- Folders and Materials Display -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- Folder List -->
    <div class="card md:col-span-1" style="padding: 1.5rem; height: fit-content;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 0.95rem; color: var(--text-primary); display: flex; align-items: center; gap: 0.35rem;">
            <i class="ph ph-folder" style="color: var(--accent-color);"></i> Sub-Folder
        </h4>
        
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @forelse($folders as $folder)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-color);">
                    <a href="{{ route('pengurus.materials.index', array_merge(['folder_id' => $folder->id], $classroom ? ['classroom_id' => $classroomId] : [], request()->has('iframe') ? ['iframe' => 1] : [])) }}" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--text-primary); text-decoration: none; flex: 1;">
                        <i class="ph-fill ph-folder" style="font-size: 1.25rem; color: #eab308;"></i>
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 120px;">{{ $folder->name }}</span>
                    </a>
                    
                    <form action="{{ route('pengurus.folders.destroy', $folder->id) }}" method="POST" onsubmit="return confirm('Hapus folder ini beserta seluruh isinya secara permanen?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; padding: 0.25rem; cursor: pointer; color: var(--danger-color); display: flex; align-items: center;">
                            <i class="ph ph-trash" style="font-size: 1rem;"></i>
                        </button>
                    </form>
                </div>
            @empty
                <p style="font-size: 0.75rem; color: var(--text-muted); text-align: center; margin: 1rem 0;">Tidak ada sub-folder.</p>
            @endforelse
        </div>
    </div>

    <!-- Materials list -->
    <div class="card md:col-span-3" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 0.95rem; color: var(--text-primary); display: flex; align-items: center; gap: 0.35rem;">
            <i class="ph ph-file" style="color: var(--accent-color);"></i> Berkas Latihan
        </h4>

        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Berkas</th>
                        <th>Ukuran</th>
                        <th>Tipe</th>
                        <th style="min-width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $mat)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @if(Str::endsWith($mat->file_path, '.mp3') || Str::endsWith($mat->file_path, '.wav'))
                                    <i class="ph-fill ph-music-notes" style="font-size: 1.25rem; color: var(--accent-color);"></i>
                                @elseif(Str::endsWith($mat->file_path, '.pdf'))
                                    <i class="ph-fill ph-file-pdf" style="font-size: 1.25rem; color: #ef4444;"></i>
                                @else
                                    <i class="ph-fill ph-file" style="font-size: 1.25rem; color: #64748b;"></i>
                                @endif
                                <span>{{ $mat->title }}</span>
                            </div>
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.8rem;">
                            {{ $mat->size ? number_format($mat->size / 1024, 1) . ' KB' : '-' }}
                        </td>
                        <td style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase;">
                            {{ pathinfo($mat->file_path, PATHINFO_EXTENSION) }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center; align-items: center;">
                                @if($classroom)
                                    @if($classroom->materials->contains($mat->id))
                                        <button type="button" class="btn" disabled style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem; border-radius: 6px; cursor: not-allowed; width: 100%; justify-content: center;">
                                            <i class="ph ph-check-circle"></i> Terhubung
                                        </button>
                                    @else
                                        @php
                                            $addRoute = $classroom->performance_id
                                                ? route('pengurus.programs.performance.classroom.materials.add', [$classroom->performance->program_id, $classroom->performance_id])
                                                : route('pengurus.jobs.classroom.materials.add', [$classroom->job_id]);
                                        @endphp
                                        <form action="{{ $addRoute }}" method="POST" style="margin: 0; display: inline; flex: 1;">
                                            @csrf
                                            <input type="hidden" name="material_id" value="{{ $mat->id }}">
                                            <input type="hidden" name="redirect_to_materials" value="1">
                                            @if(request()->has('iframe'))
                                                <input type="hidden" name="iframe" value="1">
                                            @endif
                                            <button type="submit" class="btn btn-success" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem; background: var(--success-color); color: #fff; border: none; cursor: pointer;">
                                                <i class="ph ph-plus-circle"></i> Pilih Berkas
                                            </button>
                                        </form>
                                        <button type="button" onclick="showPreview('{{ addslashes($mat->title) }}', '{{ pathinfo($mat->file_path, PATHINFO_EXTENSION) }}', '{{ asset('storage/' . $mat->file_path) }}')" class="btn" style="background: var(--accent-light); color: var(--accent-color); border: 1px solid var(--accent-color); padding: 0.35rem; font-size: 0.95rem; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;" title="Pratinjau">
                                            <i class="ph ph-eye"></i>
                                        </button>
                                    @endif
                                @else
                                    <button type="button" onclick="showPreview('{{ addslashes($mat->title) }}', '{{ pathinfo($mat->file_path, PATHINFO_EXTENSION) }}', '{{ asset('storage/' . $mat->file_path) }}')" class="btn" style="background: var(--accent-light); color: var(--accent-color); border: 1px solid var(--accent-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem; border-radius: 6px;">
                                        <i class="ph ph-eye"></i> Pratinjau
                                    </button>
                                    <a href="{{ route('pengurus.materials.download', $mat->id) }}" class="btn" style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 600; color: #475569; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; border-radius: 6px;">
                                        <i class="ph ph-download-simple"></i> Unduh
                                    </a>
                                    <form action="{{ route('pengurus.materials.destroy', $mat->id) }}" method="POST" onsubmit="return confirm('Hapus berkas ini?');" style="margin: 0; display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 600; border-radius: 6px;"><i class="ph ph-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">Belum ada berkas materi di folder ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Folder Baru -->
<div id="addFolderModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; color: var(--text-primary);">Buat Folder Baru</h4>
        <form action="{{ route('pengurus.folders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? '' }}">
            
            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Folder</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Lagu Kebangsaan" required style="padding: 0.65rem;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="document.getElementById('addFolderModal').style.display='none'" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); font-weight: bold; padding: 0.5rem 1rem;">Batal</button>
                <button type="submit" class="btn btn-primary" style="font-weight: bold; padding: 0.5rem 1rem;">Buat Folder</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Unggah Berkas -->
<div id="uploadFileModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; color: var(--text-primary);">Unggah Berkas Baru</h4>
        <form action="{{ route('pengurus.materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="folder_id" value="{{ $currentFolder->id ?? '' }}">
            
            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama/Judul Materi <span style="color: var(--danger-color);">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Partitur Indonesia Raya" required style="padding: 0.65rem;">
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Jenis Materi <span style="color: var(--danger-color);">*</span></label>
                <select name="type" class="form-control" required style="padding: 0.65rem;">
                    <option value="Partitur">Partitur (PDF/Gambar)</option>
                    <option value="Audio">Audio (MP3/WAV)</option>
                    <option value="Video">Video (MP4/MOV)</option>
                </select>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Pilih Berkas <span style="color: var(--danger-color);">*</span></label>
                <input type="file" name="file" class="form-control" required style="padding: 0.5rem;">
                <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">Format: PDF, DOCX, MP3, WAV, MP4, MOV (Maks. 50MB).</p>
            </div>

            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Deskripsi</label>
                <textarea name="description" class="form-control" placeholder="Tulis catatan atau rincian materi di sini..." rows="3" style="padding: 0.65rem;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="document.getElementById('uploadFileModal').style.display='none'" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); font-weight: bold; padding: 0.5rem 1rem;">Batal</button>
                <button type="submit" class="btn btn-primary" style="font-weight: bold; padding: 0.5rem 1rem;">Unggah</button>
            </div>
        </form>
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
