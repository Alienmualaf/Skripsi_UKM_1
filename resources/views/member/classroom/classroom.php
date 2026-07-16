@extends('layouts.app')

@php
    $classroomTitle = $classroom->performance->title ?? 'Pusat Latihan';
    $classroomDate = $classroom->performance->performance_date ?? now();
    $classroomVenue = $classroom->performance->venue ?? '-';
    $classroomType = ($classroom->performance ? (is_null($classroom->performance->program_id) ? 'Job' : ($classroom->performance->program && $classroom->performance->program->activity_type === 'Competition' ? 'Lomba' : 'Penampilan')) : 'Pusat Latihan');
@endphp

@section('title', 'Pusat Latihan: ' . $classroomTitle)
@section('header', 'Pusat Latihan: ' . $classroomTitle)

@section('content')
<link rel="stylesheet" href="{{ asset('css/member.css') }}">

<div class="animate-fade-in">
    <!-- Header / Banner Pusat Latihan -->
    <div style="background: linear-gradient(135deg, var(--accent-color) 0%, #1d4ed8 100%); color: white; border-radius: 16px; padding: 2.5rem 2rem; margin-bottom: 2rem; position: relative; overflow: hidden; box-shadow: 0 10px 20px -5px rgba(29, 78, 216, 0.15);">
        <div style="position: relative; z-index: 2;">
            <span class="badge" style="background: rgba(255, 255, 255, 0.15); color: white; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.725rem; border: 1px solid rgba(255, 255, 255, 0.25); text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 0.75rem;">
                Pusat Latihan {{ $classroomType }}
            </span>
            <h1 style="font-size: 2.25rem; font-weight: 800; margin: 0; letter-spacing: -0.025em; color: #ffffff; font-family: 'Georgia', serif;">{{ $classroomTitle }}</h1>
            <p style="font-size: 1rem; opacity: 0.9; margin: 0.5rem 0 0 0; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <i class="ph ph-calendar"></i> {{ date('d M Y', strtotime($classroomDate)) }} • 
                <i class="ph ph-map-pin"></i> {{ $classroomVenue }} •
                <i class="ph ph-chalkboard-teacher"></i> Pelatih: <strong>{{ $classroom->trainer ? $classroom->trainer->name : 'Belum Ditentukan' }}</strong>
            </p>
        </div>
        <!-- Decorative Shapes -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.07); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
    </div>

    <!-- Navigation Tabs (Premium Segmented Pills) -->
    <div style="display: flex; gap: 0.35rem; background: var(--bg-color); border: 1px solid var(--border-color); padding: 4px; border-radius: 8px; margin-bottom: 2.25rem; width: max-content; overflow-x: auto; max-width: 100%; font-family: 'Plus Jakarta Sans', sans-serif;">
        <button onclick="switchClassroomTab('stream')" class="classroom-tab-btn {{ $tab === 'stream' ? 'active' : '' }}" data-tab="stream">
            <i class="ph ph-megaphone" style="font-size: 1rem;"></i> Pengumuman
        </button>
        <button onclick="switchClassroomTab('materials')" class="classroom-tab-btn {{ $tab === 'materials' ? 'active' : '' }}" data-tab="materials">
            <i class="ph ph-notebook" style="font-size: 1rem;"></i> Materi
        </button>
        <button onclick="switchClassroomTab('song-targets')" class="classroom-tab-btn {{ $tab === 'song-targets' ? 'active' : '' }}" data-tab="song-targets">
            <i class="ph ph-music-notes" style="font-size: 1rem;"></i> Target Lagu
        </button>
        <button onclick="switchClassroomTab('members')" class="classroom-tab-btn {{ $tab === 'members' ? 'active' : '' }}" data-tab="members">
            <i class="ph ph-users-three" style="font-size: 1rem;"></i> Peserta
        </button>
        <button onclick="switchClassroomTab('attendances')" class="classroom-tab-btn {{ $tab === 'attendances' ? 'active' : '' }}" data-tab="attendances">
            <i class="ph ph-list-checks" style="font-size: 1rem;"></i> Presensi Saya
        </button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Tab: Stream (Announcements) -->
        <div id="tab-content-stream" class="classroom-tab-content" style="display: {{ $tab === 'stream' ? 'grid' : 'none' }}; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">
            <!-- Left Sidebar -->
            <div class="hidden-mobile" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="card" style="padding: 1.25rem; border-radius: 12px;">
                    <h4 style="font-weight: 800; font-size: 0.875rem; margin: 0 0 0.5rem 0; color: var(--text-primary); font-family: 'Georgia', serif;">Jadwal Latihan Terdekat</h4>
                    @if($schedules->where('date', '>=', date('Y-m-d'))->count() > 0)
                        @foreach($schedules->where('date', '>=', date('Y-m-d'))->take(2) as $sch)
                            <div style="border-left: 3px solid var(--accent-color); padding-left: 0.75rem; margin-top: 0.75rem;">
                                <div style="font-weight: bold; font-size: 0.8rem; color: var(--text-primary);">{{ $sch->title }}</div>
                                <div style="font-size: 0.725rem; color: var(--text-secondary); margin-top: 0.15rem;">
                                    {{ date('d M Y', strtotime($sch->date)) }} ({{ date('H:i', strtotime($sch->start_time)) }} WIB)
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="font-size: 0.775rem; color: var(--text-secondary); line-height: 1.4; margin: 0 0 0.5rem 0;">Tidak ada latihan terdekat saat ini.</p>
                    @endif
                </div>
            </div>

            <!-- Feed Thread -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @forelse($announcements as $ann)
                <div class="card" style="padding: 1.5rem; display: flex; gap: 1.25rem; align-items: flex-start; border-radius: 12px; border: 1px solid var(--border-color);">
                    <div style="width: 38px; height: 38px; background: var(--accent-light); color: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: bold;">
                        {{ strtoupper(substr($ann->creator->name ?? 'A', 0, 1)) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                            <div>
                                <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary); font-family: 'Plus Jakarta Sans', sans-serif;">{{ $ann->creator->name ?? 'Pengurus' }}</span>
                                <span style="color: var(--text-secondary); font-size: 0.775rem; margin-left: 0.5rem;">• {{ $ann->created_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                        <div style="font-size: 0.875rem; line-height: 1.6; color: var(--text-primary);">
                            {!! nl2br(e($ann->content)) !!}
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 4rem 2rem; border-radius: 12px;" class="card">
                    <i class="ph ph-chat-circle-dots" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; color: var(--text-secondary);"></i>
                    <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada pengumuman kelas.</p>
                    <p style="font-size: 0.8125rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Informasi terbaru dari pelatih akan muncul di sini.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Tab: Materials -->
        <div id="tab-content-materials" class="classroom-tab-content" style="max-width: 800px; margin: 0 auto; display: {{ $tab === 'materials' ? 'flex' : 'none' }}; flex-direction: column; gap: 0.75rem;">
            @forelse($materials as $material)
            <div class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; transition: all 0.2s; border-radius: 12px; border: 1px solid var(--border-color);">
                <div style="display: flex; align-items: center; gap: 1rem; min-width: 0; flex: 1;">
                    <div style="width: 42px; height: 42px; background: var(--accent-light); color: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.25rem;">
                        <i class="ph ph-file-text"></i>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <h4 style="font-weight: 700; margin: 0; font-size: 0.925rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-family: 'Georgia', serif;">{{ $material->title }}</h4>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.725rem; color: var(--text-secondary);">Kategori: {{ $material->category ?? 'Materi' }}</p>
                    </div>
                </div>
                <div style="flex-shrink: 0; display: flex; gap: 0.5rem; align-items: center;">
                    @if($material->file_path)
                        <a href="{{ route('member.materials.download', $material->id) }}" class="btn btn-secondary" style="padding: 0.45rem 1rem; font-size: 0.8rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                            <i class="ph ph-download"></i> Unduh File
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 4rem 2rem; border-radius: 12px;" class="card">
                <i class="ph ph-books" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; color: var(--text-secondary);"></i>
                <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada materi dibagikan.</p>
                <p style="font-size: 0.8125rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Partitur atau panduan vokal akan ditautkan di sini.</p>
            </div>
            @endforelse
        </div>

        <!-- Tab: Song Targets -->
        <div id="tab-content-song-targets" class="classroom-tab-content" style="max-width: 800px; margin: 0 auto; display: {{ $tab === 'song-targets' ? 'flex' : 'none' }}; flex-direction: column; gap: 0.75rem;">
            @forelse($songTargets as $target)
            <div class="card" style="padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
                <div style="display: flex; align-items: center; gap: 1rem; min-width: 0; flex: 1;">
                    <div style="width: 42px; height: 42px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.25rem;">
                        <i class="ph-fill ph-music-notes"></i>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <h4 style="font-weight: 700; margin: 0; font-size: 0.95rem; color: var(--text-primary); font-family: 'Georgia', serif;">{{ $target->song_title }}</h4>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.775rem; color: var(--text-secondary);">
                            Komposer: {{ $target->composer ?? '-' }} | Bagian Suara: <strong>{{ $target->voice_part ?? 'Semua Vokal' }}</strong>
                        </p>
                        @if($target->notes)
                            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; color: var(--text-secondary); font-style: italic; background: var(--bg-color); padding: 0.4rem; border-radius: 6px; border-left: 2px solid #3b82f6;">
                                Catatan: {{ $target->notes }}
                            </p>
                        @endif
                    </div>
                </div>
                <div style="flex-shrink: 0;">
                    <span style="font-size: 0.725rem; font-weight: bold; padding: 0.35rem 0.65rem; border-radius: 8px; text-transform: uppercase; background: {{ $target->status === 'Selesai' ? '#d1fae5' : '#fffbeb' }}; color: {{ $target->status === 'Selesai' ? '#065f46' : '#92400e' }};">
                        {{ $target->status }}
                    </span>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 4rem 2rem; border-radius: 12px;" class="card">
                <i class="ph ph-playlist" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem; color: var(--text-secondary);"></i>
                <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada target lagu ditetapkan.</p>
                <p style="font-size: 0.8125rem; color: var(--text-secondary); margin: 0.25rem 0 0 0;">Lagu-lagu yang akan dibawakan akan dicatat di sini.</p>
            </div>
            @endforelse
        </div>

        <!-- Tab: Members -->
        <div id="tab-content-members" class="classroom-tab-content" style="max-width: 800px; margin: 0 auto; display: {{ $tab === 'members' ? 'flex' : 'none' }}; flex-direction: column; gap: 2rem;">
            <!-- Peserta -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem;">
                    <h4 style="font-size: 1rem; font-weight: 800; color: var(--text-primary); margin: 0; font-family: 'Georgia', serif;">Peserta Terdaftar</h4>
                    <span style="font-size: 0.75rem; font-weight: 700; background: var(--bg-color); color: var(--text-secondary); padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid var(--border-color);">{{ count($participants) }} Orang</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach($participants as $part)
                    <div class="card" style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; margin-bottom: 0; border-radius: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--bg-color); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; color: var(--text-secondary); border: 1px solid var(--border-color);">
                            {{ strtoupper(substr($part->name, 0, 1)) }}
                        </div>
                        <div style="min-width: 0; flex: 1; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                            <span style="font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">{{ $part->name }}</span>
                            @if($part->voiceClassification)
                                <span style="font-size: 0.725rem; color: var(--text-secondary); background: var(--bg-color); padding: 2px 8px; border-radius: 6px; border: 1px solid var(--border-color); font-weight: 600;">
                                    {{ $part->voiceClassification->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tab: Attendances -->
        <div id="tab-content-attendances" class="classroom-tab-content" style="max-width: 800px; margin: 0 auto; display: {{ $tab === 'attendances' ? 'flex' : 'none' }}; flex-direction: column; gap: 1.5rem;">
            <!-- Personal Stats Summary Card -->
            <div class="card animate-fade-in" style="padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem; border-radius: 12px;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; border-radius: 8px; background: var(--accent-light); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                        <i class="ph ph-chart-line-up"></i>
                    </div>
                    <div>
                        <h4 style="font-weight: 800; margin: 0; font-size: 1rem; color: var(--text-primary); font-family: 'Georgia', serif;">Rangkuman Kehadiran Anda</h4>
                        <p style="color: var(--text-secondary); font-size: 0.8rem; margin: 0.25rem 0 0 0;">
                             Total Sesi Presensi: <strong style="color: var(--accent-color);">{{ $totalSessions }} Sesi</strong>
                        </p>
                    </div>
                </div>
                
                <!-- Stats Breakdown -->
                <div style="display: flex; gap: 1.25rem; flex-wrap: wrap; align-items: center;">
                    <div style="text-align: center;">
                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Hadir</span>
                        <span style="font-size: 1.15rem; font-weight: 800; color: var(--success-color);">{{ $hadirCount }}</span>
                    </div>
                    <div style="text-align: center; border-left: 1.5px solid var(--border-color); padding-left: 1.25rem;">
                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Izin</span>
                        <span style="font-size: 1.15rem; font-weight: 800; color: var(--warning-color);">{{ $izinCount }}</span>
                    </div>
                    <div style="text-align: center; border-left: 1.5px solid var(--border-color); padding-left: 1.25rem;">
                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Alpa</span>
                        <span style="font-size: 1.15rem; font-weight: 800; color: var(--danger-color);">{{ $tidakHadirCount }}</span>
                    </div>
                    
                    <div style="text-align: center; border-left: 1.5px solid var(--border-color); padding-left: 1.25rem;">
                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Rasio Kehadiran</span>
                        @php
                            $barColor = 'var(--danger-color)';
                            if ($persentase >= 75) {
                                $barColor = 'var(--success-color)';
                            } elseif ($persentase >= 50) {
                                $barColor = 'var(--warning-color)';
                            }
                        @endphp
                        <span style="font-size: 1.25rem; font-weight: 900; color: {{ $barColor }};">{{ $persentase }}%</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 style="font-weight: 800; margin: 0 0 1.25rem 0; font-size: 1rem; color: var(--text-primary); font-family: 'Georgia', serif; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph ph-check-square" style="color: var(--accent-color); font-size: 1.25rem;"></i> 
                    Riwayat Kehadiran Presensi
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem;">
                    @forelse($attendances as $session)
                    <div class="card" style="padding: 1.25rem; text-align: center; display: flex; flex-direction: column; justify-content: space-between; margin-bottom: 0; height: 100%; border-radius: 12px;">
                        <div>
                            <div style="font-size: 0.725rem; color: var(--text-secondary); font-weight: 700; margin-bottom: 0.5rem;">{{ date('d M Y', strtotime($session->date)) }}</div>
                            <h4 style="font-weight: 800; font-size: 0.9rem; color: var(--text-primary); margin: 0 0 1rem 0; font-family: 'Georgia', serif; min-height: 2.25rem; display: flex; align-items: center; justify-content: center;">{{ $session->title }}</h4>
                            
                            @php
                                $detail = $session->details->first();
                                $statusColor = 'var(--text-secondary)';
                                $statusBg = 'var(--bg-color)';
                                $statusLabel = 'Belum Diabsen';
                                $statusIcon = 'ph-question';
                                $statusBorder = 'var(--border-color)';

                                if($detail) {
                                    $status = strtolower($detail->status);
                                    if($status === 'hadir') {
                                        $statusColor = 'var(--success-color)';
                                        $statusBg = '#f0fdf4';
                                        $statusLabel = 'Hadir';
                                        $statusIcon = 'ph-check-circle';
                                        $statusBorder = '#bbf7d0';
                                    } elseif($status === 'izin') {
                                        $statusColor = 'var(--warning-color)';
                                        $statusBg = '#fffbeb';
                                        $statusLabel = 'Izin';
                                        $statusIcon = 'ph-info';
                                        $statusBorder = '#fef3c7';
                                    } elseif($status === 'sakit') {
                                        $statusColor = 'var(--warning-color)';
                                        $statusBg = '#fffbeb';
                                        $statusLabel = 'Sakit';
                                        $statusIcon = 'ph-first-aid';
                                        $statusBorder = '#fef3c7';
                                    } elseif(in_array($status, ['alpha', 'alpa', 'tidak hadir'])) {
                                        $statusColor = 'var(--danger-color)';
                                        $statusBg = '#fef2f2';
                                        $statusLabel = 'Alpa / Tidak Hadir';
                                        $statusIcon = 'ph-x-circle';
                                        $statusBorder = '#fecaca';
                                    }
                                }
                            @endphp

                            <div style="background: {{ $statusBg }}; color: {{ $statusColor }}; border: 1px solid {{ $statusBorder }}; padding: 0.45rem; border-radius: 8px; font-weight: 800; font-size: 0.775rem; display: inline-flex; align-items: center; gap: 0.25rem; justify-content: center; width: 100%;">
                                <i class="ph-fill {{ $statusIcon }}"></i> {{ $statusLabel }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; border-radius: 12px;" class="card">
                        <i class="ph ph-calendar-blank" style="font-size: 3rem; opacity: 0.2; color: var(--text-secondary);"></i>
                        <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Belum ada sesi presensi dibuka.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchClassroomTab(tabId) {
        // 1. Sembunyikan seluruh konten tab
        const contents = document.querySelectorAll('.classroom-tab-content');
        contents.forEach(content => {
            content.style.display = 'none';
        });

        // 2. Hapus kelas active dari seluruh button segmented pills
        const buttons = document.querySelectorAll('.classroom-tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });

        // 3. Tampilkan target konten tab yang sesuai dengan property layout aslinya
        const targetContent = document.getElementById('tab-content-' + tabId);
        if (targetContent) {
            if (tabId === 'stream') {
                targetContent.style.display = 'grid';
            } else {
                targetContent.style.display = 'flex';
            }
        }

        // 4. Tambahkan kelas active pada tombol yang diklik
        const targetButton = document.querySelector(`.classroom-tab-btn[data-tab="${tabId}"]`);
        if (targetButton) {
            targetButton.classList.add('active');
        }

        // 5. Perbarui URL di address bar browser secara dinamis tanpa reload halaman
        const newUrl = window.location.pathname + '?tab=' + tabId;
        window.history.replaceState({ tab: tabId }, '', newUrl);
    }
</script>
@endsection
