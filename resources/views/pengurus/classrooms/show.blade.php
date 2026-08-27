@extends('layouts.app')

@php
    $isJob = isset($job);
    $backUrl = route('pengurus.classrooms.index');
    $classroomTitle = $isJob ? $job->title : $performance->title;
    $classroomVenue = $isJob ? $job->location : $performance->venue;
    $classroomDate = $isJob ? $job->date : $performance->performance_date;
    
    $routePrefix = $isJob ? 'pengurus.jobs.classroom' : 'pengurus.programs.performance.classroom';
    $routeParams = $isJob ? [$job->id] : [$programId, $performance->id];
@endphp

@section('title', 'Pusat Latihan - ' . $classroomTitle)
@section('header', 'Pusat Latihan')
@section('content')
<link rel="stylesheet" href="{{ asset('css/ukm.css') }}">
<style>
    @media (max-width: 768px) {
        .table {
            display: table !important;
            table-layout: fixed !important;
            width: 100% !important;
        }
        thead, tbody, tr {
            min-width: auto !important;
            display: table-row-group !important;
        }
        thead {
            display: table-header-group !important;
        }
        tr {
            display: table-row !important;
        }
        .table th, .table td {
            padding: 0.5rem 0.35rem !important;
            font-size: 0.75rem !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }
    }
</style>

@if(session('success'))
<div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:0.85rem 1.25rem;border-radius:8px;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
    <i class="ph ph-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div>
        <h3 style="font-size:1.2rem;font-weight:800;color:var(--text-primary);margin:0;">Pusat Latihan — {{ $classroomTitle }}</h3>
        <p style="margin:0.25rem 0 0;color:var(--text-secondary);font-size:0.875rem;"><i class="ph ph-map-pin"></i> {{ $classroomVenue }} &nbsp;|&nbsp; <i class="ph ph-calendar"></i> {{ date('d M Y', strtotime($classroomDate)) }}</p>
    </div>
</div>

