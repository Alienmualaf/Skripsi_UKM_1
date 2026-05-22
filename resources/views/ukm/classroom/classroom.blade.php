@extends('layouts.app')

@section('title', 'Classroom Admin: ' . $event->title)

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #10b981;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="card mb-4 animate-fade-in" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #ef4444;">
        <i class="ph-fill ph-x-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('error') }}
    </div>
@endif

<div class="animate-fade-in">
    <!-- Header / Banner Agenda -->
    <div style="background: linear-gradient(135deg, var(--accent-color), #2563eb); color: white; border-radius: var(--radius-md); padding: 2.5rem 2rem; margin-bottom: 2rem; position: relative; overflow: hidden; box-shadow: 0 10px 20px -5px rgba(30, 64, 175, 0.15); border-top: 4px solid var(--warning-color);">
        <div style="position: relative; z-index: 2;">
            <span class="badge" style="background: rgba(255, 255, 255, 0.15); color: white; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.725rem; border: 1px solid rgba(255, 255, 255, 0.25); text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 0.75rem;">
                Classroom Admin (Agenda)
            </span>
            <h1 style="font-size: 2rem; font-weight: 800; margin: 0; letter-spacing: -0.025em; color: #ffffff; font-family: 'Outfit', sans-serif;">{{ $event->title }}</h1>
            <p style="font-size: 1rem; opacity: 0.85; margin: 0.5rem 0 0 0; font-weight: 500; display: flex; align-items: center; gap: 0.4rem;">
                <i class="ph ph-calendar"></i> Mulai: {{ $event->start_date->format('d M Y') }} • <i class="ph ph-hash"></i> {{ $ukm->name }}
            </p>
        </div>
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.07); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
    </div>

    <!-- Navigation Tabs (Vercel-Style Segmented Pills) -->
    <div style="display: flex; gap: 0.35rem; background: var(--bg-color); border: 1px solid var(--border-color); padding: 4px; border-radius: 8px; margin-bottom: 2.25rem; width: max-content; font-family: 'Outfit', sans-serif;">
        <button onclick="switchClassroomTab('stream')" class="classroom-tab-btn {{ $tab === 'stream' ? 'active' : '' }}" data-tab="stream">
            <i class="ph ph-megaphone" style="font-size: 1rem;"></i> Informasi & Forum
        </button>
        <button onclick="switchClassroomTab('materials')" class="classroom-tab-btn {{ $tab === 'materials' ? 'active' : '' }}" data-tab="materials">
            <i class="ph ph-notebook" style="font-size: 1rem;"></i> Kelola Materi
        </button>
        <button onclick="switchClassroomTab('attendances')" class="classroom-tab-btn {{ $tab === 'attendances' ? 'active' : '' }}" data-tab="attendances">
            <i class="ph ph-list-checks" style="font-size: 1rem;"></i> Presensi & Sesi
        </button>
        <button onclick="switchClassroomTab('members')" class="classroom-tab-btn {{ $tab === 'members' ? 'active' : '' }}" data-tab="members">
            <i class="ph ph-users-three" style="font-size: 1rem;"></i> Anggota & Orang
        </button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        
        <!-- Tab: Stream (Informasi & Forum) -->
        <div id="tab-content-stream" class="classroom-tab-content" style="display: {{ $tab === 'stream' ? 'grid' : 'none' }}; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">
            <!-- Left Info Sidebar -->
            <div class="hidden-mobile" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="card" style="padding: 1.25rem; border-top: 3px solid var(--warning-color); margin-bottom: 0;">
                    <h4 style="font-weight: 800; font-size: 0.875rem; margin: 0 0 0.5rem 0; color: var(--text-primary); font-family: 'Outfit', sans-serif;">Info Ringkas</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.8rem; color: var(--text-secondary);">
                        <div>Peserta: <strong>{{ count($participants) }} orang</strong></div>
                        <div>Materi: <strong>{{ count($materials) }} file/link</strong></div>
                        <div>Pertemuan: <strong>{{ count($sessions) }} sesi</strong></div>
                    </div>
                </div>
            </div>

            <!-- Feed Thread -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <!-- Post Announcement Card -->
                <div class="card" style="padding: 1.5rem; margin-bottom: 0;">
                    <h4 style="font-weight: 800; font-size: 1.1rem; margin-top: 0; margin-bottom: 1rem; color: var(--text-primary);">Buat Pengumuman Baru</h4>
                    <form action="/ukm/announcements" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Judul Pengumuman</label>
                            <input type="text" name="title" class="form-control" required placeholder="Contoh: Pengumuman Pertemuan Minggu Ini" style="border-radius: 10px;">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Isi Pengumuman</label>
                            <textarea name="content" class="form-control" rows="3" required placeholder="Tulis rincian pengumuman di sini..." style="border-radius: 10px;"></textarea>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.75rem; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <i class="ph ph-paper-plane-tilt"></i> Terbitkan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Announcements Timeline -->
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($announcements as $ann)
                    <div class="card" style="padding: 1.5rem; display: flex; gap: 1.25rem; align-items: flex-start; border-left: 3px solid var(--accent-color); margin-bottom: 0;">
                        <div style="width: 38px; height: 38px; background: var(--accent-light); color: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: bold;">
                            {{ strtoupper(substr($ann->creator->name, 0, 1)) }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                                <div>
                                    <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">{{ $ann->creator->name }}</span>
                                    <span style="color: var(--text-secondary); font-size: 0.775rem; margin-left: 0.5rem;">• {{ $ann->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <form action="/ukm/announcements/{{ $ann->id }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: var(--danger-color); cursor: pointer; font-size: 1.15rem;" title="Hapus Pengumuman">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <h5 style="margin: 0 0 0.5rem 0; font-weight: 800; font-size: 1rem; color: var(--text-primary);">{{ $ann->title }}</h5>
                            <div style="font-size: 0.875rem; line-height: 1.55; color: var(--text-primary);">
                                {!! nl2br(e($ann->content)) !!}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 4rem 2rem; background: var(--surface-color); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                        <i class="ph ph-chat-circle-dots" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; color: var(--text-secondary);"></i>
                        <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada pengumuman di agenda ini.</p>
                        <p style="font-size: 0.8125rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Gunakan formulir di atas untuk menerbitkan informasi baru.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tab: Materials (Kelola Materi) -->
        <div id="tab-content-materials" class="classroom-tab-content" style="max-width: 850px; margin: 0 auto; display: {{ $tab === 'materials' ? 'flex' : 'none' }}; flex-direction: column; gap: 1.5rem;">
            
            <!-- Upload Material Form -->
            <div class="card" style="padding: 1.5rem; margin-bottom: 0;">
                <h4 style="font-weight: 800; font-size: 1.1rem; margin-top: 0; margin-bottom: 1.25rem; color: var(--text-primary);">Unggah Materi Pembelajaran Baru</h4>
                <form action="/ukm/materials" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Judul Materi</label>
                            <input type="text" name="title" class="form-control" required placeholder="Contoh: Slide Presentasi Teori Dasar" style="border-radius: 10px;">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Jenis</label>
                            <select name="type" class="form-control" required style="border-radius: 10px;">
                                <option value="dokumen">Dokumen</option>
                                <option value="audio">Audio</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Unggah File (Max 10MB)</label>
                            <input type="file" name="file" class="form-control" style="border-radius: 10px;">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Atau Tautan Eksternal (Opsional)</label>
                            <input type="url" name="link" class="form-control" placeholder="https://youtube.com/..." style="border-radius: 10px;">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Deskripsi / Instruksi</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Tulis instruksi atau keterangan materi di sini..." style="border-radius: 10px;"></textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.65rem 2rem; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="ph ph-upload-simple"></i> Simpan Materi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Materials List -->
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <h4 style="font-weight: 800; font-size: 1.15rem; color: var(--text-primary); margin: 0.5rem 0;">Daftar Materi yang Dibagikan</h4>
                
                @forelse($materials as $material)
                <div class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; transition: all 0.2s; border-left: 3px solid var(--accent-color); margin-bottom: 0;">
                    <div style="display: flex; align-items: center; gap: 1rem; min-width: 0; flex: 1;">
                        <div style="width: 42px; height: 42px; background: var(--accent-light); color: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.25rem;">
                            <i class="ph ph-{{ $material->type === 'audio' ? 'music-note' : ($material->type === 'video' ? 'video-camera' : 'file-text') }}"></i>
                        </div>
                        <div style="min-width: 0; flex: 1;">
                            <h4 style="font-weight: 700; margin: 0; font-size: 0.925rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-family: 'Outfit', sans-serif;">{{ $material->title }}</h4>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.725rem; color: var(--text-secondary);">
                                Diposting {{ $material->created_at->format('d M Y') }} 
                                @if($material->description) • {{ Str::limit($material->description, 50) }} @endif
                            </p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-shrink: 0;">
                        @if($material->file_path)
                            <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn btn-secondary" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center;" title="Unduh File">
                                <i class="ph ph-download-simple"></i>
                            </a>
                        @endif
                        @if($material->link)
                            <a href="{{ $material->link }}" target="_blank" class="btn btn-secondary" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; color: var(--success-color);" title="Buka Link">
                                <i class="ph ph-link"></i>
                            </a>
                        @endif
                        
                        <form action="/ukm/materials/{{ $material->id }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding: 0.45rem 0.6rem; font-size: 0.8rem; border-radius: 8px;">
                                <i class="ph ph-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 4rem 2rem; background: var(--surface-color); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <i class="ph ph-books" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; color: var(--text-secondary);"></i>
                    <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada materi dibagikan.</p>
                    <p style="font-size: 0.8125rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Materi pengajaran akan muncul di sini setelah Anda mengunggahnya.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Tab: Attendances (Presensi & Sesi) -->
        <div id="tab-content-attendances" class="classroom-tab-content" style="max-width: 850px; margin: 0 auto; display: {{ $tab === 'attendances' ? 'flex' : 'none' }}; flex-direction: column; gap: 1.5rem;">
            <!-- Create Session Card -->
            <div class="card" style="padding: 1.5rem; margin-bottom: 0;">
                <h4 style="font-weight: 800; font-size: 1.1rem; margin-top: 0; margin-bottom: 1.25rem; color: var(--text-primary);">Buat Pertemuan / Sesi Absensi Baru</h4>
                <form action="/ukm/events/{{ $event->id }}/sessions" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Nama Pertemuan</label>
                            <input type="text" name="title" class="form-control" required placeholder="Contoh: Pertemuan 1 - Pengenalan Dasar" style="border-radius: 10px;">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Tanggal</label>
                            <input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}" style="border-radius: 10px;">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label" style="font-weight: 600; font-size: 0.85rem;">Deskripsi Pertemuan (Opsional)</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Tulis instruksi atau catatan khusus untuk pertemuan ini..." style="border-radius: 10px;"></textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.65rem 2rem; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="ph ph-plus-circle"></i> Tambah Pertemuan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sessions List -->
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <h4 style="font-weight: 800; font-size: 1.15rem; color: var(--text-primary); margin: 0.5rem 0;">Daftar Sesi & Kehadiran</h4>

                @forelse($sessions as $session)
                @php
                    $filledCount = $session->attendances->count();
                @endphp
                <div class="card" style="padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; transition: all 0.2s; border-left: 3px solid var(--accent-color); margin-bottom: 0;">
                    <div style="min-width: 0; flex: 1;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                            <h4 style="font-weight: 800; margin: 0; font-size: 1rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">{{ $session->title }}</h4>
                            <span style="font-size: 0.725rem; color: var(--text-secondary); background: var(--bg-color); padding: 0.15rem 0.5rem; border-radius: 6px; border: 1px solid var(--border-color); font-weight: 600;">
                                {{ $session->date->format('d M Y') }}
                            </span>
                        </div>
                        <p style="margin: 0; font-size: 0.8rem; color: var(--text-secondary);">
                            {{ $session->description ?: 'Tidak ada deskripsi pertemuan.' }}
                        </p>
                    </div>
                    <div style="display: flex; gap: 1rem; align-items: center; flex-shrink: 0;">
                        <div>
                            @if($filledCount > 0)
                                <span class="badge badge-approved" style="font-size: 0.725rem; font-weight: 700; padding: 0.25rem 0.5rem;">
                                    {{ $filledCount }} Terisi
                                </span>
                            @else
                                <span class="badge" style="background: var(--border-color); color: var(--text-secondary); font-size: 0.725rem; font-weight: 700; padding: 0.25rem 0.5rem;">
                                    Belum Diisi
                                </span>
                            @endif
                        </div>
                        <div style="display: flex; gap: 0.35rem; align-items: center;">
                            <a href="{{ route('ukm.sessions.attendance', $session->id) }}" class="btn btn-primary" style="padding: 0.45rem 0.75rem; font-size: 0.75rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;" title="Absen Anggota">
                                <i class="ph ph-users"></i> Absen Anggota
                            </a>
                            <a href="/ukm/coach-attendances/session/{{ $session->id }}" class="btn btn-secondary" style="padding: 0.45rem 0.75rem; font-size: 0.75rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem; background: var(--accent-light); color: var(--accent-color); border: 1px solid transparent;" title="Absen Pelatih">
                                <i class="ph ph-chalkboard-teacher"></i> Absen Pelatih
                            </a>
                            <form action="/ukm/sessions/{{ $session->id }}" method="POST" onsubmit="return confirm('Hapus pertemuan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding: 0.45rem 0.5rem; font-size: 0.75rem; border-radius: 8px;" title="Hapus Sesi">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 4rem 2rem; background: var(--surface-color); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <i class="ph ph-calendar-blank" style="font-size: 3rem; opacity: 0.2; color: var(--text-secondary);"></i>
                    <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada pertemuan/sesi absensi.</p>
                    <p style="font-size: 0.8125rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Gunakan formulir di atas untuk membuat sesi absensi baru.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Tab: Members (Anggota & Orang) -->
        <div id="tab-content-members" class="classroom-tab-content" style="max-width: 850px; margin: 0 auto; display: {{ $tab === 'members' ? 'flex' : 'none' }}; flex-direction: column; gap: 2rem;">
            
            <!-- Coaches list -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem;">
                    <h4 style="font-size: 1.15rem; font-weight: 800; color: var(--accent-color); margin: 0; font-family: 'Outfit', sans-serif;">Pengajar & Pelatih Terlibat</h4>
                    <span style="font-size: 0.75rem; font-weight: 700; background: var(--accent-light); color: var(--accent-color); padding: 0.2rem 0.5rem; border-radius: 6px;">{{ count($coaches) }} Orang</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @forelse($coaches as $coach)
                    <div class="card" style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; margin-bottom: 0; border-left: 3px solid var(--accent-color);">
                        @if($coach->photo)
                            <img src="{{ asset('storage/' . $coach->photo) }}" style="width: 36px; height: 36px; border-radius: 8px; object-fit: cover;">
                        @else
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--accent-light); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--accent-color); font-size: 0.9rem;">{{ substr($coach->name, 0, 1) }}</div>
                        @endif
                        <div style="min-width: 0; flex: 1;">
                            <h5 style="margin: 0; font-weight: 700; font-size: 0.875rem; color: var(--text-primary);">{{ $coach->name }}</h5>
                            <p style="margin: 2px 0 0 0; font-size: 0.725rem; color: var(--text-secondary);">{{ $coach->category }}</p>
                        </div>
                    </div>
                    @empty
                    <p style="font-size: 0.85rem; color: var(--text-secondary); text-align: center; padding: 1.5rem;">Belum ada pelatih terlibat.</p>
                    @endforelse
                </div>
            </div>

            <!-- Participants list -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem;">
                    <h4 style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); margin: 0; font-family: 'Outfit', sans-serif;">Peserta Kegiatan (Anggota)</h4>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span style="font-size: 0.75rem; font-weight: 700; background: var(--bg-color); color: var(--text-secondary); padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid var(--border-color);">{{ count($participants) }} Orang</span>
                        <a href="/ukm/events/{{ $event->id }}/participants" class="btn btn-primary" style="padding: 0.4rem 0.85rem; font-size: 0.775rem; border-radius: 8px; font-weight: 700;">
                            <i class="ph ph-user-plus"></i> Kelola Peserta
                        </a>
                    </div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @forelse($participants as $part)
                    <div class="card" style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; margin-bottom: 0;">
                        @if($part->user->photo)
                            <img src="{{ asset('storage/' . $part->user->photo) }}" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;">
                        @else
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--bg-color); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; color: var(--text-secondary); border: 1px solid var(--border-color);">{{ substr($part->user->name, 0, 1) }}</div>
                        @endif
                        <div style="min-width: 0; flex: 1; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                            <span style="font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">{{ $part->user->name }}</span>
                            @if($part->classification)
                                <span style="font-size: 0.725rem; color: var(--text-secondary); background: var(--bg-color); padding: 2px 8px; border-radius: 6px; border: 1px solid var(--border-color); font-weight: 600;">{{ $part->classification->name }}</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 3rem; border: 1px dashed var(--border-color); border-radius: 12px;">
                        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">Belum ada peserta terdaftar dalam agenda ini.</p>
                        <a href="/ukm/events/{{ $event->id }}/participants" class="btn btn-primary" style="padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">
                            Hubungkan Peserta
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>

