@extends('layouts.app')
@section('title', 'Classroom')
@section('header', 'Classroom PSUP')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ukm.css') }}">

@if(session('success'))
<div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:0.85rem 1.25rem;border-radius:8px;font-weight:600;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
    <i class="ph ph-check-circle"></i> {{ session('success') }}
</div>
@endif

<div style="margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;">
    <div>
        <h3 style="font-size:1.25rem;font-weight:800;color:var(--text-primary);margin:0 0 0.25rem 0;">Daftar Classroom Aktif</h3>
        <p style="margin:0;color:var(--text-secondary);font-size:0.875rem;">Classroom dibuat otomatis dari Program Kerja bertipe <strong>Performance</strong>. Setiap penampilan memiliki classroom sendiri.</p>
    </div>
    <a href="{{ route('pengurus.programs.create') }}" class="btn btn-primary" style="padding:0.65rem 1.25rem;font-weight:700;border-radius:10px;display:inline-flex;align-items:center;gap:0.35rem;text-decoration:none;">
        <i class="ph ph-plus"></i> Buat Program Kerja Penampilan
    </a>
</div>

@if($classrooms->count() > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.25rem;margin-bottom:2rem;">
        @foreach($classrooms as $cls)
        @php
            $isJob = (bool) $cls->job_id;
            $perf = $cls->performance;
            $prog = $perf?->program;
            $job = $cls->job;
        @endphp
        <div class="card" style="padding:0;overflow:hidden;border:1px solid var(--border-color);transition:box-shadow 0.2s,transform 0.2s;" onmouseenter="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)';this.style.transform='translateY(-2px)';" onmouseleave="this.style.boxShadow='';this.style.transform='';">
            <!-- Header Gradient -->
            <div style="background:linear-gradient(135deg,#10b981,#059669);padding:1.25rem 1.5rem;position:relative;">
                <div style="position:absolute;top:0.75rem;right:0.75rem;">
                    <span style="background:rgba(255,255,255,0.2);color:#fff;font-size:0.7rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:999px;">
                        {{ $cls->status }}
                    </span>
                </div>
                <i class="ph ph-chalkboard" style="font-size:1.75rem;color:rgba(255,255,255,0.7);display:block;margin-bottom:0.5rem;"></i>
                <h4 style="margin:0 0 0.25rem;font-weight:800;font-size:1rem;color:#fff;">{{ $cls->name }}</h4>
                @if($isJob)
                <p style="margin:0;font-size:0.8125rem;color:rgba(255,255,255,0.8);">
                    <i class="ph ph-briefcase"></i> Job: {{ $job->title }}
                </p>
                @elseif($perf)
                <p style="margin:0;font-size:0.8125rem;color:rgba(255,255,255,0.8);">
                    <i class="ph ph-microphone-stage"></i> {{ $perf->title }}
                </p>
                @endif
            </div>

            <!-- Body -->
            <div style="padding:1.25rem 1.5rem;">
                @if($isJob)
                <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                    <i class="ph ph-tag"></i> Jenis: <strong style="color:var(--text-primary);">Penugasan Job / Delegasi</strong>
                </p>
                <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                    <i class="ph ph-calendar"></i> Tanggal: <strong style="color:var(--text-primary);">{{ date('d M Y', strtotime($job->date)) }}</strong>
                </p>
                <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                    <i class="ph ph-map-pin"></i> Lokasi: <strong style="color:var(--text-primary);">{{ $job->location }}</strong>
                </p>
                @else
                    @if($prog)
                    <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                        <i class="ph ph-folder"></i> Program: <strong style="color:var(--text-primary);">{{ $prog->name }}</strong>
                    </p>
                    @endif
                    @if($perf)
                    <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                        <i class="ph ph-calendar"></i> Tanggal: <strong style="color:var(--text-primary);">{{ date('d M Y', strtotime($perf->performance_date)) }}</strong>
                    </p>
                    <p style="margin:0 0 0.75rem;font-size:0.8125rem;color:var(--text-secondary);">
                        <i class="ph ph-map-pin"></i> Venue: <strong style="color:var(--text-primary);">{{ $perf->venue }}</strong>
                    </p>
                    @endif
                @endif

                <!-- Stats -->
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;margin-top:1rem;margin-bottom:1.25rem;">
                    <div style="background:var(--surface-color);border-radius:8px;padding:0.75rem;text-align:center;">
                        <span style="font-size:1.25rem;font-weight:800;color:var(--accent-color);display:block;">{{ $cls->members->count() }}</span>
                        <span style="font-size:0.7rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;">Peserta</span>
                    </div>
                    <div style="background:var(--surface-color);border-radius:8px;padding:0.75rem;text-align:center;">
                        <span style="font-size:1.25rem;font-weight:800;color:#f59e0b;display:block;">{{ $cls->attendances->count() }}</span>
                        <span style="font-size:0.7rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;">Sesi</span>
                    </div>
                    <div style="background:var(--surface-color);border-radius:8px;padding:0.75rem;text-align:center;">
                        <span style="font-size:1.25rem;font-weight:800;color:#10b981;display:block;">{{ $cls->songTargets()->count() }}</span>
                        <span style="font-size:0.7rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;">Lagu</span>
                    </div>
                </div>

                @if($isJob)
                <a href="{{ route('pengurus.jobs.classroom.show', $job->id) }}"
                   class="btn btn-primary"
                   style="width:100%;text-align:center;padding:0.65rem;font-weight:700;border-radius:8px;text-decoration:none;display:block;">
                    <i class="ph ph-arrow-square-in"></i> Masuk Classroom
                </a>
                @elseif($perf && $prog)
                <a href="{{ route('pengurus.programs.performance.classroom.show', [$prog->id, $perf->id]) }}"
                   class="btn btn-primary"
                   style="width:100%;text-align:center;padding:0.65rem;font-weight:700;border-radius:8px;text-decoration:none;display:block;">
                    <i class="ph ph-arrow-square-in"></i> Masuk Classroom
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="card" style="padding:3rem;text-align:center;">
        <i class="ph ph-chalkboard" style="font-size:4rem;color:var(--text-muted);display:block;margin-bottom:1rem;"></i>
        <h4 style="font-weight:800;color:var(--text-primary);margin:0 0 0.5rem;">Belum Ada Classroom</h4>
        <p style="color:var(--text-secondary);font-size:0.875rem;margin:0 0 1.5rem;max-width:420px;margin-left:auto;margin-right:auto;">
            Classroom dibuat otomatis saat kamu membuat <strong>Program Kerja dengan tipe Performance</strong>, lalu menambahkan data Penampilan pada program tersebut.
        </p>

        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-bottom:2rem;">
            <div style="background:var(--surface-color);border-radius:12px;padding:1.25rem;text-align:left;max-width:220px;border:1px solid var(--border-color);">
                <div style="width:36px;height:36px;background:rgba(99,102,241,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="ph ph-number-circle-one" style="font-size:1.25rem;color:#6366f1;"></i>
                </div>
                <p style="font-weight:700;font-size:0.875rem;margin:0 0 0.25rem;color:var(--text-primary);">Buat Program Kerja</p>
                <p style="font-size:0.8rem;color:var(--text-secondary);margin:0;">Pilih jenis <strong>Performance</strong> saat membuat proker</p>
            </div>
            <div style="background:var(--surface-color);border-radius:12px;padding:1.25rem;text-align:left;max-width:220px;border:1px solid var(--border-color);">
                <div style="width:36px;height:36px;background:rgba(16,185,129,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="ph ph-number-circle-two" style="font-size:1.25rem;color:#10b981;"></i>
                </div>
                <p style="font-weight:700;font-size:0.875rem;margin:0 0 0.25rem;color:var(--text-primary);">Isi Data Penampilan</p>
                <p style="font-size:0.8rem;color:var(--text-secondary);margin:0;">Di halaman detail proker, isi form data penampilan</p>
            </div>
            <div style="background:var(--surface-color);border-radius:12px;padding:1.25rem;text-align:left;max-width:220px;border:1px solid var(--border-color);">
                <div style="width:36px;height:36px;background:rgba(245,158,11,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                    <i class="ph ph-number-circle-three" style="font-size:1.25rem;color:#f59e0b;"></i>
                </div>
                <p style="font-weight:700;font-size:0.875rem;margin:0 0 0.25rem;color:var(--text-primary);">Buka Classroom</p>
                <p style="font-size:0.8rem;color:var(--text-secondary);margin:0;">Classroom otomatis terbentuk — kelola peserta, materi, absensi</p>
            </div>
        </div>

        <a href="{{ route('pengurus.programs.create') }}" class="btn btn-primary" style="padding:0.75rem 2rem;font-weight:700;border-radius:10px;display:inline-flex;align-items:center;gap:0.35rem;text-decoration:none;font-size:0.9375rem;">
            <i class="ph ph-plus"></i> Buat Program Kerja Penampilan Sekarang
        </a>
    </div>
@endif

@if($performances->count() > 0)
<!-- Performance Programs yang belum punya classroom -->
@php $withoutClassroom = $performances->filter(fn($p) => !$p->classroom); @endphp
@if($withoutClassroom->count() > 0)
<div class="card" style="padding:1.5rem;margin-top:1.5rem;border:2px dashed var(--border-color);">
    <h5 style="font-weight:800;margin:0 0 1rem;color:var(--text-primary);display:flex;align-items:center;gap:0.5rem;">
        <i class="ph ph-warning" style="color:#f59e0b;"></i> Penampilan Belum Memiliki Classroom
    </h5>
    <p style="font-size:0.875rem;color:var(--text-secondary);margin:0 0 1rem;">Buka halaman penampilan berikut untuk mengakses classroomnya (akan dibuat otomatis):</p>
    @foreach($withoutClassroom as $perf)
    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.75rem 1rem;background:var(--surface-color);border-radius:8px;margin-bottom:0.5rem;">
        <div>
            <strong>{{ $perf->title }}</strong>
            <span style="font-size:0.8125rem;color:var(--text-secondary);display:block;">{{ $perf->program->name ?? '' }} — {{ date('d M Y', strtotime($perf->performance_date)) }}</span>
        </div>
        <a href="{{ route('pengurus.programs.performance.classroom.show', [$perf->program->id, $perf->id]) }}" class="btn btn-primary" style="padding:0.4rem 1rem;font-size:0.8125rem;font-weight:700;border-radius:7px;text-decoration:none;">
            <i class="ph ph-arrow-right"></i> Buka
        </a>
    </div>
    @endforeach
</div>
@endif
@endif

@endsection
