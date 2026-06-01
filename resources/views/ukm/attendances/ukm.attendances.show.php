@extends('layouts.app')

@section('title', 'Absensi Anggota: ' . $event->title)
@section('header', 'Pertemuan Absensi Anggota')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; border-top: 3px solid #10b981;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div class="animate-fade-in" style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem; align-items: start;">
    <!-- LEFT SIDEBAR COLUMN: Agenda Profile & Controls -->
    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div class="card" style="margin-bottom: 0; padding: 1.5rem;">
            <div style="width: 44px; height: 44px; border-radius: 8px; background: var(--accent-light); color: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
                <i class="ph ph-calendar-check"></i>
            </div>
            
            <h3 style="margin: 0; font-weight: 800; font-size: 1.15rem; color: var(--text-primary); line-height: 1.35; font-family: 'Outfit', sans-serif;">{{ $event->title }}</h3>
            
            <div style="margin: 0.75rem 0 1.25rem 0;">
                <span class="badge" style="background: var(--accent-light); color: var(--accent-color); font-weight: 700; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.725rem; border: 1px solid rgba(30, 64, 175, 0.1);">
                    Agenda Anggota
                </span>
            </div>

            <!-- Detailed Stats Metadata -->
            <div style="display: flex; flex-direction: column; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem; margin-bottom: 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <i class="ph ph-calendar-blank" style="color: var(--accent-color); font-size: 1.15rem;"></i>
                    <span>Mulai: <strong>{{ $event->start_date->format('d M Y') }}</strong></span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <i class="ph ph-hash" style="color: var(--accent-color); font-size: 1.15rem;"></i>
                    <span>Total Sesi: <strong>{{ $sessions->count() }} Sesi</strong></span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <i class="ph ph-check-square" style="color: var(--warning-color); font-size: 1.15rem;"></i>
                    <span>Terisi: <strong>{{ $sessions->filter(fn($s) => $s->attendances->count() > 0)->count() }} Sesi</strong></span>
                </div>
            </div>

            <!-- Page-centric Action Buttons -->
            <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                <button class="btn btn-primary" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 700; border-radius: var(--radius-md);" onclick="document.getElementById('addSessionModal').style.display='flex'">
                    <i class="ph ph-plus-circle"></i> Buat Sesi Baru
                </button>
                <a href="{{ route('ukm.rekap.members', $event->id) }}" class="btn btn-secondary" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 700; border-radius: var(--radius-md);">
                    <i class="ph ph-list-checks"></i> Rekap Absensi
                </a>
            </div>
        </div>
    </div>

    <!-- RIGHT CONTENT COLUMN: Interactive Timeline Sesi -->
    <div class="card" style="margin-bottom: 0; padding: 1.75rem; border-top: 3px solid var(--accent-color);">
        <h4 style="font-weight: 800; margin: 0 0 2rem 0; font-size: 1.1rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">
            <i class="ph ph-git-commit" style="color: var(--accent-color); font-size: 1.35rem; vertical-align: middle; margin-right: 0.25rem;"></i>
            Timeline Pertemuan & Absensi
        </h4>

        <!-- Timeline Flow container -->
        <div style="position: relative; padding-left: 2.25rem; display: flex; flex-direction: column; gap: 1.75rem;">
            <!-- Vertical Timeline connector line -->
            <div style="position: absolute; left: 11px; top: 10px; bottom: 10px; width: 2px; background: var(--border-color); z-index: 1;"></div>

            @forelse($sessions as $session)
                @php
                    $filledCount = $session->attendances->count();
                @endphp
                <div style="position: relative; display: flex; gap: 1rem; align-items: start;">
                    <!-- Bullet Status Indicator -->
                    <div style="position: absolute; left: -31px; top: 14px; width: 20px; height: 20px; border-radius: 50%; z-index: 2; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;
                        @if($filledCount > 0)
                            background: var(--accent-color); color: white; box-shadow: 0 0 0 4px var(--accent-light);
                        @else
                            background: var(--surface-color); border: 2px solid var(--border-color); color: var(--text-secondary);
                        @endif
                    ">
                        @if($filledCount > 0)
                            <i class="ph ph-check" style="font-size: 0.7rem; font-weight: bold;"></i>
                        @else
                            <div style="width: 6px; height: 6px; border-radius: 50%; background: var(--border-color);"></div>
                        @endif
                    </div>

                    <!-- Horizontal Row Session Card -->
                    <div class="card" style="flex: 1; margin-bottom: 0; padding: 1.25rem; transition: all 0.2s ease; display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; border-top: none; border-left: 1px solid var(--border-color);">
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                                <h4 style="font-weight: 700; font-size: 0.95rem; margin: 0; color: var(--text-primary);">{{ $session->title }}</h4>
                                <span style="font-size: 0.725rem; color: var(--text-secondary); background: var(--bg-color); padding: 0.15rem 0.5rem; border-radius: 6px; border: 1px solid var(--border-color); font-weight: 600;">
                                    {{ $session->date->format('d M Y') }}
                                </span>
                            </div>
                            <p style="font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.45; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $session->description ?: 'Tidak ada deskripsi pertemuan.' }}
                            </p>
                        </div>

                        <div style="display: flex; align-items: center; gap: 1.25rem; flex-shrink: 0;">
                            <!-- Dynamic badge status -->
                            <div>
                                @if($filledCount > 0)
                                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700; font-size: 0.725rem; padding: 0.3rem 0.55rem; border-radius: 6px; border: 1px solid rgba(3, 105, 161, 0.1); display: inline-flex; align-items: center; gap: 0.25rem;">
                                        <i class="ph-fill ph-check-circle" style="font-size: 0.8rem;"></i> {{ $filledCount }} Terisi
                                    </span>
                                @else
                                    <span class="badge" style="background: #f3f4f6; color: #4b5563; font-weight: 600; font-size: 0.725rem; padding: 0.3rem 0.55rem; border-radius: 6px;">
                                        Belum Diisi
                                    </span>
                                @endif
                            </div>

                            <!-- Contextual Action Buttons -->
                            <div style="display: flex; gap: 0.35rem; align-items: center;">
                                <a href="{{ route('ukm.sessions.attendance', $session->id) }}" class="btn btn-primary" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i class="ph ph-check-square"></i> Kelola
                                </a>
                                <form action="/ukm/sessions/{{ $session->id }}" method="POST" onsubmit="return confirm('Hapus pertemuan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding: 0.45rem 0.55rem; font-size: 0.8rem; border-radius: 8px; display: inline-flex; align-items: center;">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 4rem 0; color: var(--text-secondary); width: 100%;">
                    <i class="ph ph-calendar-blank" style="font-size: 3rem; opacity: 0.2;"></i>
                    <p style="margin-top: 1rem; font-weight: 600; color: var(--text-primary);">Belum ada pertemuan pada agenda ini.</p>
                    <p style="font-size: 0.8125rem; opacity: 0.8;">Klik "Buat Sesi Baru" di kolom kiri untuk mulai mencatat absensi.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>


<!-- Modal Tambah Pertemuan -->
<div id="addSessionModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content card" style="width: 100%; max-width: 500px; padding: 2rem;">
        <h3 style="margin-bottom: 1.5rem; font-weight: 800;">Buat Sesi Baru</h3>
        <form action="/ukm/events/{{ $event->id }}/sessions" method="POST">
            @csrf
            <div class="form-group mb-4">
                <label style="font-weight: 700;">Judul Pertemuan</label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Pertemuan 1 / Latihan Fisik" style="border-radius: 12px; height: 46px;" required>
            </div>
            <div class="form-group mb-4">
                <label style="font-weight: 700;">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" style="border-radius: 12px; height: 46px;" required>
            </div>
            <div class="form-group mb-4">
                <label style="font-weight: 700;">Deskripsi (Opsional)</label>
                <textarea name="description" class="form-control" rows="3" style="border-radius: 12px;"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn" style="background: var(--bg-color); font-weight: 700; border-radius: 10px;" onclick="document.getElementById('addSessionModal').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary" style="font-weight: 700; border-radius: 10px;">Simpan Pertemuan</button>
            </div>
        </form>
    </div>
</div>
@endsection