<style>
    .classroom-tab-btn {
        padding: 0.5rem 1.25rem;
        font-weight: 700;
        border-radius: 6px;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--text-secondary);
        border: 1px solid transparent;
        background: transparent;
        cursor: pointer;
    }
    .classroom-tab-btn.active {
        background: var(--surface-color);
        color: var(--accent-color);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }
    .tab-content {
        min-height: 400px;
    }
    @media (max-width: 768px) {
        .hidden-mobile { display: none; }
    }
</style>

<script>
    function switchClassroomTab(tabId) {
        // 1. Hide all tab content
        const contents = document.querySelectorAll('.classroom-tab-content');
        contents.forEach(content => {
            content.style.display = 'none';
        });

        // 2. Remove active class from buttons
        const buttons = document.querySelectorAll('.classroom-tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });

        // 3. Display clicked tab content
        const targetContent = document.getElementById('tab-content-' + tabId);
        if (targetContent) {
            if (tabId === 'stream') {
                targetContent.style.display = 'grid';
            } else {
                targetContent.style.display = 'flex';
            }
        }

        // 4. Set active button class
        const targetButton = document.querySelector(`.classroom-tab-btn[data-tab="${tabId}"]`);
        if (targetButton) {
            targetButton.classList.add('active');
        }

        // 5. Update URL query parameter
        const newUrl = window.location.pathname + '?tab=' + tabId;
        window.history.replaceState({ tab: tabId }, '', newUrl);
    }
</script>
@endsection