<!-- Trainer Selection Card -->
<div class="card" style="padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; width: 100%; border: 1px solid var(--border-color); background: var(--card-bg); margin-bottom: 1.5rem; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <i class="ph ph-chalkboard-teacher" style="font-size: 1.75rem; color: var(--accent-color);"></i>
        <div>
            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Pelatih Terpilih</p>
            <p style="margin: 0; font-size: 0.9375rem; font-weight: 700; color: var(--text-primary);">
                {{ $classroom->trainer ? $classroom->trainer->name : 'Belum Ditentukan' }}
            </p>
        </div>
    </div>
    <div>
        <form action="{{ route($isJob ? 'pengurus.jobs.classroom.trainer' : 'pengurus.programs.performance.classroom.trainer', $routeParams) }}" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
            @csrf
            <select name="trainer_id" class="form-control" style="font-size: 0.8125rem; padding: 0.4rem 0.75rem; border-radius: 6px; border: 1px solid var(--border-color); min-width: 180px;" onchange="this.form.submit()">
                <option value="">-- Pilih Pelatih --</option>
                @foreach($allTrainers as $t)
                    <option value="{{ $t->id }}" {{ ($classroom->trainer_id == $t->id) ? 'selected' : '' }}>{{ $t->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<!-- Tab Navigation -->
<div style="display:flex;gap:0.25rem;border-bottom:2px solid var(--border-color);margin-bottom:1.5rem;overflow-x:auto;">
    @foreach([['members','ph-users-three','Peserta'],['materials','ph-folder-open','Materi'],['attendance','ph-check-square','Absensi'],['schedules','ph-calendar','Jadwal'],['songs','ph-music-notes','Target Lagu'],['announcements','ph-megaphone','Pengumuman']] as [$tab,$icon,$label])
    <button onclick="showTab('{{ $tab }}')" id="tab-{{ $tab }}" style="padding:0.6rem 1rem;border:none;background:none;font-weight:700;font-size:0.8125rem;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:0.35rem;border-bottom:2px solid transparent;margin-bottom:-2px;color:var(--text-secondary);transition:all 0.15s;">
        <i class="ph {{ $icon }}"></i> {{ $label }}
    </button>
    @endforeach
</div>

<!-- TAB: Peserta -->
<div id="panel-members" class="tab-panel" style="display:block;">
    <div class="grid-sidebar-layout" style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;align-items:start;">
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Peserta Pusat Latihan ({{ $classroom->members->count() }} orang)</h5>
            <table class="table" style="font-size:0.875rem;">
                <thead><tr><th class="hidden-mobile">#</th><th>Nama</th><th class="hidden-mobile">Klasifikasi Suara</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($classroom->members as $i => $m)
                    <tr>
                        <td class="hidden-mobile">{{ $i+1 }}</td>
                        <td style="font-weight:600;">{{ $m->name }}</td>
                        <td class="hidden-mobile">{{ $m->voiceClassification->name ?? '-' }}</td>
                        <td><span class="badge" style="background:rgba(16,185,129,0.1);color:var(--success-color);font-weight:700;">{{ $m->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada peserta.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Atur Peserta</h5>
            <form action="{{ route($routePrefix . '.members', $routeParams) }}" method="POST">
                @csrf
                <div style="margin-bottom: 0.75rem;">
                    <input type="text" id="memberSearch" placeholder="Cari nama atau klasifikasi..." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.85rem;" onkeyup="filterMembers()">
                </div>
                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem; justify-content: flex-end;">
                    <button type="button" onclick="selectAllMembers(true)" style="background: none; border: none; color: var(--accent-color); font-size: 0.75rem; font-weight: 700; cursor: pointer; padding: 0;">Pilih Semua</button>
                    <span style="color: var(--text-muted); font-size: 0.75rem;">|</span>
                    <button type="button" onclick="selectAllMembers(false)" style="background: none; border: none; color: var(--text-muted); font-size: 0.75rem; font-weight: 700; cursor: pointer; padding: 0;">Kosongkan</button>
                </div>
                <div style="max-height:240px;overflow-y:auto;border:1px solid var(--border-color);border-radius:8px;padding:0.75rem;margin-bottom:1rem;">
                    @foreach($allMembers as $m)
                    <label class="member-checkbox-label" style="display:flex;align-items:center;gap:0.5rem;padding:0.4rem 0;cursor:pointer;font-size:0.875rem;">
                        <input type="checkbox" name="member_ids[]" value="{{ $m->id }}" {{ in_array($m->id, $classroomMemberIds) ? 'checked' : '' }}>
                        <span>{{ $m->name }}</span>
                        <span style="font-size:0.75rem;color:var(--text-muted);">— {{ $m->voiceClassification->name ?? '?' }}</span>
                    </label>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.65rem; color: #00ff2fff; font-weight:700;border-radius:8px;;">Simpan Peserta</button>
            </form>
        </div>
    </div>
</div>

<!-- TAB: Materi -->
<div id="panel-materials" class="tab-panel" style="display:none;">
    <div class="{{ false ? '' : 'grid-sidebar-layout' }}" style="display:grid;grid-template-columns:{{ false ? '1fr' : '1fr 360px' }};gap:1.5rem;align-items:start;">
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Materi Pusat Latihan ({{ $classroom->materials->count() }})</h5>
            <table class="table" style="font-size:0.875rem;">
                <thead><tr><th>Judul</th><th class="hidden-mobile">Jenis</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($classroom->materials as $mat)
                    <tr>
                        <td style="font-weight:600;">{{ $mat->title }}</td>
                        <td class="hidden-mobile"><span class="badge">{{ $mat->type }}</span></td>
                        <td>
                            <div style="display:flex;gap:0.35rem;">
                                <a href="{{ route('pengurus.materials.download', $mat->id) }}" class="btn" style="padding:0.3rem 0.6rem;font-size:0.75rem;background:var(--accent-light);color:var(--accent-color);border-radius:6px;text-decoration:none;font-weight:700;"><i class="ph ph-download-simple"></i></a>
                                <form action="{{ route($routePrefix . '.materials.remove', array_merge($routeParams, [$mat->id])) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus dari Pusat Latihan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:0.3rem 0.6rem;font-size:0.75rem;border-radius:6px;"><i class="ph ph-x"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada materi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(!false)
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 0.5rem;font-size:1rem;">Tambah dari Materi Master</h5>
            <p style="font-size:0.8rem;color:var(--text-secondary);line-height:1.5;margin:0 0 1.25rem 0;">Pilih berkas dari repositori Materi Master atau unggah berkas baru langsung ke dalam perpustakaan materi.</p>
            <button type="button" onclick="openMaterialsModal()" class="btn btn-primary" style="width:100%;padding:0.65rem;font-weight:700;border-radius:8px;text-align:center;display:block;cursor:pointer;"><i class="ph ph-folder-open"></i> Buka Materi Master</button>
        </div>
        @endif
    </div>
</div>

<!-- TAB: Absensi -->
<div id="panel-attendance" class="tab-panel" style="display:none;">
    <div class="{{ false ? '' : 'grid-sidebar-layout' }}" style="display:grid;grid-template-columns:{{ false ? '1fr' : '1fr 340px' }};gap:1.5rem;align-items:start;">
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Sesi Absensi</h5>
            <table class="table" style="font-size:0.875rem;">
                <thead><tr><th>Judul</th><th class="hidden-mobile">Jenis</th><th class="hidden-mobile">Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($classroom->attendances as $att)
                    <tr>
                        <td style="font-weight:600;">{{ $att->title }}</td>
                        <td class="hidden-mobile"><span class="badge">{{ $att->type }}</span></td>
                        <td class="hidden-mobile">{{ date('d M Y', strtotime($att->date)) }}</td>
                        <td>
                            <div style="display:flex;gap:0.35rem;align-items:center;">
                                <a href="{{ route($routePrefix . '.attendance', array_merge($routeParams, [$att->id])) }}" class="btn" style="padding:0.3rem 0.6rem;font-size:0.75rem;font-weight:700;background:var(--accent-light);color:var(--accent-color);border-radius:6px;text-decoration:none;"><i class="ph ph-list-checks"></i> Isi</a>
                                @if(!false)
                                <button type="button" onclick="editAttendance({{ $att->id }}, {{ json_encode($att->title) }}, {{ json_encode($att->type) }}, {{ json_encode($att->date) }})" class="btn" style="padding:0.3rem 0.6rem;font-size:0.75rem;border-radius:6px;background:var(--bg-color);border:1px solid var(--border-color);"><i class="ph ph-pencil-simple"></i></button>
                                <form action="{{ route($routePrefix . '.attendance.destroy', array_merge($routeParams, [$att->id])) }}" method="POST" onsubmit="return confirm('Hapus sesi absensi ini beserta semua data kehadirannya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:0.3rem 0.6rem;font-size:0.75rem;border-radius:6px;"><i class="ph ph-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada sesi absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(!false)
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Buat Sesi Absensi</h5>
            <form action="{{ route($routePrefix . '.attendance.store', $routeParams) }}" method="POST">
                @csrf
                
                <!-- Sesi Dropdown (Pilih dari Jadwal) -->
                <div style="margin-bottom:1rem;" id="titleSelectContainer">
                    <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Judul Sesi</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <select name="title" id="attendanceTitleSelect" class="form-control" required style="flex: 1;">
                            <option value="">-- Pilih dari Jadwal --</option>
                            @foreach($classroom->schedules as $sch)
                                <option value="{{ $sch->title }}" data-date="{{ $sch->date }}">{{ $sch->title }} ({{ date('d-m-Y', strtotime($sch->date)) }})</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-secondary" onclick="toggleTitleInput(true)" style="padding: 0.5rem; display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; flex-shrink: 0;" title="Ketik Manual"><i class="ph ph-keyboard"></i></button>
                    </div>
                </div>

                <!-- Sesi Input Manual (Tersembunyi secara default) -->
                <div style="margin-bottom:1rem; display: none;" id="titleInputContainer">
                    <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Judul Sesi</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" id="attendanceTitleInput" class="form-control" placeholder="Latihan #1" style="flex: 1;">
                        <button type="button" class="btn btn-secondary" onclick="toggleTitleInput(false)" style="padding: 0.5rem; display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; flex-shrink: 0;" title="Pilih dari Jadwal"><i class="ph ph-list-bullets"></i></button>
                    </div>
                </div>

                <div style="margin-bottom:1rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Jenis</label>
                    <select name="type" class="form-control" required>
                        @foreach(['Latihan','Gladi Resik','Penampilan'] as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1.25rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Tanggal</label><input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.65rem;font-weight:700;border-radius:8px;"><i class="ph ph-plus"></i> Buat Sesi</button>
            </form>
        </div>
        @endif
    </div>

    <!-- Recap Section -->
    <div class="card" style="padding:1.5rem; margin-top: 1.5rem; width: 100%;">
        <h5 style="font-weight:800; margin:0 0 1rem; font-size:1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-chart-bar" style="color: var(--accent-color);"></i> Rekapitulasi Absensi Anggota
        </h5>
        
        <div style="overflow-x:auto;">
            <table class="table" style="font-size:0.875rem; width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color);">
                        <th style="text-align: left; padding: 0.75rem 0.5rem;">Nama Anggota</th>
                        <th class="hidden-mobile" style="text-align: left; padding: 0.75rem 0.5rem;">Klasifikasi Suara</th>
                        <th class="hidden-mobile" style="text-align: center; padding: 0.75rem 0.5rem;">Total Sesi</th>
                        <th style="text-align: center; padding: 0.75rem 0.5rem; color: var(--success-color);">Hadir</th>
                        <th class="hidden-mobile" style="text-align: center; padding: 0.75rem 0.5rem; color: #3b82f6;">Izin</th>
                        <th class="hidden-mobile" style="text-align: center; padding: 0.75rem 0.5rem; color: #f59e0b;">Sakit</th>
                        <th class="hidden-mobile" style="text-align: center; padding: 0.75rem 0.5rem; color: var(--danger-color);">Alpha</th>
                        <th style="text-align: center; padding: 0.75rem 0.5rem; font-weight: bold;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classroom->members as $member)
                        @php
                            $total = $classroom->attendances->count();
                            $hadir = 0;
                            $izin = 0;
                            $sakit = 0;
                            $alpha = 0;
                            
                            foreach($classroom->attendances as $att) {
                                $detail = $att->details->where('member_id', $member->id)->first();
                                if ($detail) {
                                    $status = strtolower($detail->status);
                                    if ($status === 'hadir') {
                                        $hadir++;
                                    } elseif ($status === 'izin') {
                                        $izin++;
                                    } elseif ($status === 'sakit') {
                                        $sakit++;
                                    } else {
                                        $alpha++;
                                    }
                                } else {
                                    $alpha++;
                                }
                            }
                            
                            $percentage = $total > 0 ? ($hadir / $total) * 100 : 0;
                        @endphp
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="font-weight:600; padding: 0.75rem 0.5rem;">{{ $member->name }}</td>
                            <td class="hidden-mobile" style="padding: 0.75rem 0.5rem;">{{ $member->voiceClassification->name ?? '-' }}</td>
                            <td class="hidden-mobile" style="text-align: center; padding: 0.75rem 0.5rem;">{{ $total }}</td>
                            <td style="text-align: center; padding: 0.75rem 0.5rem; font-weight: 600; color: var(--success-color);">{{ $hadir }}</td>
                            <td class="hidden-mobile" style="text-align: center; padding: 0.75rem 0.5rem; font-weight: 600; color: #3b82f6;">{{ $izin }}</td>
                            <td class="hidden-mobile" style="text-align: center; padding: 0.75rem 0.5rem; font-weight: 600; color: #f59e0b;">{{ $sakit }}</td>
                            <td class="hidden-mobile" style="text-align: center; padding: 0.75rem 0.5rem; font-weight: 600; color: var(--danger-color);">{{ $alpha }}</td>
                            <td style="text-align: center; padding: 0.75rem 0.5rem; font-weight: bold;">
                                <span class="badge" style="background: {{ $percentage >= 75 ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }}; color: {{ $percentage >= 75 ? 'var(--success-color)' : 'var(--danger-color)' }};">
                                    {{ round($percentage, 1) }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada data rekapitulasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- TAB: Jadwal -->
<div id="panel-schedules" class="tab-panel" style="display:none;">
    <div class="{{ false ? '' : 'grid-sidebar-layout' }}" style="display:grid;grid-template-columns:{{ false ? '1fr' : '1fr 340px' }};gap:1.5rem;align-items:start;">
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Jadwal Latihan</h5>
            <table class="table" style="font-size:0.875rem;">
                <thead><tr><th>Kegiatan</th><th>Tanggal</th><th class="hidden-mobile">Waktu</th><th class="hidden-mobile">Lokasi</th><th></th></tr></thead>
                <tbody>
                    @forelse($classroom->schedules->sortBy('date') as $sch)
                    <tr>
                        <td style="font-weight:600;">{{ $sch->title }}</td>
                        <td>{{ date('d M Y', strtotime($sch->date)) }}</td>
                        <td class="hidden-mobile">{{ substr($sch->start_time,0,5) }}{{ $sch->end_time ? ' – '.substr($sch->end_time,0,5) : '' }}</td>
                        <td class="hidden-mobile">{{ $sch->location ?? '-' }}</td>
                        <td>
                            <div style="display:flex;gap:0.35rem;">
                                @if(!false)
                                <button type="button" onclick="editSchedule({{ $sch->id }}, {{ json_encode($sch->title) }}, {{ json_encode($sch->date) }}, {{ json_encode(substr($sch->start_time,0,5)) }}, {{ json_encode($sch->end_time ? substr($sch->end_time,0,5) : '') }}, {{ json_encode($sch->location) }})" class="btn" style="padding:0.3rem 0.6rem;font-size:0.75rem;border-radius:6px;background:var(--bg-color);border:1px solid var(--border-color);"><i class="ph ph-pencil-simple"></i></button>
                                <form action="{{ route($routePrefix . '.schedules.destroy', array_merge($routeParams, [$sch->id])) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:0.3rem 0.6rem;font-size:0.75rem;border-radius:6px;"><i class="ph ph-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada jadwal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(!false)
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Tambah Jadwal</h5>
            <form action="{{ route($routePrefix . '.schedules.store', $routeParams) }}" method="POST">
                @csrf
                <div style="margin-bottom:0.85rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Nama Kegiatan</label><input type="text" name="title" class="form-control" required placeholder="Latihan vokal..."></div>
                <div style="margin-bottom:0.85rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Tanggal</label><input type="date" name="date" class="form-control" required></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-bottom:0.85rem;">
                    <div><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Mulai</label><input type="time" name="start_time" class="form-control" required></div>
                    <div><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Selesai</label><input type="time" name="end_time" class="form-control"></div>
                </div>
                <div style="margin-bottom:1.25rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Lokasi</label><input type="text" name="location" class="form-control" placeholder="Ruang latihan..."></div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.65rem;font-weight:700;border-radius:8px;"><i class="ph ph-plus"></i> Tambah Jadwal</button>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- TAB: Target Lagu -->
<div id="panel-songs" class="tab-panel" style="display:none;">
    <div class="{{ false ? '' : 'grid-sidebar-layout' }}" style="display:grid;grid-template-columns:{{ false ? '1fr' : '1fr 360px' }};gap:1.5rem;align-items:start;">
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Target Lagu</h5>
            <table class="table" style="font-size:0.875rem;">
                <thead><tr><th>Judul Lagu</th><th class="hidden-mobile">Komposer</th><th class="hidden-mobile">Part</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($classroom->songTargets as $song)
                    <tr>
                        <td style="font-weight:600;">{{ $song->song_title }}</td>
                        <td class="hidden-mobile" style="color:var(--text-secondary);">{{ $song->composer ?? '-' }}</td>
                        <td class="hidden-mobile">{{ $song->voice_part ?? 'Full Choir' }}</td>
                        <td>
                            <form action="{{ route($routePrefix . '.songs.update', array_merge($routeParams, [$song->id])) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <select name="status" class="form-control" onchange="this.form.submit()" style="padding:0.3rem 0.5rem;font-size:0.75rem;font-weight:700;height:auto;border-radius:6px;">
                                    @foreach(['Belajar','Hafal','Siap Tampil'] as $s)
                                    <option value="{{ $s }}" {{ $song->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route($routePrefix . '.songs.destroy', array_merge($routeParams, [$song->id])) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:0.3rem 0.6rem;font-size:0.75rem;border-radius:6px;"><i class="ph ph-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada target lagu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(!false)
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Tambah Target Lagu</h5>
            <form action="{{ route($routePrefix . '.songs.store', $routeParams) }}" method="POST">
                @csrf
                <div style="margin-bottom:0.85rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Judul Lagu <span style="color:red">*</span></label><input type="text" name="song_title" class="form-control" required></div>
                <div style="margin-bottom:0.85rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Komposer</label><input type="text" name="composer" class="form-control"></div>
                <div style="margin-bottom:0.85rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Part Suara</label>
                    <select name="voice_part" class="form-control">
                        <option value="">Full Choir</option>
                        @foreach(['Sopran','Alto','Tenor','Bass'] as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1.25rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Status Awal</label>
                    <select name="status" class="form-control"><option>Belajar</option><option>Hafal</option><option>Siap Tampil</option></select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.65rem;font-weight:700;border-radius:8px;"><i class="ph ph-plus"></i> Tambah Lagu</button>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- TAB: Pengumuman -->
<div id="panel-announcements" class="tab-panel" style="display:none;">
    <div class="{{ false ? '' : 'grid-sidebar-layout' }}" style="display:grid;grid-template-columns:{{ false ? '1fr' : '1fr 360px' }};gap:1.5rem;align-items:start;">
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Pengumuman Classroom</h5>
            @forelse($classroom->announcements->sortByDesc('created_at') as $ann)
            <div style="border:1px solid var(--border-color);border-radius:10px;padding:1rem;margin-bottom:0.85rem;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <p style="font-weight:700;margin:0 0 0.35rem;color:var(--text-primary);">{{ $ann->title }}</p>
                        <p style="font-size:0.8125rem;color:var(--text-secondary);margin:0 0 0.5rem;">{{ $ann->creator->name ?? 'Admin' }} — {{ date('d M Y H:i', strtotime($ann->created_at)) }}</p>
                        <p style="font-size:0.875rem;color:var(--text-primary);margin:0;line-height:1.6;">{{ $ann->content }}</p>
                    </div>
                    <form action="{{ route($routePrefix . '.announcements.destroy', array_merge($routeParams, [$ann->id])) }}" method="POST" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:var(--danger-color);cursor:pointer;font-size:1rem;"><i class="ph ph-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
            <p style="text-align:center;color:var(--text-muted);padding:2rem 0;">Belum ada pengumuman.</p>
            @endforelse
        </div>
        @if(!false)
        <div class="card" style="padding:1.25rem;">
            <h5 style="font-weight:800;margin:0 0 1rem;font-size:1rem;">Buat Pengumuman</h5>
            <form action="{{ route($routePrefix . '.announcements.store', $routeParams) }}" method="POST">
                @csrf
                <div style="margin-bottom:0.85rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Judul</label><input type="text" name="title" class="form-control" required></div>
                <div style="margin-bottom:1.25rem;"><label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;">Isi Pengumuman</label><textarea name="content" class="form-control" rows="5" required></textarea></div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.65rem;font-weight:700;border-radius:8px;"><i class="ph ph-megaphone"></i> Kirim Pengumuman</button>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
function showTab(name) {
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('[id^="tab-"]').forEach(t => {
        t.style.borderBottomColor = 'transparent';
        t.style.color = 'var(--text-secondary)';
    });
    document.getElementById('panel-' + name).style.display = 'block';
    const btn = document.getElementById('tab-' + name);
    btn.style.borderBottomColor = 'var(--accent-color)';
    btn.style.color = 'var(--accent-color)';
}
showTab('members');

function filterMembers() {
    const query = document.getElementById('memberSearch').value.toLowerCase();
    document.querySelectorAll('.member-checkbox-label').forEach(label => {
        const text = label.textContent.toLowerCase();
        if (text.includes(query)) {
            label.style.display = 'flex';
        } else {
            label.style.display = 'none';
        }
    });
}

function selectAllMembers(checked) {
    document.querySelectorAll('.member-checkbox-label input[type="checkbox"]').forEach(checkbox => {
        const label = checkbox.closest('.member-checkbox-label');
        if (label.style.display !== 'none') {
            checkbox.checked = checked;
        }
    });
}

function toggleTitleInput(manual) {
    const selectContainer = document.getElementById('titleSelectContainer');
    const inputContainer = document.getElementById('titleInputContainer');
    const selectEl = document.getElementById('attendanceTitleSelect');
    const inputEl = document.getElementById('attendanceTitleInput');
    
    if (manual) {
        selectContainer.style.display = 'none';
        selectEl.removeAttribute('name');
        selectEl.removeAttribute('required');
        
        inputContainer.style.display = 'block';
        inputEl.setAttribute('name', 'title');
        inputEl.setAttribute('required', 'required');
        inputEl.focus();
    } else {
        selectContainer.style.display = 'block';
        selectEl.setAttribute('name', 'title');
        selectEl.setAttribute('required', 'required');
        
        inputContainer.style.display = 'none';
        inputEl.removeAttribute('name');
        inputEl.removeAttribute('required');
    }
}

function toggleEditTitleInput(manual) {
    const selectContainer = document.getElementById('editTitleSelectContainer');
    const inputContainer = document.getElementById('editTitleInputContainer');
    const selectEl = document.getElementById('editAttendanceTitleSelect');
    const inputEl = document.getElementById('editAttendanceTitleInput');
    
    if (manual) {
        selectContainer.style.display = 'none';
        selectEl.removeAttribute('name');
        selectEl.removeAttribute('required');
        
        inputContainer.style.display = 'block';
        inputEl.setAttribute('name', 'title');
        inputEl.setAttribute('required', 'required');
        inputEl.focus();
    } else {
        selectContainer.style.display = 'block';
        selectEl.setAttribute('name', 'title');
        selectEl.setAttribute('required', 'required');
        
        inputContainer.style.display = 'none';
        inputEl.removeAttribute('name');
        inputEl.removeAttribute('required');
    }
}

function editAttendance(id, title, type, date) {
    const modal = document.getElementById('editAttendanceModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    let selectEl = document.getElementById('editAttendanceTitleSelect');
    let optionExists = Array.from(selectEl.options).some(opt => opt.value === title);
    
    if (optionExists && title !== "") {
        toggleEditTitleInput(false);
        selectEl.value = title;
    } else {
        toggleEditTitleInput(true);
        document.getElementById('editAttendanceTitleInput').value = title;
    }
    document.getElementById('editAttendanceType').value = type;
    document.getElementById('editAttendanceDate').value = date;
    
    // Set form action dynamically
    const baseUrl = "{{ route($routePrefix . '.attendance.update', array_merge($routeParams, [999999999])) }}".replace('999999999', id);
    document.getElementById('editAttendanceForm').action = baseUrl;
}

function cancelEditAttendance() {
    const modal = document.getElementById('editAttendanceModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

function editSchedule(id, title, date, start, end, location) {
    const modal = document.getElementById('editScheduleModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    document.getElementById('editScheduleTitle').value = title;
    document.getElementById('editScheduleDate').value = date;
    document.getElementById('editScheduleStart').value = start;
    document.getElementById('editScheduleEnd').value = end;
    document.getElementById('editScheduleLocation').value = location;
    
    const baseUrl = "{{ route($routePrefix . '.schedules.update', array_merge($routeParams, [999999999])) }}".replace('999999999', id);
    document.getElementById('editScheduleForm').action = baseUrl;
}

function cancelEditSchedule() {
    const modal = document.getElementById('editScheduleModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const selectEl = document.getElementById('attendanceTitleSelect');
    const dateInput = document.querySelector('input[name="date"]');
    
    if (selectEl) {
        selectEl.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const dateVal = selectedOption.getAttribute('data-date');
            if (dateVal) {
                dateInput.value = dateVal;
            }
        });
    }
    
    const editSelectEl = document.getElementById('editAttendanceTitleSelect');
    const editDateInput = document.getElementById('editAttendanceDate');
    if (editSelectEl) {
        editSelectEl.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const dateVal = selectedOption.getAttribute('data-date');
            if (dateVal) {
                editDateInput.value = dateVal;
            }
        });
    }
    
    @if($classroom->schedules->isEmpty())
        toggleTitleInput(true);
        const backBtn = document.querySelector('#titleInputContainer button');
        if (backBtn) backBtn.style.display = 'none';
    @endif
});

function openMaterialsModal() {
    const modal = document.getElementById('materialsModal');
    const iframe = document.getElementById('materialsIframe');
    iframe.src = "{!! route('pengurus.materials.index', ['classroom_id' => $classroom->id, 'iframe' => 1]) !!}";
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeMaterialsModal() {
    const modal = document.getElementById('materialsModal');
    const iframe = document.getElementById('materialsIframe');
    iframe.src = "";
    modal.style.display = 'none';
    document.body.style.overflow = '';
    window.location.reload();
}
</script>

<!-- Modal Floating Overlay Materi Master -->
<div id="materialsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1.5rem;">
    <div class="card" style="width: 100%; max-width: 1100px; height: 90vh; display: flex; flex-direction: column; padding: 0; overflow: hidden; position: relative; border: 1px solid var(--border-color); box-shadow: var(--shadow-lg); background: var(--surface-color);">
        <!-- Modal Header -->
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; background: var(--surface-color);">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(59, 130, 246, 0.1); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="ph-fill ph-folders"></i>
                </div>
                <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; color: var(--text-primary);">Pilih Materi Master</h4>
            </div>
            <button type="button" onclick="closeMaterialsModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary); display: flex; align-items: center; justify-content: center; padding: 0.25rem;"><i class="ph ph-x"></i></button>
        </div>
        
        <!-- Modal Body (Iframe) -->
        <div style="flex: 1; position: relative; background: var(--surface-color);">
            <iframe id="materialsIframe" style="width: 100%; height: 100%; border: none;" src=""></iframe>
        </div>
    </div>
</div>
<!-- Modal Edit Attendance -->
<div id="editAttendanceModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1.5rem;">
    <div class="card" style="width: 100%; max-width: 450px; padding: 1.5rem; position: relative; border: 1px solid var(--border-color); box-shadow: var(--shadow-lg); background: var(--surface-color); border-radius: 12px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
            <h5 style="font-weight:800;margin:0;font-size:1.1rem;color:var(--text-primary);display:flex;align-items:center;gap:0.5rem;"><i class="ph ph-pencil-simple" style="color:var(--accent-color);"></i> Edit Sesi Absensi</h5>
            <button type="button" onclick="cancelEditAttendance()" style="background:none;border:none;cursor:pointer;font-size:1.25rem;color:var(--text-secondary);"><i class="ph ph-x"></i></button>
        </div>
        <form id="editAttendanceForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom:1rem;" id="editTitleSelectContainer">
                <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;color:var(--text-secondary);">Judul Sesi</label>
                <div style="display: flex; gap: 0.5rem;">
                    <select name="title" id="editAttendanceTitleSelect" class="form-control" style="flex: 1; padding:0.6rem;">
                        <option value="">-- Pilih dari Jadwal --</option>
                        @foreach($classroom->schedules as $sch)
                            <option value="{{ $sch->title }}" data-date="{{ $sch->date }}">{{ $sch->title }} ({{ date('d-m-Y', strtotime($sch->date)) }})</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-secondary" onclick="toggleEditTitleInput(true)" style="padding: 0.5rem; display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; flex-shrink: 0;" title="Ketik Manual"><i class="ph ph-keyboard"></i></button>
                </div>
            </div>

            <div style="margin-bottom:1rem; display: none;" id="editTitleInputContainer">
                <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;color:var(--text-secondary);">Judul Sesi</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" id="editAttendanceTitleInput" class="form-control" placeholder="Latihan #1" style="flex: 1; padding:0.6rem;">
                    <button type="button" class="btn btn-secondary" onclick="toggleEditTitleInput(false)" style="padding: 0.5rem; display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; flex-shrink: 0;" title="Pilih dari Jadwal"><i class="ph ph-list-bullets"></i></button>
                </div>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;color:var(--text-secondary);">Jenis</label>
                <select name="type" id="editAttendanceType" class="form-control" required style="padding:0.6rem;">
                    @foreach(['Latihan','Gladi Resik','Penampilan'] as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;color:var(--text-secondary);">Tanggal</label>
                <input type="date" name="date" id="editAttendanceDate" class="form-control" required style="padding:0.6rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:0.75rem;font-weight:700;border-radius:8px; color: green"><i class="ph ph-floppy-disk"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>

<!-- Modal Edit Schedule -->
<div id="editScheduleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1.5rem;">
    <div class="card" style="width: 100%; max-width: 450px; padding: 1.5rem; position: relative; border: 1px solid var(--border-color); box-shadow: var(--shadow-lg); background: var(--surface-color); border-radius: 12px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
            <h5 style="font-weight:800;margin:0;font-size:1.1rem;color:var(--text-primary);display:flex;align-items:center;gap:0.5rem;"><i class="ph ph-pencil-simple" style="color:var(--accent-color);"></i> Edit Jadwal</h5>
            <button type="button" onclick="cancelEditSchedule()" style="background:none;border:none;cursor:pointer;font-size:1.25rem;color:var(--text-secondary);"><i class="ph ph-x"></i></button>
        </div>
        <form id="editScheduleForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom:1rem;">
                <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;color:var(--text-secondary);">Nama Kegiatan</label>
                <input type="text" name="title" id="editScheduleTitle" class="form-control" required style="padding:0.6rem;">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;color:var(--text-secondary);">Tanggal</label>
                <input type="date" name="date" id="editScheduleDate" class="form-control" required style="padding:0.6rem;">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:1rem;">
                <div>
                    <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;color:var(--text-secondary);">Mulai</label>
                    <input type="time" name="start_time" id="editScheduleStart" class="form-control" required style="padding:0.6rem;">
                </div>
                <div>
                    <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;color:var(--text-secondary);">Selesai</label>
                    <input type="time" name="end_time" id="editScheduleEnd" class="form-control" style="padding:0.6rem;">
                </div>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="font-weight:700;font-size:0.8125rem;display:block;margin-bottom:0.35rem;color:var(--text-secondary);">Lokasi</label>
                <input type="text" name="location" id="editScheduleLocation" class="form-control" style="padding:0.6rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:0.75rem;font-weight:700;border-radius:8px;"><i class="ph ph-floppy-disk"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
